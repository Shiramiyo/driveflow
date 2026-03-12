<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm uppercase tracking-[0.18em] text-lime-200">Profile</p>
            <h1 class="mt-2 text-3xl font-semibold text-white">Manage your account details.</h1>
        </div>
    </x-slot>

    <section class="page-width space-y-6 pt-10">
        <div class="shell-panel p-6 sm:p-8">
            <div class="max-w-3xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="shell-panel p-6 sm:p-8">
            <div class="max-w-3xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="shell-panel p-6 sm:p-8">
            <div class="max-w-3xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </section>
</x-app-layout>
