<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Attendance Trends') }}</h2>
            <div class="flex flex-wrap items-center gap-2">
                <form method="GET" action="{{ route('admin.attendance.trends') }}" class="flex flex-wrap items-center gap-2">
                    <input type="date" name="from" value="{{ $from }}" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <input type="date" name="to" value="{{ $to }}" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">{{ __('Apply') }}</button>
                </form>

                <a href="{{ route('admin.attendance.trends.export', ['from' => $from, 'to' => $to]) }}" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                    {{ __('Export CSV') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Date') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Present') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Absent') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('On Leave') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Day Off') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($trendRows as $row)
                                <tr>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($row['date'])->format('M d, Y') }}</td>
                                    <td class="px-5 py-3 text-sm text-emerald-700 font-semibold">{{ $row['counts']['present'] }}</td>
                                    <td class="px-5 py-3 text-sm text-red-700 font-semibold">{{ $row['counts']['absent'] }}</td>
                                    <td class="px-5 py-3 text-sm text-amber-700 font-semibold">{{ $row['counts']['on_leave'] }}</td>
                                    <td class="px-5 py-3 text-sm text-indigo-700 font-semibold">{{ $row['counts']['day_off'] }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $row['counts']['total'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-6 text-center text-sm text-gray-500">{{ __('No trend data available.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
