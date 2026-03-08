@extends('app')

@section('content')
<div class="mx-auto max-w-[1440px] px-6 py-12 md:py-20">

    @php
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

    <nav class="flex flex-wrap justify-center gap-3 mb-12" id="gallery-filters">
        <button data-filter="all" class="filter-btn px-6 py-2.5 rounded-full text-sm font-bold transition-all border bg-primary text-white border-primary shadow-lg shadow-primary/20">
            {{ __('messages.all_media') }}
        </button>
        <button data-filter="hero" class="filter-btn px-6 py-2.5 rounded-full text-sm font-bold transition-all border bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-transparent hover:border-primary/50">
            {{ __('messages.hero_data') }}
        </button>
        <button data-filter="relic" class="filter-btn px-6 py-2.5 rounded-full text-sm font-bold transition-all border bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-transparent hover:border-primary/50">
            {{ __('messages.relic') }}
        </button>
        <button data-filter="monument" class="filter-btn px-6 py-2.5 rounded-full text-sm font-bold transition-all border bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-transparent hover:border-primary/50">
            {{ __('messages.monuments') }}
        </button>
    </nav>

    {{-- Grid Gabungan --}}
    <div id="gallery-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($allMedia as $index => $item)
            @php
                $identifier = ($item->type === 'hero') ? $item->slug : $item->id;

                $imgPath = $item->image_path;
                if($item->type == 'hero' && !str_contains($imgPath, 'img/')) {
                    $imgPath = 'img/' . $imgPath;
                }
            @endphp

            <div class="gallery-item group block" data-type="{{ $item->type }}"
                 @if($index >= $initialDisplay) style="display: none;" @endif>

                <a href="{{ route('gallery.show', ['type' => $item->type, 'id_or_slug' => $identifier]) }}">
                    <article class="hero-card-hover cursor-pointer overflow-hidden rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-2xl hover:-translate-y-1 hover:border-primary/20">

                        <div class="relative aspect-[4/5] overflow-hidden bg-slate-100 dark:bg-slate-800">
                            <img src="{{ asset('storage/' . $imgPath) }}" alt="{{ $item->name }}" loading="lazy"
                                 class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">

                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/10 to-transparent opacity-60 transition-opacity group-hover:opacity-80"></div>

                            <div class="absolute bottom-4 left-4 flex gap-2">
                                <span class="rounded-lg bg-primary/90 backdrop-blur-sm px-3 py-1 text-[10px] font-black text-white uppercase tracking-widest shadow-lg">
                                    {{ $item->type }}
                                </span>
                                @if(isset($item->category))
                                <span class="rounded-lg bg-accent-gold/90 backdrop-blur-sm px-3 py-1 text-[10px] font-black text-white uppercase tracking-widest shadow-lg">
                                    {{ __('messages.' . $item->category) }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors leading-tight mb-2">
                                {{ $item->name }}
                            </h3>
                            <div class="flex items-center gap-1.5 text-xs font-bold text-primary uppercase tracking-widest mb-6">
                                <span class="material-symbols-outlined text-[16px]">location_on</span>
                                {{ $item->hometown }}
                            </div>

                            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-end justify-between">
                                <div class="space-y-1">
                                    <span class="block text-[10px] uppercase text-slate-400 font-black tracking-tighter">
                                        {{ $item->type == 'relic' ? 'ESTIMATED AGE' : __('messages.lifespan') }}
                                    </span>
                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300 italic">
                                        @if($item->type == 'hero')
                                            {{ $item->birth_date ? \Carbon\Carbon::parse($item->birth_date)->format('Y') : '????' }} —
                                            {{ $item->death_date ? \Carbon\Carbon::parse($item->death_date)->format('Y') : __('messages.present') }}
                                        @elseif($item->type == 'monument')
                                            Sejarah / Monument
                                        @else
                                            {{ $item->estimated_age }}
                                        @endif
                                    </span>
                                </div>
                                <div class="size-10 rounded-full bg-primary/5 flex items-center justify-center group-hover:bg-primary transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-primary group-hover:text-white transition-colors text-[20px]">arrow_forward</span>
                                </div>
                            </div>
                        </div>
                    </article>
                </a>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-slate-400 italic">{{ __('messages.no_archives') }}</p>
            </div>
        @endforelse
    </div>

    <footer class="mt-20 flex flex-col items-center">
        @if($allMedia->count() > $initialDisplay)
            <button id="load-more-btn"
                    class="group flex items-center gap-3 rounded-2xl bg-white dark:bg-slate-900 border-2 border-primary/10 px-12 py-4 text-primary font-black hover:bg-primary hover:text-white hover:border-primary transition-all shadow-xl active:scale-95 mb-8">
                <span id="btn-text">{{ __('messages.explore_more') }}</span>
                <span class="material-symbols-outlined group-hover:translate-y-1 transition-transform">expand_more</span>
            </button>
        @endif
        <p class="text-slate-400 text-sm font-medium">
            Total Koleksi: <span class="text-slate-900 dark:text-white font-bold">{{ $allMedia->count() }}</span> Item
        </p>
    </footer>
</div>
@endsection
