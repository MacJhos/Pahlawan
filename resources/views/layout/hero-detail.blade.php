@extends('app')

@section('content')
<div class="py-12 bg-slate-50 dark:bg-slate-950">
    <div class="mx-auto max-w-[1440px] px-6">

        @php
            $locale = app()->getLocale();
        @endphp

        <a href="{{ route('galeri') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:gap-3 transition-all mb-8 group">
            <span class="material-symbols-outlined">arrow_back</span>
            {{ __('messages.back_to_gallery') }}
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

            <div class="lg:col-span-4 space-y-6">
                <div class="sticky top-24">
                    <div class="overflow-hidden rounded-3xl shadow-2xl border-4 border-white dark:border-slate-800">
                        <img src="{{ str_contains($hero->image_path, 'img/') ? asset('storage/' . $hero->image_path) : asset('storage/img/' . $hero->image_path) }}"
                             alt="{{ $hero->name }}"
                             class="w-full aspect-[3/4] object-cover">
                    </div>

                    <div class="mt-6 p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                        <h2 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4">
                            {{ __('messages.core_info') }}
                        </h2>
                        <ul class="space-y-4">
                            <li class="flex flex-col">
                                <span class="text-[10px] font-bold text-primary uppercase">{{ __('messages.category') }}</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">
                                    {{ __('messages.' . $hero->category) }}
                                </span>
                            </li>
                            <li class="flex flex-col">
                                <span class="text-[10px] font-bold text-primary uppercase">{{ __('messages.hometown') }}</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">{{ $hero->hometown }}</span>
                            </li>
                            <li class="flex flex-col">
                                <span class="text-[10px] font-bold text-primary uppercase">{{ __('messages.lifespan') }}</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">
                                    {{ \Carbon\Carbon::parse($hero->birth_date)->format('Y') }} —
                                    {{ $hero->death_date ? \Carbon\Carbon::parse($hero->death_date)->format('Y') : __('messages.present') }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="space-y-8">
                    <div>
                        <span class="inline-block px-4 py-1 rounded-full bg-primary/10 text-primary text-xs font-black uppercase mb-4 tracking-tighter">
                            {{ __('messages.history_struggle') }}
                        </span>
                        <h1 class="text-5xl lg:text-6xl font-black text-slate-900 dark:text-white leading-tight">
                            {{ $hero->name }}
                        </h1>
                    </div>

                    @if($hero->quotes)
                    <div class="relative p-8 rounded-3xl bg-primary text-white overflow-hidden shadow-xl shadow-primary/20">
                        <span class="material-symbols-outlined absolute -right-4 -top-4 text-9xl opacity-20 rotate-12">format_quote</span>
                        <p class="relative z-10 text-xl italic font-medium leading-relaxed">
                            "{{ $hero->quotes }}"
                        </p>
                    </div>
                    @endif

                    <div class="prose prose-slate dark:prose-invert max-w-none">
                        <h2 class="text-2xl font-bold flex items-center gap-3 mb-6">
                            <span class="size-3 bg-primary rounded-full"></span>
                            {{ __('messages.history_struggle') }}
                        </h2>

                        <div class="hero-bio text-slate-600 dark:text-slate-300 leading-relaxed text-lg text-justify space-y-6">
                            {!! nl2br(e($locale == 'id' ? $hero->bio_id : ($hero->bio_en ?? $hero->bio_id))) !!}
                        </div>
                    </div>

                    <div class="pt-12 border-t border-slate-200 dark:border-slate-800">
                        <h2 class="text-2xl font-bold mb-8">{{ __('messages.struggle_timeline') }}</h2>
                        <div class="space-y-8">
                            <div class="flex gap-6">
                                <div class="font-black text-primary text-xl">
                                    {{ \Carbon\Carbon::parse($hero->birth_date)->format('Y') }}
                                </div>
                                <div class="flex-1 pb-8 border-b border-slate-100 dark:border-slate-800">
                                    <h4 class="font-bold text-lg">
                                        {{ __('messages.birth') }}
                                    </h4>
                                    <p class="text-slate-500 text-sm mt-1">
                                        {{ __('messages.born_in') }}{{ $hero->hometown }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
