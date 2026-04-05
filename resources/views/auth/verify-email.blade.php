<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>CMS — Verify Email</title>
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
                        "primary": "#adc6ff", "on-surface-variant": "#c2c6d6",
                        "secondary": "#ffb95f", "surface": "#0b1326",
                        "error": "#ffb4ab", "on-primary": "#002e6a",
                        "outline": "#8c909f", "outline-variant": "#424754",
                        "on-background": "#dae2fd", "background": "#0b1326",
                        "on-surface": "#dae2fd", "surface-container": "#171f33",
                        "primary-container": "#4d8eff",
                        "surface-container-lowest": "#060e20",
                        "surface-container-high": "#222a3d",
                        "surface-container-highest": "#2d3449",
                    },
                    fontFamily: { "headline": ["Manrope"], "body": ["Inter"], "label": ["Inter"] },
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
        </div>
    </nav>

    {{-- ── MAIN ── --}}
    <main class="relative min-h-screen flex items-center justify-center p-6 tectonic-bg">

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
                        style="font-variation-settings:'FILL' 1;">mark_email_unread</span>
                </div>
                <h1 class="font-headline text-3xl font-extrabold tracking-tight text-on-surface mb-2">
                    Verify Your Email
                </h1>
                <p class="text-on-surface-variant font-body text-sm leading-relaxed max-w-sm mx-auto">
                    Thanks for signing up! Please verify your email address by clicking the link we just sent you.
                </p>
            </div>

            {{-- ── CARD ── --}}
            <div
                class="glass-panel border border-outline-variant/15 rounded-xl p-8 shadow-[0px_8px_24px_rgba(6,14,32,0.4)]">

                {{-- Success status --}}
                @if(session('status') == 'verification-link-sent')
                    <div class="mb-6 p-4 rounded-xl bg-primary/10 border border-primary/20
                                text-primary text-sm font-medium flex items-center gap-3">
                        <span class="material-symbols-outlined text-base"
                            style="font-variation-settings:'FILL' 1;">check_circle</span>
                        A new verification link has been sent to your email address.
                    </div>
                @endif

                {{-- Info box --}}
                <div class="p-4 rounded-xl bg-surface-container-highest border border-outline-variant/20
                            flex items-start gap-3 mb-6">
                    <span class="material-symbols-outlined text-secondary text-xl flex-shrink-0 mt-0.5"
                        style="font-variation-settings:'FILL' 1;">info</span>
                    <p class="text-sm text-on-surface-variant leading-relaxed">
                        Didn't receive the email? Check your spam folder or click below to resend.
                    </p>
                </div>

                {{-- Resend button --}}
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary
                                   font-headline font-bold text-sm tracking-widest uppercase rounded-xl
                                   shadow-lg shadow-primary/20 hover:shadow-primary/30
                                   transition-all active:scale-[0.98] duration-200
                                   flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base"
                            style="font-variation-settings:'FILL' 1;">send</span>
                        Resend Verification Email
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative my-6 text-center">
                    <div class="absolute inset-0 flex items-center">
                        <span class="w-full border-t border-outline-variant/10"></span>
                    </div>
                    <span class="relative px-4 text-[10px] uppercase tracking-[0.2em] font-label
                                 text-outline/60 bg-[#1e263a]">or</span>
                </div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-3.5 bg-surface-container-highest text-on-surface-variant
                                   font-headline font-bold text-sm tracking-widest uppercase rounded-xl
                                   border border-outline-variant/20 hover:bg-surface-container-high
                                   hover:text-on-surface transition-all active:scale-[0.98] duration-200
                                   flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base">logout</span>
                        Log Out
                    </button>
                </form>

            </div>
        </div>
    </main>

    {{-- ── FOOTER ── --}}
    <footer class="fixed bottom-0 w-full flex flex-col md:flex-row justify-between items-center
                   px-12 py-6 text-[10px] uppercase tracking-widest text-slate-500
                   opacity-80 hover:opacity-100 transition-opacity bg-transparent">
        <div class="mb-4 md:mb-0">© {{ date('Y') }} CMS. High-Precision Industrial Grade.</div>
        <div class="flex gap-8">
            <a class="hover:text-blue-400 transition-colors" href="#">Privacy Policy</a>
            <a class="hover:text-blue-400 transition-colors" href="#">Support</a>
        </div>
    </footer>

</body>

</html>