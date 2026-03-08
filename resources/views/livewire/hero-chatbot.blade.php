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
            class="chat-window-animate absolute bottom-20 right-0 flex h-[550px] w-[350px] flex-col overflow-hidden rounded-3xl shadow-2xl border border-white/10 md:w-[400px] bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-2xl"
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

            <div x-ref="chatBox"
                 x-init="$watch('chats', () => $nextTick(() => { $el.scrollTop = $el.scrollHeight }))"
                 class="flex-1 overflow-y-auto space-y-4 p-5 custom-scrollbar bg-transparent">

                @foreach($chats as $chat)
                    <div class="flex {{ $chat['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[85%]">

                            @if($chat['role'] === 'assistant' && isset($chat['source']) && $chat['source'] !== 'system')
                                <div class="flex mb-1 ml-1">
                                    <span class="text-[8px] font-black px-2 py-0.5 rounded-full uppercase tracking-tighter border
                                        {{ $chat['source'] === 'Verified Gallery Data'
                                            ? 'bg-green-500/20 text-green-600 border-green-500/30'
                                            : 'bg-blue-500/20 text-blue-600 border-blue-500/30' }}">
                                        {{ $chat['source'] }}
                                    </span>
                                </div>
                            @endif

                            <div class="rounded-2xl px-4 py-3 text-sm shadow-sm transition-all
                                {{ $chat['role'] === 'user'
                                    ? 'bg-blue-600/80 backdrop-blur-md text-white rounded-tr-none'
                                    : 'bg-white/60 dark:bg-slate-800/60 backdrop-blur-md text-slate-800 dark:text-slate-100 border border-white/20 rounded-tl-none shadow-md' }}">

                                <div class="leading-relaxed">
                                    {!! nl2br(e($chat['content'])) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($isTyping)
                    <div class="flex justify-start">
                        <div class="bg-white/40 dark:bg-slate-800/40 backdrop-blur-md px-4 py-3 rounded-2xl rounded-tl-none border border-white/10">
                            <div class="flex gap-1">
                                <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-blue-500"></span>
                                <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-blue-500 [animation-delay:0.2s]"></span>
                                <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-blue-500 [animation-delay:0.4s]"></span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <form wire:submit.prevent="sendMessage" class="p-4 bg-white/5 border-t border-white/10">
                <div class="relative flex items-center">
                    <input
                        type="text"
                        wire:model="message"
                        placeholder="{{ app()->getLocale() === 'id' ? 'Tanya sesuatu...' : 'Ask something...' }}"
                        class="w-full rounded-xl border border-white/10 bg-white/10 backdrop-blur-md py-3 pl-4 pr-12 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all"
                    >
                    <button
                        type="submit"
                        class="absolute right-2 flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500 text-white transition-all hover:bg-blue-600 active:scale-90 disabled:opacity-50"
                        wire:loading.attr="disabled"
                    >
                        <span class="material-symbols-outlined text-sm">send</span>
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
