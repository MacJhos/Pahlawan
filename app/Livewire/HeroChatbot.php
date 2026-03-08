<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

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
            ? 'Hello! I am your Gallery Assistant. Ask me about Heroes, Monuments, or Historical Relics.'
            : 'Halo! Saya asisten Galeri. Tanyakan saya tentang Pahlawan, Monumen, atau Benda Bersejarah.';

        $this->chats[] = ['role' => 'assistant', 'content' => $welcomeMessage, 'source' => 'system'];
    }

    public function toggleChat() { $this->isOpen = !$this->isOpen; }

    public function sendMessage()
    {
        $userQuery = trim($this->message);
        if ($userQuery === '') return;

        $this->chats[] = ['role' => 'user', 'content' => $userQuery];
        $this->message = '';
        $this->isTyping = true;

        $locale = App::getLocale();
        $languageName = $locale === 'en' ? 'English' : 'Indonesian';

        $searchResult = $this->findInDatabase($userQuery, $locale);

        try {
            $apiKey = env('GROQ_API_KEY');
            $client = new Client(['verify' => false]);

            if ($searchResult['found']) {
                $sourceLabel = 'Verified Gallery Data';

                $correctionText = ($searchResult['is_typo'])
                    ? "Mungkin yang Anda maksud adalah **{$searchResult['name']}**? Berikut informasinya:\n\n"
                    : "";

                $systemInstruction = "You are a professional historian. Respond in {$languageName}.
                STRICT RULE: Directly provide information. DO NOT use 'Official Data' or intro.
                STRICT RULE: DO NOT ask follow-up questions.
                STRICT RULE: At the end, only provide the link: {$searchResult['url']}

                DATA: Name: {$searchResult['name']}, Content: {$searchResult['content']}";
            } else {
                $sourceLabel = 'AI General Knowledge';

                $systemInstruction = "You are a historical expert. Respond in {$languageName}.
                STRICT RULE: If the user made a typo (e.g., 'napoleun'), start your response with: 'Mungkin yang Anda maksud adalah **[Correct Name]**? Berikut informasinya:'
                STRICT RULE: Directly explain the topic. NO introductory phrases or follow-up questions.";
                $correctionText = "";
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
                    'temperature' => 0.1,
                ]
            ]);

            $aiResponse = json_decode($response->getBody(), true)['choices'][0]['message']['content'] ?? 'Error.';
            $cleanAiResponse = trim(preg_replace('/^(Official Data|Data Resmi):/i', '', $aiResponse));

            $this->chats[] = [
                'role' => 'assistant',
                'content' => $correctionText . $cleanAiResponse,
                'source' => $sourceLabel
            ];

        } catch (\Exception $e) {
            Log::error("Chatbot Error: " . $e->getMessage());
            $this->chats[] = ['role' => 'assistant', 'content' => 'Error.', 'source' => 'system'];
        }

        $this->isTyping = false;
        $this->dispatch('chat-updated');
    }

    private function findInDatabase($query, $locale)
    {
        $inputLower = strtolower($query);
        $allData = DB::table('heroes')->select('name', 'bio_id as content', 'slug as id')->get()->map(fn($i) => (object)[...((array)$i), 'type' => 'hero'])
            ->concat(DB::table('monuments')->select('name', 'description_id as content', 'id')->get()->map(fn($i) => (object)[...((array)$i), 'type' => 'monument']))
            ->concat(DB::table('relics')->select('name', 'description_id as content', 'id')->get()->map(fn($i) => (object)[...((array)$i), 'type' => 'relic']));

        $bestMatch = null;
        $highestSimilarity = 0;

        foreach ($allData as $item) {
            $nameLower = strtolower($item->name);
            if ($inputLower === $nameLower) return ['found' => true, 'is_typo' => false, 'name' => $item->name, 'content' => strip_tags($item->content), 'url' => route('gallery.show', ['type' => $item->type, 'id_or_slug' => $item->id])];

            similar_text($inputLower, $nameLower, $percent);
            if ($percent > 75 && $percent > $highestSimilarity) {
                $highestSimilarity = $percent;
                $bestMatch = $item;
            }
        }

        if ($bestMatch) {
            return ['found' => true, 'is_typo' => true, 'name' => $bestMatch->name, 'content' => strip_tags($bestMatch->content), 'url' => route('gallery.show', ['type' => $bestMatch->type, 'id_or_slug' => $bestMatch->id])];
        }

        return ['found' => false];
    }

    public function render() { return view('livewire.hero-chatbot'); }
}
