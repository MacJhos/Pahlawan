@extends('app')

@section('content')
<div class="py-12 bg-slate-50 dark:bg-slate-950">
    <div class="mx-auto max-w-[1440px] px-6">
        <a href="{{ route('galeri') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary mb-8 group">
            <span class="material-symbols-outlined">arrow_back</span> {{ __('messages.back_to_gallery') }}
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <div class="lg:col-span-7 space-y-8">
                <div>
                    <h1 class="text-5xl font-black text-slate-900 dark:text-white">{{ $data->name }}</h1>
                    <p class="mt-4 flex items-center gap-2 text-primary font-bold">
                        <span class="material-symbols-outlined">location_on</span> {{ $data->location }}
                    </p>
                </div>

                <div class="aspect-video rounded-3xl overflow-hidden shadow-2xl border-8 border-white dark:border-slate-900">
                    <img src="{{ asset('storage/' . $data->image_path) }}" class="w-full h-full object-cover">
                </div>

                <div class="prose prose-slate dark:prose-invert max-w-none text-lg leading-relaxed">
                    {!! app()->getLocale() == 'id' ? $data->description_id : ($data->description_en ?? $data->description_id) !!}
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="sticky top-24 space-y-6">
                    <h3 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                        <span class="size-2 bg-primary rounded-full"></span> {{ __('messages.location_map') }}
                    </h3>
                    <div class="h-[400px] rounded-3xl overflow-hidden shadow-xl border border-slate-200 dark:border-slate-800">
                        @if($data->coordinate)
                            <iframe width="100%" height="100%" frameborder="0" style="border:0"
                                    src="https://maps.google.com/maps?q={{ $data->coordinate }}&t=&z=15&ie=UTF8&iwloc=&output=embed"></iframe>
                        @else
                            <div class="flex items-center justify-center h-full bg-slate-100 dark:bg-slate-900 text-slate-400 italic">
                                {{ __('messages.map_not_available') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
