<x-app-layout>
    <x-slot name="header">
        <h2 class="bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 bg-clip-text text-xl font-semibold leading-tight text-transparent">
            {{ __('Attendance Trends') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            <div class="glass-panel-strong flex flex-col gap-4 p-4 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
                <form method="GET" action="{{ route('admin.attendance.trends') }}" class="flex flex-wrap items-center justify-center gap-2 sm:justify-start">
                    <input type="date" name="from" value="{{ $from }}" class="input-glass w-auto">
                    <input type="date" name="to" value="{{ $to }}" class="input-glass w-auto">
                    <button type="submit" class="btn-glass-primary">{{ __('Apply') }}</button>
                </form>
                <a href="{{ route('admin.attendance.trends.export', ['from' => $from, 'to' => $to]) }}" class="btn-glass-secondary text-center">
                    {{ __('Export CSV') }}
                </a>
            </div>

            <div class="glass-panel overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/40">
                        <thead class="bg-white/50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Date') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Present') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Absent') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('On Leave') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Day Off') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/30 bg-white/25">
                            @forelse ($trendRows as $row)
                                <tr class="hover:bg-white/45">
                                    <td class="px-5 py-3 text-sm text-gray-800">{{ \Carbon\Carbon::parse($row['date'])->format('M d, Y') }}</td>
                                    <td class="px-5 py-3 text-sm font-semibold text-emerald-700">{{ $row['counts']['present'] }}</td>
                                    <td class="px-5 py-3 text-sm font-semibold text-red-700">{{ $row['counts']['absent'] }}</td>
                                    <td class="px-5 py-3 text-sm font-semibold text-amber-700">{{ $row['counts']['on_leave'] }}</td>
                                    <td class="px-5 py-3 text-sm font-semibold text-indigo-700">{{ $row['counts']['day_off'] }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-800">{{ $row['counts']['total'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-8 text-center text-sm text-gray-500">{{ __('No trend data available.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
