@extends('app')

@section('content')
<div class="mx-auto max-w-[1440px] px-6 py-12 md:py-20">

    @php
        $sortedHeroes = $heroes->sortBy('name');
        $initialDisplay = 6;
    @endphp

    <header class="mb-16 text-center">
        <h2 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">
            {{ __('messages.archive_title') }} <span class="text-primary">{{ __('messages.gallery') }}</span>
        </h2>
        <p class="text-slate-500 max-w-2xl mx-auto leading-relaxed">
            {{ __('messages.archive_subtitle') }}
        </p>
    </header>

    {{-- Filter Navigasi --}}
    <nav class="flex flex-wrap justify-center gap-3 mb-12">
        @php
            $currentFilters = [
                __('messages.all_media'),
                __('messages.rare_photos'),
                __('messages.manuscripts'),
                __('messages.monuments')
            ];
        @endphp
        @foreach($currentFilters as $filter)
            <button class="px-6 py-2.5 rounded-full text-sm font-bold transition-all border
                {{ $loop->first ? 'bg-primary text-white border-primary shadow-lg shadow-primary/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-transparent hover:border-primary/50' }}">
                {{ $filter }}
            </button>
        @endforeach
    </nav>

    {{-- Grid Hero --}}
    <div id="hero-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($sortedHeroes as $index => $hero)
            <a href="{{ route('hero.show', $hero->slug) }}"
               class="hero-item group block"
               @if($index >= $initialDisplay) style="display: none;" @endif>
                <article class="hero-card-hover cursor-pointer overflow-hidden rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-2xl hover:-translate-y-1 hover:border-primary/20">

                    <div class="relative aspect-[4/5] overflow-hidden bg-slate-100 dark:bg-slate-800">
                        <img
                            src="{{ str_contains($hero->image_path, 'img/') ? asset('storage/' . $hero->image_path) : asset('storage/img/' . $hero->image_path) }}"
                            alt="{{ $hero->name }}"
                            loading="lazy"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                        >

                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/10 to-transparent opacity-60 transition-opacity group-hover:opacity-80"></div>
                        <div class="absolute bottom-4 left-4">
                            <span class="rounded-lg bg-accent-gold/90 backdrop-blur-sm px-3 py-1 text-[10px] font-black text-white uppercase tracking-widest shadow-lg">
                                {{ __('messages.' . $hero->category) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors leading-tight mb-2">
                            {{ $hero->name }}
                        </h3>
                        <div class="flex items-center gap-1.5 text-xs font-bold text-primary uppercase tracking-widest mb-6">
                            <span class="material-symbols-outlined text-[16px]">location_on</span>
                            {{ $hero->hometown }}
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-end justify-between">
                            <div class="space-y-1">
                                <span class="block text-[10px] uppercase text-slate-400 font-black tracking-tighter">
                                    {{ __('messages.lifespan') }}
                                </span>
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300 italic">
                                    {{ $hero->birth_date ? \Carbon\Carbon::parse($hero->birth_date)->format('Y') : '????' }} —
                                    {{ $hero->death_date ? \Carbon\Carbon::parse($hero->death_date)->format('Y') : __('messages.present') }}
                                </span>
                            </div>
                            <div class="size-10 rounded-full bg-primary/5 flex items-center justify-center group-hover:bg-primary transition-all shadow-sm">
                                <span class="material-symbols-outlined text-primary group-hover:text-white transition-colors">arrow_forward</span>
                            </div>
                        </div>
                    </div>
                </article>
            </a>
        @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-slate-400 italic">
                    {{ __('messages.no_archives') }}
                </p>
            </div>
        @endforelse
    </div>

    <footer class="mt-20 flex flex-col items-center">
        @if($sortedHeroes->count() > $initialDisplay)
            <button id="load-more-btn"
                    data-more="{{ __('messages.explore_more') }}"
                    data-less="{{ __('messages.show_less') }}"
                    class="group flex items-center gap-3 rounded-2xl bg-white dark:bg-slate-900 border-2 border-primary/10 px-12 py-4 text-primary font-black hover:bg-primary hover:text-white hover:border-primary transition-all shadow-xl shadow-primary/5 active:scale-95 mb-8">
                <span id="btn-text">{{ __('messages.explore_more') }}</span>
                <span class="material-symbols-outlined group-hover:translate-y-1 transition-transform duration-300">expand_more</span>
            </button>
        @endif

        <p id="hero-count" class="text-slate-400 text-sm font-medium">
            {{ __('messages.showing') }}
            <span id="current-count" class="text-slate-900 dark:text-white font-bold">{{ min($sortedHeroes->count(), $initialDisplay) }}</span>
            {{ __('messages.of') }}
            <span class="font-bold">{{ $sortedHeroes->count() }}</span>
            {{ __('messages.historical_items') }}
        </p>
    </footer>
</div>
@endsection
