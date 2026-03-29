<section class="mt-8 pt-8 border-t border-[#424754]/15 space-y-4">
    <h4 class="text-xs font-bold text-[#c2c6d6] uppercase tracking-widest">Change Password</h4>
    <p class="text-xs text-[#8c909f]">Use a long, random password to keep your account secure.</p>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="space-y-1.5">
            <label for="update_password_current_password"
                   class="text-xs font-semibold text-[#c2c6d6] ml-1 block">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password"
                   autocomplete="current-password"
                   class="w-full bg-[#131b2e] border border-[#424754]/30 rounded-xl px-4 py-3
                          text-[#dae2fd] placeholder-[#8c909f]/50 outline-none
                          focus:ring-2 focus:ring-[#4d8eff] transition-all" />
            @if ($errors->updatePassword->get('current_password'))
            <p class="text-xs text-[#ffb4ab] ml-1">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div class="space-y-1.5">
            <label for="update_password_password"
                   class="text-xs font-semibold text-[#c2c6d6] ml-1 block">New Password</label>
            <input id="update_password_password" name="password" type="password"
                   autocomplete="new-password"
                   class="w-full bg-[#131b2e] border border-[#424754]/30 rounded-xl px-4 py-3
                          text-[#dae2fd] placeholder-[#8c909f]/50 outline-none
                          focus:ring-2 focus:ring-[#4d8eff] transition-all" />
            @if ($errors->updatePassword->get('password'))
            <p class="text-xs text-[#ffb4ab] ml-1">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div class="space-y-1.5">
            <label for="update_password_password_confirmation"
                   class="text-xs font-semibold text-[#c2c6d6] ml-1 block">Confirm New Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                   autocomplete="new-password"
                   class="w-full bg-[#131b2e] border border-[#424754]/30 rounded-xl px-4 py-3
                          text-[#dae2fd] placeholder-[#8c909f]/50 outline-none
                          focus:ring-2 focus:ring-[#4d8eff] transition-all" />
            @if ($errors->updatePassword->get('password_confirmation'))
            <p class="text-xs text-[#ffb4ab] ml-1">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="flex-1 py-3 px-4 rounded-xl font-headline text-sm font-bold text-[#002e6a]
                           bg-gradient-to-br from-[#adc6ff] to-[#4d8eff]
                           shadow-lg shadow-[#4d8eff]/20 hover:scale-[1.02] active:scale-95 transition-all">
                Update Password
            </button>
            @if(session('status') === 'password-updated')
            <span class="text-xs text-[#adc6ff] font-semibold">✓ Saved!</span>
            @endif
        </div>
    </form>
</section>
