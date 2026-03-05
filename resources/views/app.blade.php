<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layout.__header')
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 min-h-screen flex flex-col">

    @include('layout.__navbar')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('layout.__footer')

    <script src="{{ asset('js/tailwind-config.js') }}?v={{ time() }}"></script>
    @livewire('hero-chatbot')

    @livewireScripts
</body>
</html>
