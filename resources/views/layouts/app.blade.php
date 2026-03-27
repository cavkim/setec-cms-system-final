<!DOCTYPE html>
<html lang="en" class="h-full w-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BuildScape CMS')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'secondary-fixed-dim': '#ffb95f',
                        'surface-dim': '#0b1326',
                        'surface-container-low': '#131b2e',
                        'on-tertiary-fixed-variant': '#39485a',
                        'surface-container-high': '#222a3d',
                        'primary': '#adc6ff',
                        'surface-tint': '#adc6ff',
                        'on-surface-variant': '#c2c6d6',
                        'on-error-container': '#ffdad6',
                        'on-surface': '#dae2fd',
                        'primary-fixed': '#d8e2ff',
                        'tertiary-fixed': '#d4e4fa',
                        'error-container': '#93000a',
                        'surface': '#0b1326',
                        'on-tertiary-container': '#1c2b3c',
                        'outline': '#8c909f',
                        'on-tertiary-fixed': '#0d1c2d',
                        'inverse-primary': '#005ac2',
                        'on-secondary-fixed': '#2a1700',
                        'secondary-fixed': '#ffddb8',
                        'surface-container': '#171f33',
                        'on-primary-fixed': '#001a42',
                        'on-error': '#690005',
                        'surface-container-lowest': '#060e20',
                        'tertiary-fixed-dim': '#b9c8de',
                        'on-tertiary': '#233143',
                        'secondary': '#ffb95f',
                        'tertiary': '#b9c8de',
                        'on-primary-fixed-variant': '#004395',
                        'primary-fixed-dim': '#adc6ff',
                        'surface-variant': '#2d3449',
                        'background': '#0b1326',
                        'primary-container': '#4d8eff',
                        'inverse-surface': '#dae2fd',
                        'on-primary-container': '#00285d',
                        'on-secondary-container': '#5b3800',
                        'on-secondary-fixed-variant': '#653e00',
                        'inverse-on-surface': '#283044',
                        'on-primary': '#002e6a',
                        'on-background': '#dae2fd',
                        'tertiary-container': '#8392a6',
                        'surface-container-highest': '#2d3449',
                        'secondary-container': '#ee9800',
                        'outline-variant': '#424754',
                        'surface-bright': '#31394d',
                        'error': '#ffb4ab',
                        'on-secondary': '#472a00'
                    },
                    fontFamily: {
                        headline: ['Manrope', 'ui-sans-serif', 'system-ui'],
                        body: ['Inter', 'ui-sans-serif', 'system-ui'],
                        label: ['Inter', 'ui-sans-serif', 'system-ui']
                    }
                }
            }
        };
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.css">
    <script src="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.umd.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/buildscape/layout.css') }}" />
    @yield('styles')
</head>

<body class="h-screen w-screen overflow-hidden bg-[#0b1326] text-[#dae2fd] antialiased flex flex-row">

    @if (request()->routeIs('dashboard') || request()->routeIs('projects.*') || request()->routeIs('tasks.*') || request()->routeIs('team.*') || request()->routeIs('budget.*') || request()->routeIs('documents.*') || request()->routeIs('safety.*') || request()->routeIs('reports.*') || request()->routeIs('audit.*') || request()->routeIs('audit-logs.*'))
        @include('components.dashboard-sidebar')
        <main class="ml-64 w-[calc(100%-16rem)] h-screen overflow-y-auto p-10 flex flex-col gap-8 bg-[#0b1326]">
            @include('components.dashboard-topbar')
            @yield('content')
        </main>
    @else
        @include('components.sidebar')
        <div class="main">
            @include('components.topbar')
            <div class="content">
                @yield('content')
            </div>
        </div>
    @endif

    <div class="toastwrap" id="toastwrap"></div>

    <div class="overlay" id="overlay" onclick="if(event.target===this)closeModal()">
        <div class="modal">
            <div class="mh">
                <div class="mht" id="mtitle"></div>
                <div class="mcl" onclick="closeModal()">×</div>
            </div>
            <div class="mc" id="mbody"></div>
            <div class="mf" id="mfoot"></div>
        </div>
    </div>

    <script src="{{ asset('js/buildscape/layout.js') }}"></script>
    @yield('scripts')
</body>

</html>