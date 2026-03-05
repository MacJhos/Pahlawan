<div class="fixed bottom-6 right-6 z-[9999] font-sans">
    <button
        wire:click="toggleChat"
        style="background-color: rgba(59, 130, 246, 0.5); backdrop-blur-xl; -webkit-backdrop-filter: blur(8px);"
        class="flex h-16 w-16 flex-col items-center justify-center rounded-full text-white shadow-2xl transition-all hover:scale-110 active:scale-95 focus:outline-none border border-white/20"
    >
        @if($isOpen)
            <span class="material-symbols-outlined text-3xl">close</span>
        @else
            <span class="material-symbols-outlined text-2xl leading-none">chat</span>
        @endif
    </button>

    @if($isOpen)
        <div
            class="chat-window-animate absolute bottom-20 right-0 flex h-[550px] w-[350px] flex-col overflow-hidden rounded-3xl shadow-2xl border border-white/10 md:w-[400px]"
        >
            <div style="background-color: rgba(59, 130, 246, 0.8); backdrop-blur-lg;" class="p-5 text-white shadow-lg border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                        <span class="material-symbols-outlined">account_balance</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-black uppercase tracking-widest leading-none">
                            {{ app()->getLocale() === 'id' ? 'Asisten Galeri' : 'Gallery Assistant' }}
                        </h4>
                        <span class="text-[10px] opacity-80 font-bold italic mt-1 block">
                            {{ app()->getLocale() === 'id' ? 'AI Sejarah Online' : 'Online History AI' }}
                        </span>
                    </div>
                </div>
            </div>

            <div x-ref="chatBox" class="flex-1 overflow-y-auto space-y-4 p-5 custom-scrollbar bg-transparent">
                @foreach($chats as $chat)
                    <div class="flex {{ $chat['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[85%] rounded-2xl px-4 py-3 text-sm shadow-sm
                            {{ $chat['role'] === 'user'
                                ? 'bg-blue-500/70 backdrop-blur-md text-white rounded-tr-none'
                                : 'bg-white/10 backdrop-blur-md text-slate-700 dark:bg-slate-800/40 dark:text-slate-200 border border-white/10 rounded-tl-none' }}">
                            {!! nl2br(e($chat['content'])) !!}
                        </div>
                    </div>
                @endforeach
            </div>

            <form wire:submit.prevent="sendMessage" class="p-4 bg-transparent border-t border-white/10">
                <div class="relative flex items-center">
                    <input type="text" wire:model="message" class="w-full rounded-xl border border-white/10 bg-white/5 backdrop-blur-md py-3 pl-4 pr-12 text-sm text-slate-900 dark:text-white">
                    <button type="submit" class="absolute right-2 text-blue-500">
                        <span class="material-symbols-outlined">send</span>
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
