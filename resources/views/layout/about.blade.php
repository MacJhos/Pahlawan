@extends('app')

@section('content')
    <section class="relative h-64 md:h-80 w-full overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 hover:scale-105"
             style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('{{ asset('images/hero-about.jpg') }}');">
        </div>
        <div class="relative h-full flex flex-col items-center justify-center text-center px-4">
            <h1 class="text-accent-gold text-4xl md:text-5xl font-bold uppercase tracking-[0.2em]">About Us</h1>
            <div class="w-20 h-1 bg-accent-gold mt-6 rounded-full"></div>
        </div>
    </section>

    <div class="max-w-[1440px] mx-auto px-6 py-16 md:py-24">

        <section class="text-center mb-24">
            <h2 class="text-primary text-3xl font-bold mb-8">Our Mission</h2>
            <div class="bg-white dark:bg-slate-800/40 p-10 md:p-16 rounded-2xl shadow-sm border border-primary/5 backdrop-blur-sm">
                <blockquote class="text-lg md:text-2xl leading-relaxed text-slate-700 dark:text-slate-300 italic font-light">
                    "Preserving and honoring the rich history of Indonesian National Heroes. Our digital museum serves as a sacred repository for the stories of bravery, sacrifice, and the unwavering spirit of independence."
                </blockquote>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div class="space-y-6">
                <h2 class="text-primary text-2xl font-bold tracking-tight">Who We Are</h2>
                <div class="space-y-4 text-slate-600 dark:text-slate-400 leading-relaxed">
                    <p>
                        Digital Legacy is a non-profit initiative dedicated to digitizing the narratives of the men and women who fought for Indonesia's freedom. We believe that history should be accessible to all.
                    </p>
                    <p>
                        Our team of historians and digital archivists work tirelessly to curate verified historical accounts, rare photographs, and interactive timelines to bring the past to life.
                    </p>
                </div>
            </div>

            <div class="relative group">
                <div class="absolute -inset-4 bg-accent-gold/10 rounded-2xl blur-xl group-hover:bg-accent-gold/20 transition-all duration-500"></div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 shadow-2xl">
                    <img src="{{ asset('images/team-working.jpg') }}"
                         alt="Digital Archivists at work"
                         class="w-full h-80 object-cover transition-transform duration-500 group-hover:scale-105"/>
                </div>
            </div>
        </section>
    </div>
@endsection
