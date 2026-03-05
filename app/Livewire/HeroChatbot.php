<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Hero;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class HeroChatbot extends Component
{
    public $isOpen = false;
    public $message = '';
    public $chats = [];
    public $isTyping = false;

    public function mount()
    {
        $locale = App::getLocale();
        $welcomeMessage = $locale === 'en'
            ? 'Hello! I am the Heroes Gallery assistant. Is there a hero you would like to ask about?'
            : 'Halo! Saya asisten Galeri Pahlawan. Ada pahlawan yang ingin Anda tanyakan?';

        $this->chats[] = ['role' => 'assistant', 'content' => $welcomeMessage];
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function sendMessage()
    {
        $userQuery = trim($this->message);
        if ($userQuery === '') return;

        $this->chats[] = ['role' => 'user', 'content' => $userQuery];
        $this->message = '';
        $this->isTyping = true;

        $locale = App::getLocale();
        $languageName = $locale === 'en' ? 'English' : 'Indonesian';

        $databaseContext = "";
        $foundInDatabase = false;
        $matchedHeroName = "";

        // 1. PENCARIAN CERDAS (Mendeteksi Typo & Ekstraksi Nama)
        try {
            $inputLower = strtolower($userQuery);
            $allHeroes = DB::table('heroes')->select('name', 'bio_id', 'bio_en', 'hometown')->get();

            $bestMatch = null;
            $highestSimilarity = 0;

            foreach ($allHeroes as $hero) {
                $heroName = strtolower($hero->name);

                // Cek A: Jika nama ada utuh di kalimat (Contoh: "jelsakan dewi sartika")
                if (str_contains($inputLower, $heroName)) {
                    $bestMatch = $hero;
                    $foundInDatabase = true;
                    break;
                }

                // Cek B: Fuzzy Search per kata (Toleransi Typo)
                $words = explode(' ', $inputLower);
                foreach ($words as $word) {
                    if (strlen($word) < 4) continue;

                    similar_text($word, $heroName, $percent);
                    if ($percent > 80 && $percent > $highestSimilarity) {
                        $highestSimilarity = $percent;
                        $bestMatch = $hero;
                    }
                }
            }

            if ($bestMatch) {
                $foundInDatabase = true;
                $matchedHeroName = $bestMatch->name;
                $rawBio = ($locale === 'en' && !empty($bestMatch->bio_en)) ? $bestMatch->bio_en : $bestMatch->bio_id;
                $cleanBio = preg_replace('/\s+/', ' ', strip_tags($rawBio));

                $databaseContext = "DATA RESMI DATABASE KAMI:\n";
                $databaseContext .= "Nama Pahlawan: {$bestMatch->name}\nBiografi: {$cleanBio}";
            }
        } catch (\Exception $e) {
            Log::error("Search Error: " . $e->getMessage());
        }

        // 2. TANYA KE GROQ AI (Strict Mode)
        try {
            $apiKey = env('GROQ_API_KEY');
            $client = new \GuzzleHttp\Client(['verify' => false]);

            if ($foundInDatabase) {
                // KUNCI AI: Larang halusinasi mitologi dan paksa pakai data kita
                $systemInstruction = "You are a strict history assistant. Respond in {$languageName}.
                The user asked about '{$matchedHeroName}'.
                STRICT RULE: Use ONLY the provided database info below.
                STRICT RULE: Do NOT mention mythology, deities, or anything outside this data.
                STRICT RULE: Do NOT say 'Mohon maaf'.
                OFFICIAL DATA: \n" . $databaseContext;
            } else {
                $disclaimer = ($locale === 'en') ? "I'm sorry, data not found." : "Mohon maaf, data pahlawan ini tidak tersedia di katalog kami.";
                $systemInstruction = "Respond in {$languageName}. Start with: '{$disclaimer}'";
            }

            $response = $client->post('https://api.groq.com/openai/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'messages' => [
                        ['role' => 'system', 'content' => $systemInstruction],
                        ['role' => 'user', 'content' => $userQuery]
                    ],
                    'model' => 'llama-3.3-70b-versatile',
                    'temperature' => 0.0, // WAJIB 0.0 agar AI tidak "ngaco" atau berimajinasi
                    'max_tokens' => 800,
                ]
            ]);

            $aiResponse = json_decode($response->getBody(), true)['choices'][0]['message']['content'] ?? 'Error.';
            $this->chats[] = ['role' => 'assistant', 'content' => $aiResponse];

        } catch (\Exception $e) {
            $this->chats[] = ['role' => 'assistant', 'content' => 'API Offline.'];
        }

        $this->isTyping = false;
        $this->dispatch('chat-updated');
    }

    public function render()
    {
        return view('livewire.hero-chatbot');
    }
}
