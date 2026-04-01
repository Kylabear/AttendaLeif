<x-app-layout>
    <x-slot name="header">
        <h2 class="bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 bg-clip-text text-xl font-semibold leading-tight text-transparent">
            {{ __('My Attendance') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-xl border border-emerald-200/80 bg-emerald-50/90 px-4 py-3 text-center text-sm font-medium text-emerald-800 shadow-sm backdrop-blur-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-xl border border-red-200/80 bg-red-50/90 px-4 py-3 text-center text-sm font-medium text-red-800 shadow-sm backdrop-blur-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="glass-panel p-6 text-center">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Today Status') }}</p>
                    <p class="mt-2 bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-2xl font-bold text-transparent">{{ ucfirst(str_replace('_', ' ', $todayStatus)) }}</p>
                </div>
                <div class="glass-panel p-6 text-center">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Clock In') }}</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-700">{{ optional($attendance?->clock_in_at)->format('h:i A') ?? '-' }}</p>
                </div>
                <div class="glass-panel p-6 text-center">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Clock Out') }}</p>
                    <p class="mt-2 text-2xl font-bold text-indigo-700">{{ optional($attendance?->clock_out_at)->format('h:i A') ?? '-' }}</p>
                </div>
            </div>

            <div class="glass-panel-strong space-y-4 p-6 sm:p-8">
                <div class="text-center">
                    <h3 class="text-base font-semibold text-gray-800">{{ __('Attendance Actions') }}</h3>
                    <div class="mx-auto mt-2 h-1 w-24 max-w-full rounded-full bg-gradient-to-r from-indigo-400 via-violet-500 to-fuchsia-500 shadow-sm shadow-indigo-500/30"></div>
                    <p class="mt-3 text-xs text-gray-500">{{ __('Use these buttons to record your exact time on scheduled workdays.') }}</p>
                </div>

                @if (in_array($todayStatus, ['day_off', 'on_leave'], true))
                    <div class="rounded-xl border border-indigo-200/80 bg-indigo-50/90 px-5 py-4 text-center shadow-sm backdrop-blur-sm">
                        <p class="text-sm font-semibold text-indigo-950">{{ __('No clock-in or clock-out needed today') }}</p>
                        <p class="mt-2 text-sm text-indigo-900/90">
                            {{ __('Your schedule for today is :status. The company already sees you under that category in the daily attendance report—no action is required from you.', ['status' => str_replace('_', ' ', $todayStatus)]) }}
                        </p>
                        <p class="mt-2 text-xs text-indigo-800/80">
                            {{ __('If this looks wrong, ask HR or your admin to update your schedule for this date.') }}
                        </p>
                    </div>
                @else
                    @php
                        $canClockIn = $attendance?->clock_in_at === null;
                        $canClockOut = $attendance?->clock_in_at !== null && $attendance?->clock_out_at === null;
                    @endphp

                    <div
                        class="relative"
                        x-data="attendanceConfirm({
                            in: @js(__('Are you sure you want to clock in now?')),
                            out: @js(__('Are you sure you want to clock out now?')),
                            clockedInLabel: @js(__('Clocked In')),
                        })"
                        @keydown.escape.window="cancel()"
                    >
                        {{-- Confirmation snackbar --}}
                        <div
                            class="pointer-events-none fixed inset-x-4 bottom-6 z-[100] flex justify-center sm:inset-x-0"
                            x-show="showConfirm"
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="translate-y-8 opacity-0"
                            x-transition:enter-end="translate-y-0 opacity-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="translate-y-0 opacity-100"
                            x-transition:leave-end="translate-y-4 opacity-0"
                        >
                            <div
                                class="pointer-events-auto flex w-full max-w-md flex-col gap-4 rounded-2xl border border-indigo-200/60 bg-white/90 p-4 shadow-2xl shadow-indigo-500/20 backdrop-blur-xl ring-1 ring-white/70 sm:flex-row sm:items-center sm:justify-between sm:p-5"
                                role="dialog"
                                aria-modal="true"
                                :aria-label="message"
                            >
                                <p class="text-center text-sm font-medium text-gray-800 sm:text-left" x-text="message"></p>
                                <div class="flex shrink-0 items-center justify-center gap-2 sm:justify-end">
                                    <button
                                        type="button"
                                        class="rounded-xl border border-gray-200 bg-white/80 px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
                                        @click="cancel()"
                                    >
                                        {{ __('Cancel') }}
                                    </button>
                                    <button
                                        type="button"
                                        class="btn-glass-primary px-4 py-2 text-sm"
                                        @click="confirm()"
                                    >
                                        {{ __('Yes, continue') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <form
                                method="POST"
                                action="{{ route('attendance.clock-in') }}"
                                data-clock-action="in"
                                @submit.prevent="ask('in', $el)"
                            >
                                @csrf
                                <button
                                    type="submit"
                                    class="inline-flex min-w-[140px] items-center justify-center rounded-xl border-2 border-emerald-400/50 bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 transition hover:scale-[1.02] hover:shadow-emerald-500/40 disabled:cursor-not-allowed disabled:scale-100 disabled:border-emerald-200 disabled:bg-gradient-to-r disabled:from-emerald-100 disabled:to-teal-100 disabled:text-emerald-900 disabled:shadow-md disabled:opacity-95"
                                    {{ $canClockIn ? '' : 'disabled' }}
                                >
                                    {{ $canClockIn ? __('Clock In') : __('Clocked In') }}
                                </button>
                            </form>

                            <form
                                method="POST"
                                action="{{ route('attendance.clock-out') }}"
                                data-clock-action="out"
                                @submit.prevent="ask('out', $el)"
                            >
                                @csrf
                                <button
                                    type="submit"
                                    class="btn-glass-secondary min-w-[140px] py-2.5 font-semibold disabled:cursor-not-allowed disabled:opacity-50"
                                    {{ $canClockOut ? '' : 'disabled' }}
                                >
                                    {{ __('Clock Out') }}
                                </button>
                            </form>
                        </div>
                        <p class="mt-3 text-center text-xs text-gray-500">
                            {{ __('When you tap Clock In or Clock Out, a confirmation bar appears at the bottom. Tap Yes, continue to save your time.') }}
                        </p>
                    </div>

                    @if ($attendance?->clock_out_at !== null)
                        <p class="text-center text-sm font-medium text-emerald-700">{{ __('You have completed attendance for today.') }}</p>
                    @elseif ($attendance?->clock_in_at !== null)
                        <p class="text-center text-sm text-emerald-700">{{ __('You are clocked in. Don\'t forget to clock out before leaving.') }}</p>
                    @else
                        <p class="text-center text-sm text-gray-600">{{ __('You are not clocked in yet.') }}</p>
                    @endif
                @endif
            </div>

            <div class="glass-panel overflow-hidden">
                <div class="border-b border-white/50 bg-white/30 px-5 py-4 text-center backdrop-blur-sm">
                    <h3 class="text-sm font-semibold text-gray-800">{{ __('Recent Attendance') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/40">
                        <thead class="bg-white/40">
                            <tr>
                                <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Date') }}</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Clock In') }}</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Clock Out') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/30">
                            @forelse ($recentAttendances as $item)
                                <tr class="hover:bg-white/40">
                                    <td class="px-5 py-3 text-center text-sm text-gray-800">{{ $item->attendance_date->format('M d, Y') }}</td>
                                    <td class="px-5 py-3 text-center text-sm text-gray-700">{{ optional($item->clock_in_at)->format('h:i A') ?? '-' }}</td>
                                    <td class="px-5 py-3 text-center text-sm text-gray-700">{{ optional($item->clock_out_at)->format('h:i A') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-8 text-center text-sm text-gray-500">{{ __('No attendance records yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
