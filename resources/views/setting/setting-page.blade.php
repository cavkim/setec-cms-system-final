<!DOCTYPE html>

<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
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
                        "inverse-surface": "#dae2fd",
                        "on-tertiary-fixed": "#0d1c2d",
                        "tertiary": "#b9c8de",
                        "on-error-container": "#ffdad6",
                        "inverse-primary": "#005ac2",
                        "on-secondary-container": "#5b3800",
                        "on-background": "#dae2fd",
                        "on-primary-fixed-variant": "#004395",
                        "on-primary-container": "#00285d",
                        "surface-bright": "#31394d",
                        "background": "#0b1326",
                        "tertiary-container": "#8392a6",
                        "on-secondary-fixed-variant": "#653e00",
                        "on-tertiary-fixed-variant": "#39485a",
                        "on-surface": "#dae2fd",
                        "error-container": "#93000a",
                        "outline": "#8c909f",
                        "secondary-container": "#ee9800",
                        "surface-tint": "#adc6ff",
                        "on-tertiary-container": "#1c2b3c",
                        "primary-container": "#4d8eff",
                        "on-tertiary": "#233143",
                        "primary-fixed": "#d8e2ff",
                        "on-secondary": "#472a00",
                        "surface-container-lowest": "#060e20",
                        "inverse-on-surface": "#283044",
                        "surface-container-highest": "#2d3449",
                        "surface": "#0b1326",
                        "surface-variant": "#2d3449",
                        "tertiary-fixed": "#d4e4fa",
                        "on-primary": "#002e6a",
                        "primary": "#adc6ff",
                        "tertiary-fixed-dim": "#b9c8de",
                        "primary-fixed-dim": "#adc6ff",
                        "on-secondary-fixed": "#2a1700",
                        "surface-container": "#171f33",
                        "on-error": "#690005",
                        "secondary-fixed-dim": "#ffb95f",
                        "surface-dim": "#0b1326",
                        "surface-container-high": "#222a3d",
                        "error": "#ffb4ab",
                        "secondary-fixed": "#ffddb8",
                        "outline-variant": "#424754",
                        "surface-container-low": "#131b2e",
                        "on-primary-fixed": "#001a42",
                        "on-surface-variant": "#c2c6d6",
                        "secondary": "#ffb95f"
                    },
                    fontFamily: {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    },
                    borderRadius: { "DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem" },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .glass-panel {
            background: rgba(45, 52, 73, 0.6);
            backdrop-filter: blur(16px);
        }
    </style>
</head>

<body class="bg-background text-on-surface font-body selection:bg-primary/30">
    <!-- Sidebar Navigation -->
    <aside
        class="h-screen w-64 fixed left-0 top-0 overflow-y-auto bg-[#0b1326] border-r border-slate-800/30 flex flex-col py-6 z-40 hidden md:flex">
        <div class="px-6 mb-10">
            <h2 class="font-manrope font-black text-slate-200 text-xl tracking-tighter">Industrial SaaS</h2>
            <p class="font-inter text-[10px] font-semibold uppercase tracking-widest text-primary/60">Site Terminal v2.4
            </p>
        </div>
        <nav class="flex-1">
            <a class="text-slate-500 hover:text-slate-300 mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-all font-inter text-xs font-semibold uppercase tracking-wider"
                href="#">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            <a class="text-slate-500 hover:text-slate-300 mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-all font-inter text-xs font-semibold uppercase tracking-wider"
                href="#">
                <span class="material-symbols-outlined">architecture</span>
                Blueprint
            </a>
            <a class="text-slate-500 hover:text-slate-300 mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-all font-inter text-xs font-semibold uppercase tracking-wider"
                href="#">
                <span class="material-symbols-outlined">assignment</span>
                Field Reports
            </a>
            <a class="text-slate-500 hover:text-slate-300 mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-all font-inter text-xs font-semibold uppercase tracking-wider"
                href="#">
                <span class="material-symbols-outlined">inventory_2</span>
                Inventory
            </a>
            <a class="text-slate-500 hover:text-slate-300 mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-all font-inter text-xs font-semibold uppercase tracking-wider"
                href="#">
                <span class="material-symbols-outlined">engineering</span>
                Safety
            </a>
            <!-- ACTIVE TAB: Settings -->
            <a class="bg-[#2d3449] text-blue-300 rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 font-inter text-xs font-semibold uppercase tracking-wider"
                href="#">
                <span class="material-symbols-outlined">settings</span>
                Settings
            </a>
        </nav>
        <div class="mt-auto px-2">
            <a class="text-slate-500 hover:text-slate-300 mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-all font-inter text-xs font-semibold uppercase tracking-wider"
                href="#">
                <span class="material-symbols-outlined">contact_support</span>
                Support
            </a>
            <a class="text-slate-500 hover:text-slate-300 mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-all font-inter text-xs font-semibold uppercase tracking-wider"
                href="#">
                <span class="material-symbols-outlined">logout</span>
                Logout
            </a>
        </div>
    </aside>
    <!-- Main Content Canvas -->
    <main class="md:ml-64 min-h-screen bg-surface-container-low">
        <!-- Top Navigation -->
        <header
            class="bg-[#171f33] backdrop-blur-md sticky top-0 z-30 flex justify-between items-center w-full px-6 py-3 shadow-[0px_8px_24px_rgba(6,14,32,0.4)]">
            <div class="flex items-center gap-8">
                <h1 class="text-xl font-bold tracking-tighter text-slate-100 font-headline">Ironclad Forge Settings</h1>
                <nav class="hidden lg:flex gap-6 font-manrope text-sm font-medium tracking-tight">
                    <a class="text-slate-400 hover:text-slate-200 transition-colors" href="#">Projects</a>
                    <a class="text-slate-400 hover:text-slate-200 transition-colors" href="#">Schedule</a>
                    <a class="text-slate-400 hover:text-slate-200 transition-colors" href="#">Resources</a>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <button
                    class="material-symbols-outlined text-slate-400 hover:text-white transition-colors">notifications</button>
                <button
                    class="material-symbols-outlined text-slate-400 hover:text-white transition-colors">help</button>
                <div
                    class="h-8 w-8 rounded-full overflow-hidden bg-surface-container-highest border border-outline-variant/30">
                    <img class="w-full h-full object-cover"
                        data-alt="professional architect profile headshot wearing site safety vest in soft industrial office lighting"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDvBE3_8CpYMKpUwHYieh7rEnlLgZPcNSzuGUp2htJ4px8muGrrfKD2KBF0Z9HeVG2FFbbdMVOg-DUaAnB0w8tlgsPZDXt5Gh6Li1TS54g5YIOrczVKwjFbGlRZTmA4CJC58gjCcIZdCACoVsHXZnvt8Vj-pFSNxoHaQMSbE7fVFeXoIs3ACPZPxqUunSFyd--5F4okf4Ja9lORcJm4z7tp1BtX2Bgijv-9r1iiLdYDHMU9YWN3wEbLpxWUfggGDg2jKH8tfuKo76xD" />
                </div>
            </div>
        </header>
        <!-- Settings Layout -->
        <div class="max-w-7xl mx-auto px-6 py-10">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Settings Navigation Sidebar (Internal) -->
                <aside class="lg:w-72 flex-shrink-0">
                    <div class="sticky top-24 space-y-1">
                        <button
                            class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container-highest rounded-xl text-primary font-semibold text-sm transition-all border-l-4 border-primary">
                            <span class="material-symbols-outlined">tune</span>
                            General
                        </button>
                        <button
                            class="w-full flex items-center gap-3 px-4 py-3 hover:bg-surface-container rounded-xl text-on-surface-variant font-medium text-sm transition-all border-l-4 border-transparent">
                            <span class="material-symbols-outlined">security</span>
                            Security
                        </button>
                        <button
                            class="w-full flex items-center gap-3 px-4 py-3 hover:bg-surface-container rounded-xl text-on-surface-variant font-medium text-sm transition-all border-l-4 border-transparent">
                            <span class="material-symbols-outlined">notifications_active</span>
                            Notifications
                        </button>
                        <button
                            class="w-full flex items-center gap-3 px-4 py-3 hover:bg-surface-container rounded-xl text-on-surface-variant font-medium text-sm transition-all border-l-4 border-transparent">
                            <span class="material-symbols-outlined">terminal</span>
                            System
                        </button>
                        <button
                            class="w-full flex items-center gap-3 px-4 py-3 hover:bg-surface-container rounded-xl text-on-surface-variant font-medium text-sm transition-all border-l-4 border-transparent">
                            <span class="material-symbols-outlined">group</span>
                            Team Management
                        </button>
                    </div>
                </aside>
                <!-- Settings Canvas -->
                <div class="flex-1 space-y-8">
                    <!-- Section: General Settings -->
                    <section class="bg-surface-container rounded-xl overflow-hidden shadow-sm">
                        <div class="px-8 py-6 border-b border-outline-variant/10">
                            <h2 class="font-headline text-lg font-bold text-slate-100">General Project Defaults</h2>
                            <p class="text-xs text-on-surface-variant mt-1 font-label uppercase tracking-widest">Base
                                operational parameters for site management</p>
                        </div>
                        <div class="p-8 space-y-8">
                            <!-- Bento Grid Form -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Default
                                        Project Currency</label>
                                    <div class="relative">
                                        <select
                                            class="w-full bg-surface-container-lowest border border-outline-variant/20 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-transparent outline-none appearance-none text-sm text-on-surface">
                                            <option>USD - United States Dollar</option>
                                            <option>EUR - Euro</option>
                                            <option>GBP - British Pound</option>
                                        </select>
                                        <span
                                            class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">expand_more</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider">System
                                        Date Format</label>
                                    <div class="relative">
                                        <select
                                            class="w-full bg-surface-container-lowest border border-outline-variant/20 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-transparent outline-none appearance-none text-sm text-on-surface">
                                            <option>DD/MM/YYYY</option>
                                            <option>MM/DD/YYYY</option>
                                            <option>YYYY-MM-DD</option>
                                        </select>
                                        <span
                                            class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">expand_more</span>
                                    </div>
                                </div>
                                <div class="space-y-2 md:col-span-2">
                                    <label
                                        class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Safety
                                        Protocol Version</label>
                                    <div class="flex gap-4">
                                        <input
                                            class="flex-1 bg-surface-container-lowest border border-outline-variant/20 rounded-xl px-4 py-3 text-sm text-on-surface/50 cursor-not-allowed"
                                            readonly="" type="text" value="OSHA-30-2024-v.4.2" />
                                        <button
                                            class="bg-surface-container-highest px-6 py-3 rounded-xl text-primary text-xs font-bold uppercase tracking-widest hover:bg-primary/10 transition-colors">Update
                                            Registry</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Section: Global Alert Toggles -->
                    <section class="bg-surface-container rounded-xl overflow-hidden shadow-sm">
                        <div class="px-8 py-6 border-b border-outline-variant/10">
                            <h2 class="font-headline text-lg font-bold text-slate-100">Global Alerts &amp; Monitoring
                            </h2>
                            <p class="text-xs text-on-surface-variant mt-1 font-label uppercase tracking-widest">
                                Configure system-wide broadcast triggers</p>
                        </div>
                        <div class="divide-y divide-outline-variant/10">
                            <!-- Toggle Item -->
                            <div
                                class="flex items-center justify-between p-8 hover:bg-surface-container-high transition-colors">
                                <div class="space-y-1">
                                    <h4 class="font-bold text-sm text-slate-100">Safety Breach Lockdown</h4>
                                    <p class="text-xs text-on-surface-variant">Automatically pause all site activity
                                        when a critical safety violation is logged.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input checked="" class="sr-only peer" type="checkbox" />
                                    <div
                                        class="w-12 h-6 bg-surface-container-lowest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary">
                                    </div>
                                </label>
                            </div>
                            <!-- Toggle Item -->
                            <div
                                class="flex items-center justify-between p-8 hover:bg-surface-container-high transition-colors">
                                <div class="space-y-1">
                                    <h4 class="font-bold text-sm text-slate-100">Over-Budget Watchdog</h4>
                                    <p class="text-xs text-on-surface-variant">Notify Regional Managers when procurement
                                        exceeds 15% of phase estimate.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input checked="" class="sr-only peer" type="checkbox" />
                                    <div
                                        class="w-12 h-6 bg-surface-container-lowest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                    </div>
                                </label>
                            </div>
                            <!-- Toggle Item -->
                            <div
                                class="flex items-center justify-between p-8 hover:bg-surface-container-high transition-colors">
                                <div class="space-y-1">
                                    <h4 class="font-bold text-sm text-slate-100">Drone Survey Automation</h4>
                                    <p class="text-xs text-on-surface-variant">Trigger bi-weekly site flyovers for
                                        digital twin synchronization.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input class="sr-only peer" type="checkbox" />
                                    <div
                                        class="w-12 h-6 bg-surface-container-lowest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </section>
                    <!-- Section: Security Profile -->
                    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2 bg-surface-container rounded-xl p-8 flex flex-col justify-between">
                            <div>
                                <h3 class="font-headline text-lg font-bold text-slate-100">Identity &amp; Access Control
                                </h3>
                                <p class="text-xs text-on-surface-variant mt-2 max-w-md">Manage 2FA, biometric site
                                    access, and encrypted credential keys for the engineering team.</p>
                            </div>
                            <div class="mt-8 flex gap-4">
                                <button
                                    class="bg-gradient-to-br from-primary to-primary-container text-on-primary px-8 py-3 rounded-xl font-bold text-sm shadow-lg">Revoke
                                    All Tokens</button>
                                <button
                                    class="border border-outline-variant/30 text-on-surface px-8 py-3 rounded-xl font-bold text-sm hover:bg-surface-container-highest transition-all">Audit
                                    Logs</button>
                            </div>
                        </div>
                        <div class="bg-surface-container-highest rounded-xl p-8 relative overflow-hidden">
                            <div class="relative z-10">
                                <span
                                    class="material-symbols-outlined text-secondary text-4xl mb-4">shield_with_heart</span>
                                <h4 class="font-headline font-bold text-slate-100">Ironclad Shield</h4>
                                <p class="text-xs text-on-surface-variant mt-2 leading-relaxed">Enterprise-grade
                                    encryption is active across all blueprints and financial documents.</p>
                            </div>
                            <!-- Aesthetic Gradient Background -->
                            <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-secondary/10 blur-3xl rounded-full">
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!-- Sticky Footer Action Bar -->
        <div
            class="fixed bottom-0 left-0 md:left-64 right-0 glass-panel border-t border-outline-variant/10 px-8 py-4 flex items-center justify-between z-40">
            <div class="flex items-center gap-4">
                <span class="flex h-2 w-2 rounded-full bg-secondary"></span>
                <p class="text-[10px] font-label uppercase tracking-widest text-on-surface-variant">Changes unsaved in 3
                    fields</p>
            </div>
            <div class="flex gap-4">
                <button
                    class="px-6 py-2 text-on-surface-variant font-bold text-sm hover:text-white transition-colors">Discard</button>
                <button
                    class="bg-gradient-to-br from-primary to-primary-container text-on-primary px-10 py-2.5 rounded-xl font-bold text-sm shadow-[0px_4px_16px_rgba(77,142,255,0.3)] hover:scale-[1.02] active:scale-95 duration-150">Save
                    Configurations</button>
            </div>
        </div>
    </main>
    <!-- Mobile Navigation Bar -->
    <div
        class="md:hidden fixed bottom-0 left-0 right-0 bg-[#171f33] flex justify-around py-3 px-2 z-50 border-t border-outline-variant/10">
        <button class="flex flex-col items-center gap-1 text-slate-400">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-[10px] uppercase font-bold">Dash</span>
        </button>
        <button class="flex flex-col items-center gap-1 text-slate-400">
            <span class="material-symbols-outlined">engineering</span>
            <span class="text-[10px] uppercase font-bold">Safety</span>
        </button>
        <button class="flex flex-col items-center gap-1 text-blue-400">
            <span class="material-symbols-outlined">settings</span>
            <span class="text-[10px] uppercase font-bold">Config</span>
        </button>
    </div>
</body>

</html>