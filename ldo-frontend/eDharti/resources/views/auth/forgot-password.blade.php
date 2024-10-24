<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address or phone no.  and we will send you otp that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.otp') }}">
        @csrf

        <!-- Email Address -->
        <div style="margin-bottom: 30px;">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <hr>
        <div style="color: #fff;
    margin-left: 36%;
    background: #1f2937;
    margin-top: -15px;
    width: 30px;
    padding: 5px;">OR</div>
        <div>
            <x-input-label for="phone" :value="__('Phone No.')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" pattern="[0-9]{8,12}" autofocus />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Get OTP') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>