@extends('app')

@section('content')
<div class="mx-auto max-w-[1440px] px-6 py-8">

    @php
        $locale = app()->getLocale();

        $soekarno = $heroes->filter(function($hero) {
            return stripos($hero->name, 'Soekarno') !== false;
        })->first();

        $soekarnoImg = $soekarno
            ? (str_contains($soekarno->image_path, 'img/') ? asset('storage/' . $soekarno->image_path) : asset('storage/img/' . $soekarno->image_path))
            : asset('storage/img/soekarno_hero.jpg');

        $randomHeroes = $heroes->shuffle();
    @endphp

    <section class="relative mb-16 overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-primary/5 shadow-2xl">
        <div class="flex flex-col lg:flex-row items-stretch">
            <div class="relative h-[350px] lg:h-[520px] lg:w-1/2 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white dark:to-slate-900 z-10 hidden lg:block"></div>
                <div class="h-full w-full bg-cover bg-center transition-transform duration-1000 hover:scale-105"
                     style="background-image: url('{{ $soekarnoImg }}');">
                </div>
            </div>

            <div class="flex flex-col justify-center p-10 lg:p-20 lg:w-1/2 z-20">
                <div class="mb-6 inline-flex w-fit items-center gap-2 rounded-full bg-accent-gold/10 px-4 py-1.5 text-xs font-black uppercase tracking-widest text-accent-gold">
                    <span class="material-symbols-outlined text-xs">star</span>
                    {{ __('messages.hero_day') }}
                </div>

                <h2 class="mb-3 text-5xl font-black text-slate-900 dark:text-white lg:text-6xl tracking-tighter">
                    {{ $soekarno->name ?? 'Soekarno' }}
                </h2>

                <p class="mb-8 text-xl font-bold text-primary italic">
                    "{{ $soekarno ? __('messages.' . $soekarno->category) : __('messages.National Hero') }}"
                </p>

                <p class="mb-10 text-slate-600 dark:text-slate-400 leading-relaxed text-lg max-w-lg line-clamp-4">
                    @if($soekarno)
                        {{ $locale == 'id' ? $soekarno->bio_id : ($soekarno->bio_en ?? $soekarno->bio_id) }}
                    @else
                        {{ __('messages.soekarno_default_bio') }}
                    @endif
                </p>

                <div class="flex flex-wrap gap-4">
                    @if($soekarno)
                        <a href="{{ route('hero.show', $soekarno->slug) }}" class="flex items-center gap-2 rounded-xl bg-primary px-10 py-4 text-base font-black text-white shadow-xl shadow-primary/25 hover:-translate-y-1 transition-all">
                            {{ __('messages.read_bio') }} <span class="material-symbols-outlined">auto_stories</span>
                        </a>
                    @endif
                    <a href="{{ route('galeri') }}" class="flex items-center gap-2 rounded-xl border-2 border-slate-200 px-10 py-4 text-base font-bold text-slate-700 hover:bg-slate-50 transition-all dark:border-slate-700 dark:text-slate-300">
                        {{ __('messages.explore') }} <span class="material-symbols-outlined">collections_bookmark</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="mb-10 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div class="space-y-1">
                <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                    {{ __('messages.discover') }}
                </h2>
                <p class="text-slate-500 font-medium">
                    {{ __('messages.subtitle') }}
                </p>
            </div>
        </div>

        <div id="hero-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($randomHeroes as $hero)
                <article onclick="window.location.href='{{ route('hero.show', $hero->slug) }}'" class="hero-card-hover group cursor-pointer overflow-hidden rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-2xl hover:-translate-y-1 hover:border-primary/20">
                    <div class="relative aspect-[4/5] overflow-hidden">
                        <div class="hero-image h-full w-full bg-cover bg-center transition-transform duration-700 group-hover:scale-110"
                             style="background-image: url('{{ str_contains($hero->image_path, 'img/') ? asset('storage/' . $hero->image_path) : asset('storage/img/' . $hero->image_path) }}');">
                        </div>

                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60"></div>
                        <div class="absolute bottom-4 left-4">
                            <span class="rounded-lg bg-accent-gold/90 backdrop-blur-sm px-3 py-1 text-[10px] font-black text-white uppercase tracking-widest shadow-lg">
                                {{ __('messages.' . $hero->category) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="mb-2 text-xl font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors leading-tight">
                            {{ $hero->name }}
                        </h3>

                        <div class="flex items-center gap-1.5 text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">
                            <span class="material-symbols-outlined text-[16px] text-primary">location_on</span>
                            {{ $hero->hometown }}
                        </div>

                        <div class="pt-4 border-t border-slate-50 dark:border-slate-800 flex items-center justify-between text-xs text-slate-400">
                            <div class="flex flex-col">
                                <span class="text-[9px] uppercase font-black text-slate-300">
                                    {{ __('messages.period') }}
                                </span>
                                <span class="font-bold text-slate-600 dark:text-slate-400">
                                    {{ $hero->birth_date ? \Carbon\Carbon::parse($hero->birth_date)->format('Y') : '????' }} —
                                    {{ $hero->death_date ? \Carbon\Carbon::parse($hero->death_date)->format('Y') : __('messages.present') }}
                                </span>
                            </div>
                            <span class="material-symbols-outlined text-primary opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all">
                                arrow_forward
                            </span>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-slate-400 italic">{{ __('messages.no_data') }}</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
