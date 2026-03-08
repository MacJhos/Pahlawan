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

        $this->chats[] = [
            'role' => 'assistant',
            'content' => $welcomeMessage,
            'source' => 'system'
        ];
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
                $systemInstruction = "You are a professional museum guide. Respond in {$languageName}.
                Start your response by acknowledging this is official gallery data.

                OFFICIAL DATA:
                Category: {$searchResult['type']}
                Name: {$searchResult['name']}
                Information: {$searchResult['content']}

                STRICT RULE: Use the official data provided.
                STRICT RULE: At the end, invite them to see more details using this link: {$searchResult['url']}";

                $sourceLabel = 'Verified Gallery Data';
            } else {
                $disclaimer = ($locale === 'en') ? "I couldn't find that specific item in our catalog." : "Saya tidak menemukan item tersebut di katalog resmi kami.";
                $systemInstruction = "Respond in {$languageName}. Use your general historical knowledge.
                Start by saying: '{$disclaimer}'. Then provide a helpful answer based on general history.";

                $sourceLabel = 'AI General Knowledge';
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
                    'temperature' => 0.3,
                    'max_tokens' => 800,
                ]
            ]);

            $aiResponse = json_decode($response->getBody(), true)['choices'][0]['message']['content'] ?? 'Error.';

            $this->chats[] = [
                'role' => 'assistant',
                'content' => $aiResponse,
                'source' => $sourceLabel
            ];

        } catch (\Exception $e) {
            Log::error("Chatbot AI Error: " . $e->getMessage());
            $this->chats[] = [
                'role' => 'assistant',
                'content' => 'System is a bit sleepy. Try again later!',
                'source' => 'system'
            ];
        }

        $this->isTyping = false;
        $this->dispatch('chat-updated');
    }

    private function findInDatabase($query, $locale)
    {
        $inputLower = strtolower($query);
        $term = '%' . $inputLower . '%';

        $hero = DB::table('heroes')->whereRaw('LOWER(name) LIKE ?', [$term])->first();
        if ($hero) return $this->formatResult($hero, 'hero', $locale);

        $monument = DB::table('monuments')->whereRaw('LOWER(name) LIKE ?', [$term])->first();
        if ($monument) return $this->formatResult($monument, 'monument', $locale);

        $relic = DB::table('relics')->whereRaw('LOWER(name) LIKE ?', [$term])->first();
        if ($relic) return $this->formatResult($relic, 'relic', $locale);

        return ['found' => false];
    }

    private function formatResult($data, $type, $locale)
    {
        $descId = property_exists($data, 'bio_id') ? $data->bio_id : $data->description_id;
        $descEn = property_exists($data, 'bio_en') ? $data->bio_en : $data->description_en;

        $content = ($locale === 'en' && !empty($descEn)) ? $descEn : $descId;
        $identifier = ($type === 'hero') ? $data->slug : $data->id;

        return [
            'found' => true,
            'type' => ucfirst($type),
            'name' => $data->name,
            'content' => strip_tags($content),
            'url' => route('gallery.show', ['type' => $type, 'id_or_slug' => $identifier])
        ];
    }

    public function render() { return view('livewire.hero-chatbot'); }
}
