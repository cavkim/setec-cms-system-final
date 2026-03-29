<!-- <x-guest-layout>
    
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

 
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> -->
<!DOCTYPE html>

<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Ironclad Forge - Login</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;family=Inter:wght@300;400;500;600&amp;display=swap"
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
                        "secondary-fixed-dim": "#ffb95f",
                        "primary": "#adc6ff",
                        "on-surface-variant": "#c2c6d6",
                        "tertiary": "#b9c8de",
                        "surface-tint": "#adc6ff",
                        "tertiary-fixed": "#d4e4fa",
                        "surface": "#0b1326",
                        "surface-bright": "#31394d",
                        "on-tertiary-fixed": "#0d1c2d",
                        "secondary-fixed": "#ffddb8",
                        "surface-variant": "#2d3449",
                        "secondary": "#ffb95f",
                        "surface-container-low": "#131b2e",
                        "error": "#ffb4ab",
                        "on-secondary": "#472a00",
                        "on-primary": "#002e6a",
                        "on-tertiary-fixed-variant": "#39485a",
                        "primary-fixed-dim": "#adc6ff",
                        "on-primary-fixed-variant": "#004395",
                        "on-error": "#690005",
                        "outline": "#8c909f",
                        "outline-variant": "#424754",
                        "on-error-container": "#ffdad6",
                        "primary-fixed": "#d8e2ff",
                        "on-background": "#dae2fd",
                        "on-primary-fixed": "#001a42",
                        "background": "#0b1326",
                        "on-primary-container": "#00285d",
                        "inverse-primary": "#005ac2",
                        "tertiary-container": "#8392a6",
                        "on-surface": "#dae2fd",
                        "surface-container": "#171f33",
                        "primary-container": "#4d8eff",
                        "on-tertiary": "#233143",
                        "tertiary-fixed-dim": "#b9c8de",
                        "surface-container-lowest": "#060e20",
                        "on-secondary-fixed-variant": "#653e00",
                        "secondary-container": "#ee9800",
                        "surface-container-highest": "#2d3449",
                        "surface-container-high": "#222a3d",
                        "on-secondary-container": "#5b3800",
                        "on-tertiary-container": "#1c2b3c",
                        "inverse-on-surface": "#283044",
                        "on-secondary-fixed": "#2a1700",
                        "surface-dim": "#0b1326",
                        "inverse-surface": "#dae2fd",
                        "error-container": "#93000a"
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
            background: rgba(45, 52, 73, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .tectonic-bg {
            background-image: radial-gradient(circle at 2px 2px, rgba(140, 144, 159, 0.05) 1px, transparent 0);
            background-size: 32px 32px;
        }
    </style>
</head>

<body class="bg-background text-on-background font-body selection:bg-primary/30 antialiased overflow-hidden">
    <!-- TopNavBar Shell (Suppressed Content but maintaining layout according to JSON) -->
    <!-- JSON Rule: TopNavBar is fixed top, product_name is Tectonic Blueprint -->
    <nav
        class="fixed top-0 w-full bg-[#0b1326] flex justify-between items-center px-8 py-4 z-50 bg-gradient-to-b from-[#171f33] to-transparent">
        <div class="text-xl font-bold tracking-tight text-slate-100 font-headline">
            CMS
        </div>
        <div class="flex items-center gap-6 font-manrope text-sm font-medium tracking-wide">
            <button
                class="text-slate-400 hover:text-blue-300 transition-colors scale-95 active:scale-90 duration-200 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-400" data-icon="help">help</span>
                <span>Help</span>
            </button>
            <button
                class="text-slate-400 hover:text-blue-300 transition-colors scale-95 active:scale-90 duration-200 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-400" data-icon="language">language</span>
                <span>Region</span>
            </button>
        </div>
    </nav>
    <!-- Main Content Canvas -->
    <main class="relative min-h-screen flex items-center justify-center p-6 tectonic-bg">
        <!-- Background Elements for Industrial feel -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-[10%] -left-[5%] w-[40%] h-[40%] bg-primary/5 blur-[120px] rounded-full"></div>
            <div class="absolute top-[60%] -right-[5%] w-[30%] h-[30%] bg-secondary/5 blur-[100px] rounded-full"></div>
        </div>
        <!-- Login Container -->
        <div class="relative w-full max-w-md z-10">
            <!-- Branding Header -->
            <div class="text-center mb-10">
                <div
                    class="inline-flex items-center justify-center p-4 rounded-xl bg-surface-container-high mb-6 shadow-2xl">
                    <span class="material-symbols-outlined text-primary text-4xl" data-icon="architecture"
                        style="font-variation-settings: 'FILL' 1;">architecture</span>
                </div>
                <h1 class="font-headline text-3xl font-extrabold tracking-tight text-on-surface mb-2">CMS
                </h1>
                <p class="text-on-surface-variant font-body text-sm tracking-wide">High-Precision Industrial
                    Construction Management</p>
            </div>
            <!-- Login Card -->
            <div
                class="glass-panel border border-outline-variant/15 rounded-xl p-8 shadow-[0px_8px_24px_rgba(6,14,32,0.4)]">
                <form class="space-y-6">
                    <!-- Email Input Group -->
                    <div class="space-y-2">
                        <label
                            class="font-label text-xs uppercase tracking-widest text-on-surface-variant font-semibold"
                            for="email">Email Address</label>
                        <div class="relative group">
                            <span
                                class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant group-focus-within:text-primary transition-colors"
                                data-icon="alternate_email">alternate_email</span>
                            <input
                                class="w-full bg-surface-container-lowest border-0 rounded-xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-outline/50 ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary transition-all duration-200 outline-none"
                                id="email" placeholder="name@company.com" type="email" />
                        </div>
                    </div>
                    <!-- Password Input Group -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label
                                class="font-label text-xs uppercase tracking-widest text-on-surface-variant font-semibold"
                                for="password">Password</label>
                            <a class="text-xs text-primary font-medium hover:text-primary-container transition-colors"
                                href="#">Forgot Password?</a>
                        </div>
                        <div class="relative group">
                            <span
                                class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant group-focus-within:text-primary transition-colors"
                                data-icon="lock">lock</span>
                            <input
                                class="w-full bg-surface-container-lowest border-0 rounded-xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-outline/50 ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary transition-all duration-200 outline-none"
                                id="password" placeholder="••••••••" type="password" />
                        </div>
                    </div>
                    <!-- Actions -->
                    <div class="flex items-center">
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <div class="relative">
                                <input class="peer hidden" type="checkbox" />
                                <div
                                    class="w-5 h-5 bg-surface-container-lowest border border-outline-variant/30 rounded peer-checked:bg-primary peer-checked:border-primary transition-all">
                                </div>
                                <span
                                    class="absolute inset-0 flex items-center justify-center material-symbols-outlined text-on-primary text-xs opacity-0 peer-checked:opacity-100 transition-opacity"
                                    data-icon="check" style="font-variation-settings: 'wght' 700;">check</span>
                            </div>
                            <span
                                class="text-sm text-on-surface-variant group-hover:text-on-surface transition-colors">Remember
                                Me</span>
                        </label>
                    </div>
                    <!-- Primary Submit -->
                    <button
                        class="w-full py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary font-headline font-bold text-sm tracking-widest uppercase rounded-xl shadow-lg shadow-primary/20 hover:shadow-primary/30 transition-all active:scale-[0.98] duration-200"
                        type="submit">
                        Sign In
                    </button>
                </form>
                <!-- Divider -->
                <div class="relative my-8 text-center">
                    <div class="absolute inset-0 flex items-center"><span
                            class="w-full border-t border-outline-variant/10"></span></div>
                    <span
                        class="relative px-4 text-[10px] uppercase tracking-[0.2em] font-label text-outline/60 bg-[#1e263a]">Or
                        continue with</span>
                </div>
                <!-- Social Logins -->
                <div class="grid grid-cols-2 gap-4">
                    <button
                        class="flex items-center justify-center gap-3 py-3 px-4 bg-surface-container-high rounded-xl ring-1 ring-outline-variant/15 hover:bg-surface-container-highest transition-colors group">
                        <img alt="Google" class="w-5 h-5 opacity-80 group-hover:opacity-100 transition-opacity"
                            data-alt="Official colorful Google G logo icon for secure single sign-on authentication service integration"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCGbzq21T_rQiuewtQRLxI27_D2ixXYpe-5ziJsQRjjZlzX2Eu4kMfDXwBaSpFRF5k0OUWyGZNLs_tsOiUomQYtgyEXCSZOwvpuZmYC_os9cmIrpomZzKAGpi2j1Qy2UXBnOeEBH0fIDhb89zr75cqGdudnakiEEmUzHgDTowZ6D10j3_NO5wVwwvquprZXHWFqI5gHyu5bXsEaeZkzaz8K6us0raVFrNNFkiXMlIaUormk8cGiXXEcJWuvzN65yh2_0Z2Jcxln7UAb" />
                        <span class="text-xs font-semibold text-on-surface-variant font-label">Google</span>
                    </button>
                    <button
                        class="flex items-center justify-center gap-3 py-3 px-4 bg-surface-container-high rounded-xl ring-1 ring-outline-variant/15 hover:bg-surface-container-highest transition-colors group">
                        <img alt="Microsoft" class="w-5 h-5 opacity-80 group-hover:opacity-100 transition-opacity"
                            data-alt="Official Microsoft multi-colored square window logo for professional enterprise active directory authentication"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuC_iwMdqT3pyJXRXrVEXSzMw_wGqAYvRz60Ikecj4MUAuOA8nuCyDsFsOPkNH5hboikZ555EK9fjcKNe0SMWexV-BZ3K6aXKPUrdFhoLpfr8EPrxD_Xt9czybau4-jqx_QD2ivo2g5yfh293x_eBCuztQNjZaewJ8ELj-cEmN3sEgxGd7J--vNUU9tdH6jOLZguPB_U1V64NtXy9qLiZmsV5iBeVB2BezzSoHDjVhBipg2vqUWIcizrsIU2xOHUZvWKQ-hL3v1zTXAR" />
                        <span class="text-xs font-semibold text-on-surface-variant font-label">Microsoft</span>
                    </button>
                </div>
            </div>
            <!-- Footer Link -->
            <div class="mt-8 text-center">
                <p class="text-on-surface-variant text-sm">
                    New to the platform?
                    <a class="text-primary font-semibold hover:underline ml-1" href="#">Request Access</a>
                </p>
            </div>
        </div>
    </main>
    <!-- Footer Shell -->
    <footer
        class="fixed bottom-0 w-full flex flex-col md:flex-row justify-between items-center px-12 py-6 font-inter text-[10px] uppercase tracking-widest text-slate-500 opacity-80 hover:opacity-100 transition-opacity bg-transparent">
        <div class="mb-4 md:mb-0">
            © 2024 Tectonic Construction Systems. High-Precision Industrial Grade.
        </div>
        <div class="flex gap-8">
            <a class="hover:text-blue-400 transition-colors" href="#">Privacy Policy</a>
            <a class="hover:text-blue-400 transition-colors" href="#">Terms of Service</a>
            <a class="hover:text-blue-400 transition-colors" href="#">Security</a>
            <a class="hover:text-blue-400 transition-colors" href="#">Support</a>
        </div>
    </footer>
</body>

</html>