<x-guest-layout>
    @if (session('sucess') )
    <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
        {{ session('sucess') }}
    </div>
    @endif
    @if (session('failure') )
    <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
        {{ session('failure') }}
    </div>
    @endif
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        An OTP is sent to your {{isset($email) ? 'email' : 'phone number'}}. Please enter the OTP.
    </div>



    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.verify') }}" style="width: 100%;">
            @csrf
            <input type="hidden" name="{{isset($email) ? 'email':'phone'}}" value="{{isset($email) ? $email:$phone}}">
            <x-text-input id="otp" class="block mt-1 w-full"
                type="text"
                name="otp"
                required autocomplete="otp" />

            <x-input-error :messages="$errors->get('otp')" class="mt-2" />


            <div class="mt-4">
                <x-primary-button>
                    {{ __('Verify') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>