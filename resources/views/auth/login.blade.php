<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>CMS — Login</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#adc6ff",
                        "on-surface-variant": "#c2c6d6",
                        "tertiary": "#b9c8de",
                        "surface": "#0b1326",
                        "surface-bright": "#31394d",
                        "surface-variant": "#2d3449",
                        "secondary": "#ffb95f",
                        "surface-container-low": "#131b2e",
                        "error": "#ffb4ab",
                        "on-primary": "#002e6a",
                        "outline": "#8c909f",
                        "outline-variant": "#424754",
                        "primary-fixed": "#d8e2ff",
                        "on-background": "#dae2fd",
                        "background": "#0b1326",
                        "on-primary-container": "#00285d",
                        "inverse-primary": "#005ac2",
                        "tertiary-container": "#8392a6",
                        "on-surface": "#dae2fd",
                        "surface-container": "#171f33",
                        "primary-container": "#4d8eff",
                        "surface-container-lowest": "#060e20",
                        "secondary-container": "#ee9800",
                        "surface-container-highest": "#2d3449",
                        "surface-container-high": "#222a3d",
                        "surface-dim": "#0b1326",
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

<body class="bg-background text-on-background font-body antialiased overflow-hidden">

    {{-- ── TOP NAV ── --}}
    <nav
        class="fixed top-0 w-full bg-[#0b1326] flex justify-between items-center px-8 py-4 z-50 bg-gradient-to-b from-[#171f33] to-transparent">
        <div class="text-xl font-bold tracking-tight text-slate-100 font-headline">CMS</div>
        <div class="flex items-center gap-6 text-sm font-medium tracking-wide">
            <button class="text-slate-400 hover:text-blue-300 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-400">help</span>
                <span>Help</span>
            </button>
            <button class="text-slate-400 hover:text-blue-300 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-400">language</span>
                <span>Region</span>
            </button>
        </div>
    </nav>

    {{-- ── MAIN ── --}}
    <main class="relative min-h-screen flex items-center justify-center p-6 tectonic-bg">

        {{-- Background blobs --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-[10%] -left-[5%] w-[40%] h-[40%] bg-primary/5 blur-[120px] rounded-full"></div>
            <div class="absolute top-[60%] -right-[5%] w-[30%] h-[30%] bg-secondary/5 blur-[100px] rounded-full"></div>
        </div>

        <div class="relative w-full max-w-md z-10">

            {{-- ── BRANDING ── --}}
            <div class="text-center mb-10">
                <div
                    class="inline-flex items-center justify-center p-4 rounded-xl bg-surface-container-high mb-6 shadow-2xl">
                    <span class="material-symbols-outlined text-primary text-4xl"
                        style="font-variation-settings:'FILL' 1;">architecture</span>
                </div>
                <h1 class="font-headline text-3xl font-extrabold tracking-tight text-on-surface mb-2">CMS</h1>
                <p class="text-on-surface-variant font-body text-sm tracking-wide">
                    High-Precision Industrial Construction Management
                </p>
            </div>

            {{-- ── LOGIN CARD ── --}}
            <div
                class="glass-panel border border-outline-variant/15 rounded-xl p-8 shadow-[0px_8px_24px_rgba(6,14,32,0.4)]">

                {{-- Session status (e.g. password reset link sent) --}}
                @if(session('status'))
                    <div
                        class="mb-5 p-4 rounded-xl bg-primary/10 border border-primary/20 text-primary text-sm font-medium flex items-center gap-3">
                        <span class="material-symbols-outlined text-base">info</span>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Email --}}
                    <div class="space-y-2">
                        <label
                            class="font-label text-xs uppercase tracking-widest text-on-surface-variant font-semibold"
                            for="email">Email Address</label>
                        <div class="relative group">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined
                                         text-on-surface-variant group-focus-within:text-primary transition-colors">
                                alternate_email
                            </span>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                                autocomplete="username" placeholder="name@company.com" class="w-full bg-surface-container-lowest border-0 rounded-xl py-3.5 pl-12 pr-4
                                          text-on-surface placeholder:text-outline/50
                                          ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary
                                          transition-all duration-200 outline-none
                                          {{ $errors->get('email') ? 'ring-error/60' : '' }}" />
                        </div>
                        @error('email')
                            <p class="text-xs text-error flex items-center gap-1.5 ml-1">
                                <span class="material-symbols-outlined text-sm">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label
                                class="font-label text-xs uppercase tracking-widest text-on-surface-variant font-semibold"
                                for="password">Password</label>
                            @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-xs text-primary font-medium hover:text-primary-container transition-colors">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>
                        <div class="relative group">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined
                                         text-on-surface-variant group-focus-within:text-primary transition-colors">
                                lock
                            </span>
                            <input id="password" name="password" type="password" required
                                autocomplete="current-password" placeholder="••••••••" class="w-full bg-surface-container-lowest border-0 rounded-xl py-3.5 pl-12 pr-4
                                          text-on-surface placeholder:text-outline/50
                                          ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary
                                          transition-all duration-200 outline-none
                                          {{ $errors->get('password') ? 'ring-error/60' : '' }}" />
                        </div>
                        @error('password')
                            <p class="text-xs text-error flex items-center gap-1.5 ml-1">
                                <span class="material-symbols-outlined text-sm">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Remember me --}}
                    <div class="flex items-center">
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <div class="relative">
                                <input id="remember_me" name="remember" type="checkbox" class="peer hidden" />
                                <div
                                    class="w-5 h-5 bg-surface-container-lowest border border-outline-variant/30
                                            rounded peer-checked:bg-primary peer-checked:border-primary transition-all">
                                </div>
                                <span class="absolute inset-0 flex items-center justify-center
                                             material-symbols-outlined text-on-primary text-xs
                                             opacity-0 peer-checked:opacity-100 transition-opacity"
                                    style="font-variation-settings:'wght' 700;">check</span>
                            </div>
                            <span class="text-sm text-on-surface-variant group-hover:text-on-surface transition-colors">
                                Remember me
                            </span>
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="w-full py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary
                                   font-headline font-bold text-sm tracking-widest uppercase rounded-xl
                                   shadow-lg shadow-primary/20 hover:shadow-primary/30
                                   transition-all active:scale-[0.98] duration-200">
                        Sign In
                    </button>

                </form>

                {{-- Divider --}}
                <!-- <div class="relative my-8 text-center">
                    <div class="absolute inset-0 flex items-center">
                        <span class="w-full border-t border-outline-variant/10"></span>
                    </div>
                    <span
                        class="relative px-4 text-[10px] uppercase tracking-[0.2em] font-label text-outline/60 bg-[#1e263a]">
                        Or continue with
                    </span>
                </div> -->

                {{-- Social buttons (UI only — wire up if needed) --}}
                <!-- <div class="grid grid-cols-2 gap-4">
                    <button type="button" class="flex items-center justify-center gap-3 py-3 px-4
                                   bg-surface-container-high rounded-xl ring-1 ring-outline-variant/15
                                   hover:bg-surface-container-highest transition-colors group">
                        <span class="material-symbols-outlined text-[#4285F4] text-lg">account_circle</span>
                        <span class="text-xs font-semibold text-on-surface-variant font-label">Google</span>
                    </button>
                    <button type="button" class="flex items-center justify-center gap-3 py-3 px-4
                                   bg-surface-container-high rounded-xl ring-1 ring-outline-variant/15
                                   hover:bg-surface-container-highest transition-colors group">
                        <span class="material-symbols-outlined text-primary text-lg">window</span>
                        <span class="text-xs font-semibold text-on-surface-variant font-label">Microsoft</span>
                    </button>
                </div> -->

            </div>

            {{-- Footer link --}}
            <!-- <div class="mt-8 text-center">
                <p class="text-on-surface-variant text-sm">
                    New to the platform?
                    <a href="#" class="text-primary font-semibold hover:underline ml-1">Request Access</a>
                </p>
            </div> -->

        </div>
    </main>

    {{-- ── FOOTER ── --}}
    <footer class="fixed bottom-0 w-full flex flex-col md:flex-row justify-between items-center
                   px-12 py-6 text-[10px] uppercase tracking-widest text-slate-500
                   opacity-80 hover:opacity-100 transition-opacity bg-transparent">
        <div class="mb-4 md:mb-0">© {{ date('Y') }} CMS. High-Precision Industrial Grade.</div>
        <div class="flex gap-8">
            <a class="hover:text-blue-400 transition-colors" href="#">Privacy Policy</a>
            <a class="hover:text-blue-400 transition-colors" href="#">Terms of Service</a>
            <a class="hover:text-blue-400 transition-colors" href="#">Security</a>
            <a class="hover:text-blue-400 transition-colors" href="#">Support</a>
        </div>
    </footer>

</body>

</html>