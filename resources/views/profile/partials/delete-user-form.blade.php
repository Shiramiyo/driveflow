<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-white">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm leading-6 text-white/65">
            {{ __('This removes your saved trips and account access from the demo application. Enter your password to confirm the deletion.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-5 p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-white">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="text-sm leading-6 text-white/65">
                {{ __('Once deleted, the action cannot be reversed. Enter your password to continue.') }}
            </p>

            <div>
                <x-input-label for="password" value="{{ __('Password') }}" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="{{ __('Password') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex flex-wrap justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button>
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
