<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Attendance') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs uppercase tracking-wide text-gray-500">{{ __('Today Status') }}</p>
                    <p class="mt-2 text-xl font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $todayStatus)) }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs uppercase tracking-wide text-gray-500">{{ __('Clock In') }}</p>
                    <p class="mt-2 text-xl font-semibold text-gray-800">{{ optional($attendance?->clock_in_at)->format('h:i A') ?? '-' }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs uppercase tracking-wide text-gray-500">{{ __('Clock Out') }}</p>
                    <p class="mt-2 text-xl font-semibold text-gray-800">{{ optional($attendance?->clock_out_at)->format('h:i A') ?? '-' }}</p>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-5 space-y-3">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700">{{ __('Attendance Actions') }}</h3>
                    <p class="text-xs text-gray-500">{{ __('Use these buttons to record your exact time today.') }}</p>
                </div>

                @if (in_array($todayStatus, ['day_off', 'on_leave'], true))
                    <div class="rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800">
                        {{ __('Attendance is disabled because your schedule today is :status.', ['status' => str_replace('_', ' ', $todayStatus)]) }}
                    </div>
                @else
                    @php
                        $canClockIn = $attendance?->clock_in_at === null;
                        $canClockOut = $attendance?->clock_in_at !== null && $attendance?->clock_out_at === null;
                    @endphp

                    <div class="flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('attendance.clock-in') }}" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerText = 'Clocked In';">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-md border border-emerald-700 bg-emerald-700 px-4 py-2 text-sm font-semibold text-white disabled:cursor-not-allowed disabled:border-gray-300 disabled:bg-gray-200 disabled:text-gray-500"
                                {{ $canClockIn ? '' : 'disabled' }}
                            >
                                {{ $canClockIn ? __('Clock In') : __('Clocked In') }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('attendance.clock-out') }}">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-md border border-gray-800 bg-gray-800 px-4 py-2 text-sm font-semibold text-white disabled:cursor-not-allowed disabled:border-gray-300 disabled:bg-gray-200 disabled:text-gray-500"
                                {{ $canClockOut ? '' : 'disabled' }}
                            >
                                {{ __('Clock Out') }}
                            </button>
                        </form>
                    </div>

                    @if ($attendance?->clock_out_at !== null)
                        <p class="text-sm text-emerald-700">{{ __('You have completed attendance for today.') }}</p>
                    @elseif ($attendance?->clock_in_at !== null)
                        <p class="text-sm text-emerald-700">{{ __('You are clocked in. Don\'t forget to clock out before leaving.') }}</p>
                    @else
                        <p class="text-sm text-gray-600">{{ __('You are not clocked in yet.') }}</p>
                    @endif
                @endif
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">{{ __('Recent Attendance') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Date') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Clock In') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Clock Out') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($recentAttendances as $item)
                                <tr>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $item->attendance_date->format('M d, Y') }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ optional($item->clock_in_at)->format('h:i A') ?? '-' }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ optional($item->clock_out_at)->format('h:i A') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-6 text-center text-sm text-gray-500">{{ __('No attendance records yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
