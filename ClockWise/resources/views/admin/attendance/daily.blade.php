<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Daily Attendance') }}</h2>
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('admin.attendance.daily') }}" class="flex items-center gap-2">
                    <input type="date" name="date" value="{{ $date }}" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">{{ __('Load') }}</button>
                </form>

                <a href="{{ route('admin.attendance.daily.export', ['date' => $date]) }}" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                    {{ __('Export CSV') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-4"><p class="text-xs text-gray-500 uppercase">{{ __('Total') }}</p><p class="mt-1 text-xl font-semibold">{{ $summary['total'] }}</p></div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4"><p class="text-xs text-gray-500 uppercase">{{ __('Present') }}</p><p class="mt-1 text-xl font-semibold text-emerald-600">{{ $summary['present'] }}</p></div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4"><p class="text-xs text-gray-500 uppercase">{{ __('Absent') }}</p><p class="mt-1 text-xl font-semibold text-red-600">{{ $summary['absent'] }}</p></div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4"><p class="text-xs text-gray-500 uppercase">{{ __('On Leave') }}</p><p class="mt-1 text-xl font-semibold text-amber-600">{{ $summary['on_leave'] }}</p></div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4"><p class="text-xs text-gray-500 uppercase">{{ __('Day Off') }}</p><p class="mt-1 text-xl font-semibold text-indigo-600">{{ $summary['day_off'] }}</p></div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Employee') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('ID') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Status') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Clock In') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Clock Out') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Note') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($rows as $row)
                                <tr>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $row['employee']->name }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $row['employee']->id_number }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $row['status'])) }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ optional($row['clock_in_at'])->format('h:i A') ?? '-' }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ optional($row['clock_out_at'])->format('h:i A') ?? '-' }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $row['schedule_note'] ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-6 text-center text-sm text-gray-500">{{ __('No employees found.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
