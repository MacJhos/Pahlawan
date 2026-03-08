@extends('app')

@section('content')
<div class="py-12 bg-slate-50 dark:bg-slate-950">
    <div class="mx-auto max-w-[1440px] px-6 text-center lg:text-left">
        <a href="{{ route('galeri') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary mb-8 group">
            <span class="material-symbols-outlined">arrow_back</span> {{ __('messages.back_to_gallery') }}
        </a>

        <div class="flex flex-col lg:flex-row gap-16 items-start">
            <div class="w-full lg:w-1/3">
                <div class="sticky top-24">
                    <div class="p-4 bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-slate-800">
                        <img src="{{ asset('storage/' . $data->image_path) }}" class="w-full aspect-square object-contain rounded-[2rem]">
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-2/3 space-y-10">
                <div>
                    <h1 class="text-5xl lg:text-7xl font-black text-slate-900 dark:text-white leading-tight">{{ $data->name }}</h1>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] font-black text-primary uppercase tracking-widest block mb-1">Origin</span>
                        <span class="text-lg font-bold text-slate-800 dark:text-white">{{ $data->origin }}</span>
                    </div>
                    <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] font-black text-primary uppercase tracking-widest block mb-1">Material</span>
                        <span class="text-lg font-bold text-slate-800 dark:text-white">{{ $data->material }}</span>
                    </div>
                    <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] font-black text-primary uppercase tracking-widest block mb-1">Estimated Age</span>
                        <span class="text-lg font-bold text-slate-800 dark:text-white">{{ $data->estimated_age }}</span>
                    </div>
                </div>

                <div class="prose prose-slate dark:prose-invert max-w-none text-xl leading-relaxed text-justify">
                    {!! app()->getLocale() == 'id' ? $data->description_id : ($data->description_en ?? $data->description_id) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
