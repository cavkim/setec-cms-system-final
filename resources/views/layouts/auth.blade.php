<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Authentication')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-md rounded-xl border border-slate-800 bg-slate-900 p-6 shadow-xl">
            @yield('content')
        </div>
    </div>
</body>
</html>
