<!DOCTYPE html>

<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;family=Inter:wght@300;400;500;600&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
            vertical-align: middle;
        }

        .glass-panel {
            background: rgba(23, 31, 51, 0.7);
            backdrop-filter: blur(20px);
        }
    </style>
</head>

<body class="bg-background font-body text-on-surface">
    <div class="fixed inset-0 bg-surface-container-lowest/80 backdrop-blur-sm z-40"></div>
    <aside
        class="fixed inset-y-0 right-0 w-full max-w-lg glass-panel shadow-[0px_8px_24px_rgba(6,14,32,0.4)] z-50 flex flex-col border-l border-outline-variant/20">
        <header class="p-6 flex items-center justify-between">
            <h2 class="font-headline text-xl font-bold tracking-tight text-white">Edit Profile</h2>
            <button class="p-2 rounded-xl hover:bg-surface-container-highest transition-colors group">
                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-white"
                    data-icon="close">close</span>
            </button>
        </header>
        <div class="flex-1 overflow-y-auto px-6 pb-12">
            <section class="mb-8 flex flex-col items-center">
                <div class="relative group">
                    <div
                        class="w-32 h-32 rounded-full overflow-hidden border-4 border-surface-container-highest shadow-xl">
                        <img class="w-full h-full object-cover"
                            data-alt="professional portrait of a middle-aged construction project manager with short hair and a focused expression in a dark studio setting"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBjoJnX7alLbTYx-ZlR7rhH9_kXxthmYIwXtiq0GtrU0OdcxUpf40hsO7r15-PL7dOZDmyihFN5zZAZ6Uk0NhltVJqfHJ1FbR6j-rkOQcbWftI9UevqwVZprmyQJ_-_Nwrvmlfvf5ugGyQV03QejHruC9reNlVfrEuPGzUrN0CO-8W11FRS9zXQwTMN5HaiTWKoH5SmyImWhTAom52nLlvWhgkwX8edYdC0j9sqSpkENe5JpoQQnnyI8l0xoUVzx9izpDO2cdJKPwuO" />
                    </div>
                    <button
                        class="absolute bottom-0 right-0 bg-primary-container p-2 rounded-full shadow-lg border-2 border-surface shadow-primary/20 hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-on-primary-container text-lg"
                            data-icon="edit">edit</span>
                    </button>
                </div>
                <div class="mt-4 text-center">
                    <h3 class="font-headline text-lg font-bold text-white tracking-tight">Marcus Thorne</h3>
                    <p class="font-label text-xs uppercase tracking-widest text-primary font-semibold mt-1">Lead Site
                        Engineer</p>
                </div>
            </section>
            <section class="space-y-6">
                <div class="space-y-4">
                    <h4 class="font-headline text-sm font-bold text-on-surface-variant uppercase tracking-widest">
                        Personal Information</h4>
                    <div class="grid grid-cols-1 gap-4">
                        <div class="space-y-2">
                            <label class="font-label text-xs font-semibold text-on-surface-variant ml-1">Full
                                Name</label>
                            <input
                                class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary-container transition-all placeholder-on-surface-variant/30"
                                placeholder="Enter full name" type="text" value="Marcus Thorne" />
                        </div>
                        <div class="space-y-2">
                            <label class="font-label text-xs font-semibold text-on-surface-variant ml-1">Work
                                Email</label>
                            <input
                                class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary-container transition-all placeholder-on-surface-variant/30"
                                placeholder="Email address" type="email" value="m.thorne@ironcladforge.com" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="font-label text-xs font-semibold text-on-surface-variant ml-1">Phone
                                    Number</label>
                                <input
                                    class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary-container transition-all placeholder-on-surface-variant/30"
                                    type="tel" value="+1 (555) 942-0192" />
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="font-label text-xs font-semibold text-on-surface-variant ml-1">Position</label>
                                <input
                                    class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary-container transition-all placeholder-on-surface-variant/30"
                                    type="text" value="Lead Site Engineer" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-6 space-y-4">
                    <h4 class="font-headline text-sm font-bold text-on-surface-variant uppercase tracking-widest">System
                        Preferences</h4>
                    <div class="bg-surface-container-low rounded-xl p-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-secondary"
                                    data-icon="dark_mode">dark_mode</span>
                                <div>
                                    <p class="text-sm font-semibold text-on-surface">Interface Theme</p>
                                    <p class="text-xs text-on-surface-variant">Switch between light and dark modes</p>
                                </div>
                            </div>
                            <select
                                class="bg-surface-container-highest border-none rounded-lg text-xs font-bold text-primary px-3 py-2 focus:ring-1 focus:ring-primary">
                                <option>Dark Industrial</option>
                                <option>High Contrast</option>
                                <option>System Default</option>
                            </select>
                        </div>
                        <div class="border-t border-outline-variant/10"></div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary"
                                    data-icon="notifications">notifications</span>
                                <div>
                                    <p class="text-sm font-semibold text-on-surface">Push Notifications</p>
                                    <p class="text-xs text-on-surface-variant">Receive alerts on mobile and desktop</p>
                                </div>
                            </div>
                            <button class="w-12 h-6 bg-primary-container rounded-full relative transition-colors">
                                <span class="absolute right-1 top-1 w-4 h-4 bg-white rounded-full"></span>
                            </button>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-on-surface-variant"
                                    data-icon="mail">mail</span>
                                <div>
                                    <p class="text-sm font-semibold text-on-surface">Weekly Summary Reports</p>
                                    <p class="text-xs text-on-surface-variant">Email digests of site safety reports</p>
                                </div>
                            </div>
                            <button
                                class="w-12 h-6 bg-surface-container-highest rounded-full relative transition-colors">
                                <span class="absolute left-1 top-1 w-4 h-4 bg-outline rounded-full"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <footer class="p-6 bg-surface-container-high border-t border-outline-variant/10 flex gap-3">
            <button
                class="flex-1 py-3 px-4 rounded-xl font-headline text-sm font-bold text-on-surface-variant hover:bg-surface-container-highest transition-colors">
                Cancel
            </button>
            <button
                class="flex-[2] py-3 px-4 rounded-xl font-headline text-sm font-bold text-on-primary bg-gradient-to-br from-primary to-primary-container shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                Save Changes
            </button>
        </footer>
    </aside>

</body>

</html>