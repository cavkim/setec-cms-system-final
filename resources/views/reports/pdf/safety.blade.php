<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a1a2e
        }

        .header {
            background: #0D1B2A;
            color: #fff;
            padding: 20px 28px;
            margin-bottom: 20px
        }

        .header h1 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px
        }

        .header p {
            font-size: 11px;
            opacity: .6
        }

        table {
            width: calc(100% - 56px);
            margin: 0 28px;
            border-collapse: collapse
        }

        th {
            background: #c62828;
            color: #fff;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .05em;
            padding: 8px 10px;
            text-align: left
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            font-size: 10px
        }

        tr:nth-child(even) td {
            background: #f8f9fa
        }

        .badge {
            padding: 2px 7px;
            border-radius: 6px;
            font-size: 9px;
            font-weight: 700
        }

        .critical {
            background: #ffebee;
            color: #c62828
        }

        .high {
            background: #fce4ec;
            color: #ad1457
        }

        .medium {
            background: #fff3e0;
            color: #e65100
        }

        .low {
            background: #e8f5e9;
            color: #2e7d32
        }

        .open {
            background: #ffebee;
            color: #c62828
        }

        .investigating {
            background: #fff3e0;
            color: #e65100
        }

        .resolved {
            background: #e8f5e9;
            color: #2e7d32
        }

        .closed {
            background: #eceff1;
            color: #546e7a
        }

        .footer {
            margin: 20px 28px 0;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>BuildScape CMS — Safety & Incident Report</h1>
        <p>Generated: {{ now()->format('F d, Y — H:i') }} · Total incidents: {{ $incidents->count() }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Location</th>
                <th>Severity</th>
                <th>Status</th>
                <th>Reported By</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incidents as $inc)
                <tr>
                    <td>{{ \Illuminate\Support\Str::limit($inc->description, 55) }}</td>
                    <td>{{ $inc->location ?? '—' }}</td>
                    <td><span class="badge {{ $inc->severity }}">{{ strtoupper($inc->severity) }}</span></td>
                    <td><span class="badge {{ $inc->status }}">{{ strtoupper($inc->status) }}</span></td>
                    <td>{{ $inc->reporter ?? '—' }}</td>
                    <td>{{ \Carbon\Carbon::parse($inc->incident_date)->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">BuildScape Construction Management System · Confidential · {{ now()->format('Y') }}</div>
</body>

</html>
<!DOCTYPE html>

<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>BuildScape CMS - Dashboard</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;family=Inter:wght@400;500;600&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "secondary-container": "#2c96e5",
                        "surface-container-low": "#0f1c2c",
                        "inverse-on-surface": "#243141",
                        "on-tertiary-fixed-variant": "#005048",
                        "error": "#ffb4ab",
                        "on-secondary": "#003355",
                        "on-tertiary-container": "#8ff7e6",
                        "secondary": "#99cbff",
                        "on-secondary-fixed": "#001d34",
                        "inverse-primary": "#005db7",
                        "on-background": "#d6e4f9",
                        "primary": "#a9c7ff",
                        "surface-dim": "#061423",
                        "on-primary": "#003063",
                        "tertiary-fixed-dim": "#70d8c8",
                        "primary-fixed-dim": "#a9c7ff",
                        "surface-container": "#132030",
                        "on-primary-fixed": "#001b3d",
                        "tertiary-fixed": "#8df5e4",
                        "on-error-container": "#ffdad6",
                        "outline-variant": "#424752",
                        "on-primary-container": "#dae5ff",
                        "on-surface-variant": "#c2c6d4",
                        "tertiary": "#70d8c8",
                        "on-tertiary-fixed": "#00201c",
                        "tertiary-container": "#007367",
                        "primary-container": "#1565c0",
                        "secondary-fixed": "#cfe5ff",
                        "on-surface": "#d6e4f9",
                        "on-error": "#690005",
                        "error-container": "#93000a",
                        "surface-container-lowest": "#020f1e",
                        "inverse-surface": "#d6e4f9",
                        "on-tertiary": "#003731",
                        "secondary-fixed-dim": "#99cbff",
                        "primary-fixed": "#d6e3ff",
                        "surface-container-high": "#1e2b3b",
                        "surface-variant": "#283646",
                        "surface-container-highest": "#283646",
                        "background": "#061423",
                        "on-secondary-fixed-variant": "#004a78",
                        "on-primary-fixed-variant": "#00468c",
                        "on-secondary-container": "#002c4a",
                        "outline": "#8c919d",
                        "surface": "#061423",
                        "surface-bright": "#2d3a4a",
                        "surface-tint": "#a9c7ff"
                    },
                    fontFamily: {
                        "headline": ["Manrope", "sans-serif"],
                        "body": ["Inter", "sans-serif"],
                        "label": ["Inter", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "2xl": "13px", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #283646;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-surface text-on-surface font-body selection:bg-primary/30 antialiased overflow-x-hidden">
    <!-- SideNavBar -->
    <aside class="fixed left-0 top-0 h-full w-[210px] z-50 bg-[#112233] flex flex-col py-6 shadow-2xl shadow-black/40">
        <div class="px-6 mb-8 flex items-center gap-3">
            <div
                class="w-8 h-8 rounded bg-gradient-to-br from-primary-container to-secondary-container flex items-center justify-center text-white">
                <span class="material-symbols-outlined text-lg">architecture</span>
            </div>
            <div>
                <h1 class="text-xl font-bold text-white tracking-tight leading-tight">BuildScape</h1>
                <p class="text-[9px] uppercase tracking-wider text-on-surface-variant font-medium">CONSTRUCTION PRO</p>
            </div>
        </div>
        <nav class="flex-1 flex flex-col gap-1 px-2">
            <!-- Active Navigation -->
            <a class="text-[#42A5F5] bg-[#1565C0]/30 border-l-4 border-[#1565C0] font-bold px-4 py-3 flex items-center gap-3 scale-[0.98] transition-transform"
                href="#">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="text-[13px]">Overview</span>
            </a>
            <a class="text-[#8BAABF] hover:bg-[#0f1c2c] px-4 py-3 flex items-center gap-3 transition-colors" href="#">
                <span class="material-symbols-outlined">architecture</span>
                <span class="text-[13px]">Projects</span>
            </a>
            <a class="text-[#8BAABF] hover:bg-[#0f1c2c] px-4 py-3 flex items-center gap-3 transition-colors" href="#">
                <span class="material-symbols-outlined">payments</span>
                <span class="text-[13px]">Finance</span>
            </a>
            <a class="text-[#8BAABF] hover:bg-[#0f1c2c] px-4 py-3 flex items-center gap-3 transition-colors" href="#">
                <span class="material-symbols-outlined">engineering</span>
                <span class="text-[13px]">Operations</span>
            </a>
            <a class="text-[#8BAABF] hover:bg-[#0f1c2c] px-4 py-3 flex items-center gap-3 transition-colors" href="#">
                <span class="material-symbols-outlined">assignment</span>
                <span class="text-[13px]">Tasks</span>
            </a>
            <a class="text-[#8BAABF] hover:bg-[#0f1c2c] px-4 py-3 flex items-center gap-3 transition-colors" href="#">
                <span class="material-symbols-outlined">security</span>
                <span class="text-[13px]">Safety</span>
            </a>
        </nav>
        <div class="mt-auto px-2 flex flex-col gap-1">
            <a class="text-[#8BAABF] hover:bg-[#0f1c2c] px-4 py-3 flex items-center gap-3 transition-colors" href="#">
                <span class="material-symbols-outlined">settings</span>
                <span class="text-[13px]">Settings</span>
            </a>
            <a class="text-[#8BAABF] hover:bg-[#0f1c2c] px-4 py-3 flex items-center gap-3 transition-colors" href="#">
                <span class="material-symbols-outlined">help</span>
                <span class="text-[13px]">Support</span>
            </a>
            <div class="mt-6 mx-4 p-3 glass-card rounded-xl flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-tr from-primary-container to-secondary-container p-0.5">
                    <img alt="User Avatar" class="w-full h-full rounded-full object-cover"
                        data-alt="Portrait of Alex Rivera Project Director"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCrM2tXFUBIm1L17rvzwYmD0_YnkvPbEfp3JIZqMhl6bzAZkmrGJR0V0sfBQkl5pMLtFY55Be4Nh_VwUoFZZocfuiLAvmEEQDZfbhaxrNjbd_3uR08f9DPjLGM297WLGrjbg2tcls1UW_ZYSazU2a4jWt3h5CfqIto8FxC5mGs1szbKuus0Zp5xEbUWP0LL0JkfEpC2rmrTmaNKncqvUZdbbSjTKQLKQ82SqGPF5ffAPf2PajoxwQVYaAskIkjt1U8FuqAZkwSzQG8" />
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-white truncate">Alex Rivera</p>
                    <p class="text-[10px] text-on-surface-variant truncate">Project Director</p>
                </div>
            </div>
        </div>
    </aside>
    <!-- TopAppBar -->
    <header
        class="fixed top-0 right-0 left-[210px] h-16 z-40 bg-[#0D1B2A] flex justify-between items-center px-8 w-full border-b border-white/5">
        <div class="flex items-center gap-8">
            <h2 class="text-lg font-bold text-[#E8EEF4] font-headline">Dashboard</h2>
            <div class="flex items-center gap-6">
                <a class="text-[#1565C0] border-b-2 border-[#1565C0] pb-1 text-[13px] font-medium"
                    href="#">Dashboard</a>
                <a class="text-[#8BAABF] hover:text-white transition-colors text-[13px] font-medium"
                    href="#">Analytics</a>
                <a class="text-[#8BAABF] hover:text-white transition-colors text-[13px] font-medium"
                    href="#">Reports</a>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="flex items-center bg-surface-container-low px-4 py-1.5 rounded-full">
                <span class="material-symbols-outlined text-[18px] text-on-surface-variant mr-2">search</span>
                <input
                    class="bg-transparent border-none text-[13px] focus:ring-0 text-on-surface placeholder:text-on-surface-variant w-48"
                    placeholder="Search projects..." type="text" />
            </div>
            <div class="flex items-center gap-4">
                <div
                    class="bg-primary-container/20 text-primary-container px-3 py-1 rounded-full text-[11px] font-bold tracking-wider">
                    Q1 2026</div>
                <button class="relative text-on-surface-variant hover:text-white transition-colors">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-error rounded-full border-2 border-[#0D1B2A]"></span>
                </button>
                <button class="text-on-surface-variant hover:text-white transition-colors">
                    <span class="material-symbols-outlined">settings</span>
                </button>
            </div>
        </div>
    </header>
    <!-- Main Content -->
    <main class="ml-[210px] mt-16 p-8 min-h-screen">
        <!-- Header Section -->
        <div class="mb-8 flex justify-between items-end">
            <div>
                <p class="text-on-surface-variant text-[13px] mb-1">Wednesday, Jan 14, 2026</p>
                <h3 class="text-3xl font-extrabold font-headline tracking-tight">Project Overview</h3>
            </div>
            <button
                class="bg-gradient-to-r from-primary-container to-secondary-container px-6 py-2.5 rounded-lg text-white font-bold text-sm shadow-lg shadow-primary-container/20 flex items-center gap-2 hover:scale-[1.02] transition-transform">
                <span class="material-symbols-outlined text-[18px]">add</span>
                New Project
            </button>
        </div>
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Active Projects -->
            <div
                class="glass-card p-5 rounded-2xl border-t-2 border-[#1565C0] hover:-translate-y-0.5 transition-transform cursor-default">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-[11px] font-bold text-[#8BAABF] uppercase tracking-wider">Active Projects</span>
                    <span class="material-symbols-outlined text-[#1565C0]">architecture</span>
                </div>
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl font-extrabold font-headline">12</span>
                    <span class="text-[11px] text-tertiary font-medium flex items-center">+2 this month</span>
                </div>
            </div>
            <!-- Tasks Today -->
            <div
                class="glass-card p-5 rounded-2xl border-t-2 border-[#00897B] hover:-translate-y-0.5 transition-transform cursor-default">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-[11px] font-bold text-[#8BAABF] uppercase tracking-wider">Tasks Today</span>
                    <span class="material-symbols-outlined text-[#00897B]">task_alt</span>
                </div>
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl font-extrabold font-headline">24</span>
                    <span class="text-[11px] text-error font-medium flex items-center">-4 pending</span>
                </div>
            </div>
            <!-- Weekly Spend -->
            <div
                class="glass-card p-5 rounded-2xl border-t-2 border-[#F57C00] hover:-translate-y-0.5 transition-transform cursor-default">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-[11px] font-bold text-[#8BAABF] uppercase tracking-wider">Weekly Spend</span>
                    <span class="material-symbols-outlined text-[#F57C00]">payments</span>
                </div>
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl font-extrabold font-headline">$48.2k</span>
                    <span class="text-[11px] text-tertiary font-medium flex items-center">+8% week</span>
                </div>
            </div>
            <!-- Active Workers -->
            <div
                class="glass-card p-5 rounded-2xl border-t-2 border-[#C62828] hover:-translate-y-0.5 transition-transform cursor-default">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-[11px] font-bold text-[#8BAABF] uppercase tracking-wider">Active Workers</span>
                    <span class="material-symbols-outlined text-[#C62828]">groups</span>
                </div>
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl font-extrabold font-headline">142</span>
                    <span class="text-[11px] text-on-surface-variant font-medium flex items-center">Stable</span>
                </div>
            </div>
        </div>
        <!-- Alert Banners -->
        <div class="space-y-3 mb-8">
            <div class="bg-error-container/20 border-l-4 border-error p-4 rounded-r-xl flex items-center gap-4 group">
                <span class="w-3 h-3 rounded-full bg-error animate-pulse"></span>
                <div class="flex-1">
                    <span class="text-xs font-bold text-error uppercase mr-2">Critical Budget</span>
                    <span class="text-sm font-medium text-on-background">Budget overage on Skyline Tower (91%)</span>
                </div>
                <button class="opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined text-on-surface-variant text-[18px]">close</span>
                </button>
            </div>
            <div
                class="bg-tertiary-container/10 border-l-4 border-[#F57C00] p-4 rounded-r-xl flex items-center gap-4 group">
                <span class="w-3 h-3 rounded-full bg-[#F57C00]"></span>
                <div class="flex-1">
                    <span class="text-xs font-bold text-[#F57C00] uppercase mr-2">Deadline Warning</span>
                    <span class="text-sm font-medium text-on-background">Grand Central permit expires in 3 days</span>
                </div>
                <button class="opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined text-on-surface-variant text-[18px]">close</span>
                </button>
            </div>
            <div
                class="bg-primary-container/10 border-l-4 border-primary-container p-4 rounded-r-xl flex items-center gap-4 group">
                <span class="w-3 h-3 rounded-full bg-primary-container"></span>
                <div class="flex-1">
                    <span class="text-xs font-bold text-primary-container uppercase mr-2">Information</span>
                    <span class="text-sm font-medium text-on-background">Safety inspection scheduled for tomorrow at 9
                        AM</span>
                </div>
                <button class="opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined text-on-surface-variant text-[18px]">close</span>
                </button>
            </div>
        </div>
        <!-- Middle Row: Projects & Safety -->
        <div class="grid grid-cols-10 gap-8 mb-8">
            <!-- Active Projects List (60%) -->
            <div class="col-span-10 lg:col-span-6 glass-card rounded-2xl overflow-hidden flex flex-col">
                <div class="p-6 pb-2 flex justify-between items-center">
                    <h4 class="font-bold text-lg font-headline">Active Projects</h4>
                    <button class="text-primary-container text-[12px] font-bold hover:underline">View All</button>
                </div>
                <div class="p-6 pt-0 overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider border-b border-white/5">
                                <th class="pb-3 pt-4">Project Name</th>
                                <th class="pb-3 pt-4">Location</th>
                                <th class="pb-3 pt-4">Progress</th>
                                <th class="pb-3 pt-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr class="hover:bg-white/5 transition-colors cursor-pointer group">
                                <td class="py-4 flex items-center gap-3">
                                    <span class="w-2 h-2 rounded-full bg-error"></span>
                                    <span class="text-[13px] font-semibold">Skyline Tower</span>
                                </td>
                                <td class="py-4 text-[13px] text-on-surface-variant">Austin, TX</td>
                                <td class="py-4 w-48">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-1.5 bg-surface-container rounded-full overflow-hidden">
                                            <div class="h-full bg-error rounded-full" style="width: 91%"></div>
                                        </div>
                                        <span class="text-[12px] font-medium">91%</span>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <span
                                        class="px-2 py-0.5 rounded bg-error-container/20 text-error text-[10px] font-bold uppercase">At
                                        Risk</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-white/5 transition-colors cursor-pointer group">
                                <td class="py-4 flex items-center gap-3">
                                    <span class="w-2 h-2 rounded-full bg-tertiary"></span>
                                    <span class="text-[13px] font-semibold">Grand Central</span>
                                </td>
                                <td class="py-4 text-[13px] text-on-surface-variant">New York, NY</td>
                                <td class="py-4 w-48">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-1.5 bg-surface-container rounded-full overflow-hidden">
                                            <div class="h-full bg-tertiary rounded-full" style="width: 45%"></div>
                                        </div>
                                        <span class="text-[12px] font-medium">45%</span>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <span
                                        class="px-2 py-0.5 rounded bg-tertiary-container/20 text-tertiary text-[10px] font-bold uppercase">On
                                        Track</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-white/5 transition-colors cursor-pointer group">
                                <td class="py-4 flex items-center gap-3">
                                    <span class="w-2 h-2 rounded-full bg-secondary-container"></span>
                                    <span class="text-[13px] font-semibold">Harbor Bridge</span>
                                </td>
                                <td class="py-4 text-[13px] text-on-surface-variant">Miami, FL</td>
                                <td class="py-4 w-48">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-1.5 bg-surface-container rounded-full overflow-hidden">
                                            <div class="h-full bg-secondary-container rounded-full" style="width: 12%">
                                            </div>
                                        </div>
                                        <span class="text-[12px] font-medium">12%</span>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <span
                                        class="px-2 py-0.5 rounded bg-secondary-container/20 text-secondary-container text-[10px] font-bold uppercase">Planning</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Safety Overview (40%) -->
            <div class="col-span-10 lg:col-span-4 glass-card rounded-2xl p-6 flex flex-col">
                <div class="flex justify-between items-start mb-6">
                    <h4 class="font-bold text-lg font-headline">Safety Overview</h4>
                    <span class="material-symbols-outlined text-tertiary"
                        style="font-variation-settings: 'FILL' 1;">security</span>
                </div>
                <div class="flex flex-col items-center justify-center mb-8">
                    <div class="relative w-32 h-32 flex items-center justify-center">
                        <svg class="absolute w-full h-full -rotate-90">
                            <circle class="text-surface-container-highest" cx="64" cy="64" fill="transparent" r="58"
                                stroke="currentColor" stroke-width="8"></circle>
                            <circle class="text-tertiary" cx="64" cy="64" fill="transparent" r="58"
                                stroke="currentColor" stroke-dasharray="364" stroke-dashoffset="60" stroke-width="8">
                            </circle>
                        </svg>
                        <div class="text-center z-10">
                            <span class="text-4xl font-extrabold font-headline block">12</span>
                            <span class="text-[10px] text-on-surface-variant uppercase font-bold tracking-tight">Days
                                Safe</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 mb-6">
                    <div class="bg-surface-container-lowest p-3 rounded-xl text-center">
                        <span class="block text-lg font-bold">0</span>
                        <span class="text-[9px] text-on-surface-variant uppercase font-bold">Open</span>
                    </div>
                    <div class="bg-surface-container-lowest p-3 rounded-xl text-center">
                        <span class="block text-lg font-bold">2</span>
                        <span class="text-[9px] text-on-surface-variant uppercase font-bold">Investig.</span>
                    </div>
                    <div class="bg-surface-container-lowest p-3 rounded-xl text-center">
                        <span class="block text-lg font-bold">142</span>
                        <span class="text-[9px] text-on-surface-variant uppercase font-bold">Resolved</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-2">Recent
                        Incidents</p>
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-error"></span>
                        <div class="flex-1">
                            <p class="text-[13px] font-medium">Minor trip - Floor 4</p>
                            <p class="text-[11px] text-on-surface-variant">Skyline Tower • 2h ago</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-[#F57C00]"></span>
                        <div class="flex-1">
                            <p class="text-[13px] font-medium">Equipment malfunction</p>
                            <p class="text-[11px] text-on-surface-variant">Grand Central • yesterday</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Three Column Row: Tasks, Team, Activity -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <!-- Pending Tasks -->
            <div class="glass-card rounded-2xl p-6 h-[400px] flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="font-bold text-lg font-headline">Pending Tasks</h4>
                    <span class="material-symbols-outlined text-on-surface-variant">checklist</span>
                </div>
                <div class="flex-1 overflow-y-auto custom-scrollbar space-y-4 pr-2">
                    <div class="flex items-start gap-3 p-3 hover:bg-white/5 rounded-xl transition-colors">
                        <input
                            class="mt-1 rounded border-outline-variant bg-surface-container text-primary-container focus:ring-0"
                            type="checkbox" />
                        <div class="flex-1">
                            <p class="text-[13px] font-semibold mb-1">Structural Review</p>
                            <p class="text-[11px] text-on-surface-variant mb-2">Skyline Tower</p>
                            <div class="flex justify-between items-center">
                                <span
                                    class="px-2 py-0.5 rounded bg-error/10 text-error text-[9px] font-bold uppercase">High</span>
                                <span class="text-[10px] text-on-surface-variant">Jan 15</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 hover:bg-white/5 rounded-xl transition-colors">
                        <input
                            class="mt-1 rounded border-outline-variant bg-surface-container text-primary-container focus:ring-0"
                            type="checkbox" />
                        <div class="flex-1">
                            <p class="text-[13px] font-semibold mb-1">Permit Application</p>
                            <p class="text-[11px] text-on-surface-variant mb-2">Grand Central</p>
                            <div class="flex justify-between items-center">
                                <span
                                    class="px-2 py-0.5 rounded bg-[#F57C00]/10 text-[#F57C00] text-[9px] font-bold uppercase">Med</span>
                                <span class="text-[10px] text-on-surface-variant">Jan 17</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 hover:bg-white/5 rounded-xl transition-colors">
                        <input
                            class="mt-1 rounded border-outline-variant bg-surface-container text-primary-container focus:ring-0"
                            type="checkbox" />
                        <div class="flex-1">
                            <p class="text-[13px] font-semibold mb-1">Material Delivery</p>
                            <p class="text-[11px] text-on-surface-variant mb-2">Harbor Bridge</p>
                            <div class="flex justify-between items-center">
                                <span
                                    class="px-2 py-0.5 rounded bg-tertiary/10 text-tertiary text-[9px] font-bold uppercase">Low</span>
                                <span class="text-[10px] text-on-surface-variant">Jan 18</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Team Workload -->
            <div class="glass-card rounded-2xl p-6 h-[400px] flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="font-bold text-lg font-headline">Team Workload</h4>
                    <span class="material-symbols-outlined text-on-surface-variant">group_work</span>
                </div>
                <div class="flex-1 space-y-6">
                    <div class="flex items-center gap-3">
                        <img alt="Sarah J" class="w-10 h-10 rounded-full object-cover"
                            data-alt="Portrait of Sarah J Site Engineer"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBA73C3eNgEU9D48N7A2IHUoCl6U5EbMYHyswKFBPbg50byG1abw9NvOLnOXU6fiSt5WZirMj500ZiXcn4A7Ifg-Im_Lzip11Ob-umRJpM3l0Hlnxpa5iJM5nU5ygqqgIPlP_3C_a-ET50wtyH2q6FtRaEpT1WLgvR-o1ms8fKkd60aUN3cDjoOrxuoXVAe_j_Iq7QKywnsPwp4li4-9Q_Oouqwgk0I0uP2L2TYcxlUMepk4ND17zQ_WvTaziFl940MXebdj7xZ3CA" />
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-1">
                                <p class="text-[13px] font-semibold">Sarah Jenkins</p>
                                <span class="text-[11px] font-bold text-primary-container">12 Tasks</span>
                            </div>
                            <div class="h-1.5 bg-surface-container rounded-full overflow-hidden">
                                <div class="h-full bg-primary-container rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <img alt="Michael L" class="w-10 h-10 rounded-full object-cover"
                            data-alt="Portrait of Michael L Foreman"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDhgtwwDxWBk536jaG1lxkZqKRruVVDhqUIR5ASnS6rDiMTKySGa8N6LgUXCOUp9Tbmfy4P9qhljUFqq_rvcvRjN0qB63tkiUSjCVmo9dzE3GAtwIVDMaF7ZeMRrMDkuWtCmd24maLKZb1QUqt94fmgk1qz528_Iw4PIi0UYJNTZSVoXeziBtVgnTERHGNWaQI76J_UEcnMMSoX2rmYG9qnDVCBJH2tSeA_ak1Rgh6ywJ6eoxCw2mvvFDKVtbVOBSNG5049YB_xVmY" />
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-1">
                                <p class="text-[13px] font-semibold">Michael Lang</p>
                                <span class="text-[11px] font-bold text-primary-container">8 Tasks</span>
                            </div>
                            <div class="h-1.5 bg-surface-container rounded-full overflow-hidden">
                                <div class="h-full bg-primary-container rounded-full" style="width: 42%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <img alt="Elena R" class="w-10 h-10 rounded-full object-cover"
                            data-alt="Portrait of Elena R Architect"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDE9j7PEYKLBBJbIy2V1mIzJ2IkLAaQDu4dm780oPLrt0iLwD3fWVBBE-KKF6beyQn-ETUmLG2dCHkuylml7ZxkpY_tWAt52iy3QDKOdtxjqHPXYkFmSG69U7QKdddxb0uWkW573PpzsmLQxQg0gj5NFi5649QNJC29_4k1ls4bYdCAjVlqzw2oFToP_O96vdg0c87t5Gg_k2oqgAHNb3WbFbJaU57qdp4it5nAWRVScPg-2XoYtSzzvt4_lXOvL7pwTPuVlkd2UDY" />
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-1">
                                <p class="text-[13px] font-semibold">Elena Rodriguez</p>
                                <span class="text-[11px] font-bold text-primary-container">15 Tasks</span>
                            </div>
                            <div class="h-1.5 bg-surface-container rounded-full overflow-hidden">
                                <div class="h-full bg-primary-container rounded-full" style="width: 95%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Recent Activity -->
            <div class="glass-card rounded-2xl p-6 h-[400px] flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="font-bold text-lg font-headline">Recent Activity</h4>
                    <span class="material-symbols-outlined text-on-surface-variant">history</span>
                </div>
                <div
                    class="flex-1 space-y-6 relative before:absolute before:left-[7px] before:top-2 before:bottom-2 before:w-[1px] before:bg-white/10">
                    <div class="flex items-start gap-4 relative z-10">
                        <div class="w-3.5 h-3.5 rounded-full bg-tertiary border-4 border-[#0D1B2A] mt-1"></div>
                        <div class="flex-1">
                            <p class="text-[12px] leading-tight mb-1"><span class="font-bold text-white">Alex
                                    Rivera</span> approved structural blueprints for Skyline Tower.</p>
                            <p class="text-[11px] text-on-surface-variant">12 mins ago</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 relative z-10">
                        <div class="w-3.5 h-3.5 rounded-full bg-primary-container border-4 border-[#0D1B2A] mt-1"></div>
                        <div class="flex-1">
                            <p class="text-[12px] leading-tight mb-1"><span class="font-bold text-white">Sarah
                                    Jenkins</span> uploaded 4 new site photos.</p>
                            <p class="text-[11px] text-on-surface-variant">45 mins ago</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 relative z-10">
                        <div class="w-3.5 h-3.5 rounded-full bg-error border-4 border-[#0D1B2A] mt-1"></div>
                        <div class="flex-1">
                            <p class="text-[12px] leading-tight mb-1"><span class="font-bold text-white">System</span>
                                flagged a budget overage on Skyline Tower.</p>
                            <p class="text-[11px] text-on-surface-variant">2 hours ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Budget Performance Chart -->
        <div class="glass-card rounded-2xl p-8 mb-8">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h4 class="font-bold text-xl font-headline mb-1">Budget Performance</h4>
                    <p class="text-on-surface-variant text-[13px]">Allocated vs Spent across all active projects</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded bg-primary-container"></span>
                        <span
                            class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Allocated</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded bg-secondary-container"></span>
                        <span
                            class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Spent</span>
                    </div>
                </div>
            </div>
            <div class="relative h-[300px] w-full flex items-end gap-12 px-4 border-b border-white/5">
                <!-- Data Columns Jul - Jan -->
                <div class="flex-1 flex flex-col items-center gap-4 h-full relative group">
                    <div class="flex-1 w-full flex items-end justify-center gap-1">
                        <div class="w-8 bg-primary-container/40 rounded-t-sm" style="height: 60%"></div>
                        <div class="w-8 bg-secondary-container rounded-t-sm" style="height: 55%"></div>
                    </div>
                    <span class="text-[11px] text-on-surface-variant font-bold pb-2">JUL</span>
                </div>
                <div class="flex-1 flex flex-col items-center gap-4 h-full relative group">
                    <div class="flex-1 w-full flex items-end justify-center gap-1">
                        <div class="w-8 bg-primary-container/40 rounded-t-sm" style="height: 65%"></div>
                        <div class="w-8 bg-secondary-container rounded-t-sm" style="height: 60%"></div>
                    </div>
                    <span class="text-[11px] text-on-surface-variant font-bold pb-2">AUG</span>
                </div>
                <div class="flex-1 flex flex-col items-center gap-4 h-full relative group">
                    <div class="flex-1 w-full flex items-end justify-center gap-1">
                        <div class="w-8 bg-primary-container/40 rounded-t-sm" style="height: 70%"></div>
                        <div class="w-8 bg-secondary-container rounded-t-sm" style="height: 68%"></div>
                    </div>
                    <span class="text-[11px] text-on-surface-variant font-bold pb-2">SEP</span>
                </div>
                <div class="flex-1 flex flex-col items-center gap-4 h-full relative group">
                    <div class="flex-1 w-full flex items-end justify-center gap-1">
                        <div class="w-8 bg-primary-container/40 rounded-t-sm" style="height: 75%"></div>
                        <div class="w-8 bg-secondary-container rounded-t-sm" style="height: 72%"></div>
                    </div>
                    <span class="text-[11px] text-on-surface-variant font-bold pb-2">OCT</span>
                </div>
                <div class="flex-1 flex flex-col items-center gap-4 h-full relative group">
                    <div class="flex-1 w-full flex items-end justify-center gap-1">
                        <div class="w-8 bg-primary-container/40 rounded-t-sm" style="height: 80%"></div>
                        <div class="w-8 bg-secondary-container rounded-t-sm" style="height: 78%"></div>
                    </div>
                    <span class="text-[11px] text-on-surface-variant font-bold pb-2">NOV</span>
                </div>
                <div class="flex-1 flex flex-col items-center gap-4 h-full relative group">
                    <div class="flex-1 w-full flex items-end justify-center gap-1">
                        <div class="w-8 bg-primary-container/40 rounded-t-sm" style="height: 85%"></div>
                        <div class="w-8 bg-secondary-container rounded-t-sm" style="height: 82%"></div>
                    </div>
                    <span class="text-[11px] text-on-surface-variant font-bold pb-2">DEC</span>
                </div>
                <!-- CRITICAL MONTH JAN (91%) -->
                <div class="flex-1 flex flex-col items-center gap-4 h-full relative group">
                    <div class="flex-1 w-full flex items-end justify-center gap-1">
                        <div class="w-8 bg-primary-container/40 rounded-t-sm" style="height: 90%"></div>
                        <div class="w-8 bg-error rounded-t-sm" style="height: 91%"></div>
                    </div>
                    <div
                        class="absolute top-0 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center">
                        <div class="bg-error text-white text-[10px] font-bold px-2 py-1 rounded shadow-lg mb-1">CRITICAL
                            OVERAGE</div>
                    </div>
                    <span class="text-[11px] text-error font-extrabold pb-2">JAN</span>
                </div>
            </div>
        </div>
    </main>
</body>

</html>