<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-500">{{ __('Total') }}</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-800">{{ $summary['total'] }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-500">{{ __('Present') }}</p>
                    <p class="mt-2 text-2xl font-semibold text-emerald-600">{{ $summary['present'] }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-500">{{ __('Absent') }}</p>
                    <p class="mt-2 text-2xl font-semibold text-red-600">{{ $summary['absent'] }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-500">{{ __('On Leave') }}</p>
                    <p class="mt-2 text-2xl font-semibold text-amber-600">{{ $summary['on_leave'] }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-500">{{ __('Day Off') }}</p>
                    <p class="mt-2 text-2xl font-semibold text-indigo-600">{{ $summary['day_off'] }}</p>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-5">
                <p class="text-sm text-gray-700">
                    {{ __('Open shifts (clocked in but not out)') }}:
                    <span class="font-semibold">{{ $openShifts }}</span>
                </p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('admin.attendance.daily', ['date' => $date]) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">{{ __('View Daily Attendance') }}</a>
                    <a href="{{ route('admin.attendance.trends') }}" class="inline-flex items-center rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">{{ __('View Trends') }}</a>
                    <a href="{{ route('admin.schedules.index') }}" class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">{{ __('Manage Schedules') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
