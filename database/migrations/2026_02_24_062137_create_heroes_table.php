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
            ? 'DATABASE DIAGNOSTIC MODE: Ready to check hero data.'
            : 'MODE DIAGNOSA DATABASE: Siap mengecek data pahlawan.';

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

        // --- MULAI DIAGNOSA DATABASE ---
        try {
            // Kita gunakan pencarian yang persis sama dengan yang kamu lakukan di Tinker
            $searchTerm = '%' . strtolower($userQuery) . '%';

            $hero = DB::table('heroes')
                ->whereRaw('LOWER(name) LIKE ?', [$searchTerm])
                ->orWhereRaw('LOWER(slug) LIKE ?', [$searchTerm])
                ->first();

            if ($hero) {
                // JIKA DATA DITEMUKAN: Tampilkan data mentah dari DB
                $bio = ($locale === 'en' && !empty($hero->bio_en)) ? $hero->bio_en : $hero->bio_id;

                $response = "✅ **DATA BERHASIL DITEMUKAN!**\n\n";
                $response .= "**ID:** " . $hero->id . "\n";
                $response .= "**Nama:** " . $hero->name . "\n";
                $response .= "**Asal:** " . $hero->hometown . "\n";
                $response .= "**Bio:** " . Str::limit(strip_tags($bio), 500) . "\n\n";
                $response .= "--- \n_Pesan: Jika kamu melihat ini, berarti Livewire sudah bisa membaca database kamu dengan benar._";
            } else {
                // JIKA DATA TIDAK ADA: Cek total data yang ada di tabel
                $totalData = DB::table('heroes')->count();

                $response = "❌ **DATA TIDAK DITEMUKAN DI DATABASE.**\n\n";
                $response .= "Input kamu: `" . $userQuery . "`\n";
                $response .= "Total pahlawan di tabel `heroes`: **" . $totalData . "**\n\n";
                $response .= "_Saran: Jika total data adalah 0, berarti Web Server kamu membaca file database yang berbeda dengan Tinker._";
            }
        } catch (\Exception $e) {
            $response = "⚠️ **ERROR DATABASE:**\n" . $e->getMessage();
        }

        $this->chats[] = ['role' => 'assistant', 'content' => $response];
        $this->isTyping = false;
        $this->dispatch('chat-updated');
    }

    public function render()
    {
        return view('livewire.hero-chatbot');
    }
}
