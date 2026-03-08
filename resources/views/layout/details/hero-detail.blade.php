@extends('app')

@section('content')
<div class="py-12 bg-slate-50 dark:bg-slate-950">
    <div class="mx-auto max-w-[1440px] px-6">
        @php $locale = app()->getLocale(); @endphp

        <a href="{{ route('galeri') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:gap-3 transition-all mb-8 group">
            <span class="material-symbols-outlined">arrow_back</span>
            {{ __('messages.back_to_gallery') }}
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <div class="lg:col-span-4 space-y-6">
                <div class="sticky top-24">
                    <div class="overflow-hidden rounded-3xl shadow-2xl border-4 border-white dark:border-slate-800">
                        <img src="{{ asset('storage/' . (str_contains($data->image_path, 'img/') ? $data->image_path : 'img/'.$data->image_path)) }}"
                             alt="{{ $data->name }}" class="w-full aspect-[3/4] object-cover">
                    </div>
                    <div class="mt-6 p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                        <h2 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4">{{ __('messages.core_info') }}</h2>
                        <ul class="space-y-4">
                            <li class="flex flex-col">
                                <span class="text-[10px] font-bold text-primary uppercase">{{ __('messages.category') }}</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">{{ __('messages.' . $data->category) }}</span>
                            </li>
                            <li class="flex flex-col">
                                <span class="text-[10px] font-bold text-primary uppercase">{{ __('messages.hometown') }}</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">{{ $data->hometown }}</span>
                            </li>
                            <li class="flex flex-col">
                                <span class="text-[10px] font-bold text-primary uppercase">{{ __('messages.lifespan') }}</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">
                                    {{ \Carbon\Carbon::parse($data->birth_date)->format('Y') }} —
                                    {{ $data->death_date ? \Carbon\Carbon::parse($data->death_date)->format('Y') : __('messages.present') }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 space-y-8">
                <div>
                    <span class="inline-block px-4 py-1 rounded-full bg-primary/10 text-primary text-xs font-black uppercase mb-4 tracking-tighter">
                        {{ __('messages.hero_archive') }}
                    </span>
                    <h1 class="text-5xl lg:text-6xl font-black text-slate-900 dark:text-white leading-tight">{{ $data->name }}</h1>
                </div>

                @if($data->quotes)
                <div class="relative p-8 rounded-3xl bg-primary text-white overflow-hidden shadow-xl shadow-primary/20">
                    <span class="material-symbols-outlined absolute -right-4 -top-4 text-9xl opacity-20 rotate-12">format_quote</span>
                    <p class="relative z-10 text-xl italic font-medium leading-relaxed">"{{ $data->quotes }}"</p>
                </div>
                @endif

                <div class="prose prose-slate dark:prose-invert max-w-none">
                    <div class="text-slate-600 dark:text-slate-300 leading-relaxed text-lg text-justify">
                        {!! nl2br($locale == 'id' ? $data->bio_id : ($data->bio_en ?? $data->bio_id)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
