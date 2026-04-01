<x-app-layout>
    <x-slot name="header">
        <h2 class="bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 bg-clip-text text-xl font-semibold leading-tight text-transparent">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            <div class="glass-panel px-4 py-3 text-center text-sm text-gray-700">
                <strong class="text-gray-900">{{ __('Schedules') }}:</strong>
                {{ __('Staff on day off or on leave are counted automatically. They do not clock in/out—set their status under Manage Schedules so the daily report stays accurate.') }}
            </div>

            <div class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-5">
                <div class="glass-panel p-4 text-center">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Total') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-800">{{ $summary['total'] }}</p>
                </div>
                <div class="glass-panel p-4 text-center">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Present') }}</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $summary['present'] }}</p>
                </div>
                <div class="glass-panel p-4 text-center">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Absent') }}</p>
                    <p class="mt-2 text-3xl font-bold text-red-600">{{ $summary['absent'] }}</p>
                </div>
                <div class="glass-panel p-4 text-center">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('On Leave') }}</p>
                    <p class="mt-2 text-3xl font-bold text-amber-600">{{ $summary['on_leave'] }}</p>
                </div>
                <div class="glass-panel col-span-2 p-4 text-center md:col-span-1">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Day Off') }}</p>
                    <p class="mt-2 text-3xl font-bold text-indigo-600">{{ $summary['day_off'] }}</p>
                </div>
            </div>

            <div class="glass-panel-strong space-y-5 p-6 text-center sm:p-8">
                <p class="text-sm text-gray-700">
                    {{ __('Open shifts (clocked in but not out)') }}:
                    <span class="rounded-lg bg-white/60 px-2 py-0.5 font-bold text-indigo-700 shadow-sm">{{ $openShifts }}</span>
                </p>
                <div class="mx-auto h-1 w-32 max-w-full rounded-full bg-gradient-to-r from-indigo-400 via-violet-500 to-fuchsia-500 opacity-90 shadow-sm"></div>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('admin.attendance.daily', ['date' => $date]) }}" class="btn-glass-primary">{{ __('View Daily Attendance') }}</a>
                    <a href="{{ route('admin.attendance.trends') }}" class="btn-glass-secondary">{{ __('View Trends') }}</a>
                    <a href="{{ route('admin.schedules.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/30 bg-gradient-to-r from-emerald-500 to-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/25 transition hover:scale-[1.02] hover:shadow-emerald-500/35">{{ __('Manage Schedules') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
