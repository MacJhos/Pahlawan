<header class="sticky top-0 z-50 w-full border-b border-primary/10 bg-background-light/80 backdrop-blur-md dark:bg-background-dark/80">
    <div class="mx-auto flex max-w-none items-center justify-between px-6 py-4">

        <div class="flex items-center gap-8">
            <a href="{{ route('home') }}" class="group flex items-center gap-3 transition-transform active:scale-95">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary text-white shadow-lg shadow-primary/20 group-hover:rotate-6 transition-all">
                    <span class="material-symbols-outlined">account_balance</span>
                </div>
                <div class="flex flex-col">
                    <h1 class="text-lg font-black uppercase tracking-tight text-primary leading-none">Digital Legacy</h1>
                    <span class="text-[10px] font-bold tracking-[0.2em] text-slate-500 uppercase group-hover:text-accent-gold transition-colors">
                        Heroes of Indonesia
                    </span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-6">
                @php
                    $menus = [
                        ['route' => 'home', 'label' => app()->getLocale() == 'en' ? 'Home' : 'Beranda'],
                        ['route' => 'galeri', 'label' => app()->getLocale() == 'en' ? 'Gallery' : 'Galeri'],
                        ['route' => 'about', 'label' => app()->getLocale() == 'en' ? 'About' : 'Tentang'],
                    ];
                @endphp

                @foreach($menus as $menu)
                    <a class="text-sm font-medium transition-colors hover:text-primary {{ request()->routeIs($menu['route']) ? 'text-primary font-bold' : 'text-slate-600' }}"
                       href="{{ route($menu['route']) }}">
                        {{ $menu['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="flex flex-1 justify-end gap-6 max-w-2xl ml-8">
            {{-- Search bar --}}
            @if(request()->routeIs('galeri'))
            <div class="relative w-full max-w-md group">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
                <input id="search-input" name="search" autocomplete="off" class="w-full rounded-lg border-none bg-slate-100 py-2.5 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary/20 dark:bg-slate-800 transition-all text-slate-900 dark:text-white"
                       placeholder="{{ app()->getLocale() == 'en' ? 'Search heroes...' : 'Cari pahlawan...' }}" type="text" value="{{ request('search') }}"/>
            </div>
            @endif

            {{-- Language Switcher --}}
            <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-xl border border-primary/5">
                <a href="{{ route('lang.switch', 'id') }}"
                   class="px-3 py-1.5 rounded-lg text-[10px] font-black tracking-widest transition-all {{ app()->getLocale() == 'id' ? 'bg-primary text-white shadow-md' : 'text-slate-400 hover:text-primary' }}">
                    ID
                </a>
                <a href="{{ route('lang.switch', 'en') }}"
                   class="px-3 py-1.5 rounded-lg text-[10px] font-black tracking-widest transition-all {{ app()->getLocale() == 'en' ? 'bg-primary text-white shadow-md' : 'text-slate-400 hover:text-primary' }}">
                    EN
                </a>
            </div>
        </div>
    </div>
</header>
