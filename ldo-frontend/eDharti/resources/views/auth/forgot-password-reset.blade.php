<x-guest-layout>
    <form method="POST" action="{{ route('forgotPassword.store') }}">
        @csrf
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if (session('failure'))
        <div class="alert alert-danger">
            {{ session('failure') }}
        </div>
        @endif

        <input type="hidden" name="userId" value="{{$userId}}">

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="new_password" :value="__('New Password')" />
            <x-text-input id="new_password" class="block mt-1 w-full" type="password" name="new_password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('new_password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="new_password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="new_password_confirmation" class="block mt-1 w-full"
                type="password"
                name="new_password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>