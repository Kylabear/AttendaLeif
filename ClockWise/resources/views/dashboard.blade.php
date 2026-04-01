<x-app-layout>
    <x-slot name="header">
        <h2 class="bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 bg-clip-text text-xl font-semibold leading-tight text-transparent">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10 sm:py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="glass-panel-strong overflow-hidden sm:rounded-2xl">
                <div class="p-8 text-center text-gray-900">
                    <p class="text-lg font-semibold text-gray-800">{{ __("You're logged in!") }}</p>
                    <p class="mt-3 text-sm text-gray-600">
                        {{ __('ID Number') }}: <span class="font-medium text-indigo-700">{{ auth()->user()->id_number }}</span>
                        <span class="mx-2 text-gray-300">|</span>
                        {{ __('Role') }}: <span class="font-medium text-violet-700">{{ ucfirst(auth()->user()->role) }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
