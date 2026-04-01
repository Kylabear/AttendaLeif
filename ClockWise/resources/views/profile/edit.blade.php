<x-app-layout>
    <x-slot name="header">
        <h2 class="bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 bg-clip-text text-xl font-semibold leading-tight text-transparent">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-10 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="glass-panel-strong mx-auto max-w-xl p-6 sm:p-8">
                <div>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="glass-panel-strong mx-auto max-w-xl p-6 sm:p-8">
                <div>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            @if(auth()->user()->role !== 'employee')
            <div class="glass-panel-strong mx-auto max-w-xl p-6 sm:p-8">
                <div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
