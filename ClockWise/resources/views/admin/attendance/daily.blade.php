<x-app-layout>
    <x-slot name="header">
        <h2 class="bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 bg-clip-text text-xl font-semibold leading-tight text-transparent">
            {{ __('Daily Attendance') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            <div class="glass-panel-strong flex flex-col gap-4 p-4 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
                <form method="GET" action="{{ route('admin.attendance.daily') }}" class="flex flex-wrap items-center justify-center gap-2 sm:justify-start">
                    <input type="date" name="date" value="{{ $date }}" class="input-glass w-auto">
                    <button type="submit" class="btn-glass-primary">{{ __('Load') }}</button>
                </form>
                <a href="{{ route('admin.attendance.daily.export', ['date' => $date]) }}" class="btn-glass-secondary text-center sm:text-start">
                    {{ __('Export CSV') }}
                </a>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-5">
                <div class="glass-panel p-4 text-center">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Total') }}</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">{{ $summary['total'] }}</p>
                </div>
                <div class="glass-panel p-4 text-center">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Present') }}</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-600">{{ $summary['present'] }}</p>
                </div>
                <div class="glass-panel p-4 text-center">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Absent') }}</p>
                    <p class="mt-2 text-2xl font-bold text-red-600">{{ $summary['absent'] }}</p>
                </div>
                <div class="glass-panel p-4 text-center">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('On Leave') }}</p>
                    <p class="mt-2 text-2xl font-bold text-amber-600">{{ $summary['on_leave'] }}</p>
                </div>
                <div class="glass-panel col-span-2 p-4 text-center md:col-span-1">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Day Off') }}</p>
                    <p class="mt-2 text-2xl font-bold text-indigo-600">{{ $summary['day_off'] }}</p>
                </div>
            </div>

            <div class="glass-panel overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/40">
                        <thead class="bg-white/50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Employee') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('ID') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Status') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Clock In') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Clock Out') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Note') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/30 bg-white/25">
                            @forelse ($rows as $row)
                                <tr class="hover:bg-white/45">
                                    <td class="px-5 py-3 text-sm text-gray-800">{{ $row['employee']->name }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $row['employee']->id_number }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $row['status'])) }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ optional($row['clock_in_at'])->format('h:i A') ?? '-' }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ optional($row['clock_out_at'])->format('h:i A') ?? '-' }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $row['schedule_note'] ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-8 text-center text-sm text-gray-500">{{ __('No employees found.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
