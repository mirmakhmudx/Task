<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Please enter the SMS code sent to your phone.
    </div>

    @if($errors->any())
        <div class="mb-4 text-sm text-red-600">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.phone.verify') }}">
        @csrf

        <div>
            <x-input-label for="token" value="SMS Code" />
            <x-text-input id="token" name="token" type="text"
                          class="mt-1 block w-full"
                          required autofocus
                          placeholder="12345" />
            <x-input-error :messages="$errors->get('token')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>Verify</x-primary-button>
        </div>
    </form>
</x-guest-layout>
