<x-app-layout>
    <x-slot name="header">
        <h2 class="bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 bg-clip-text text-xl font-semibold leading-tight text-transparent">
            {{ __('Schedule Management') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-xl border border-emerald-200/80 bg-emerald-50/90 px-4 py-3 text-center text-sm font-medium text-emerald-800 shadow-sm backdrop-blur-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="glass-panel-strong p-5 sm:p-6">
                <h3 class="mb-4 text-center text-sm font-semibold text-gray-800">{{ __('Add / Update Schedule') }}</h3>
                <div class="mx-auto mb-4 h-1 w-20 rounded-full bg-gradient-to-r from-indigo-400 via-violet-500 to-fuchsia-500"></div>
                <form method="POST" action="{{ route('admin.schedules.store') }}" class="grid grid-cols-1 gap-3 md:grid-cols-5">
                    @csrf
                    <select name="user_id" class="input-glass md:col-span-1" required>
                        <option value="">{{ __('Select employee') }}</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->id_number }})</option>
                        @endforeach
                    </select>
                    <input type="date" name="schedule_date" class="input-glass" required>
                    <select name="status" class="input-glass" required>
                        <option value="workday">{{ __('Workday') }}</option>
                        <option value="day_off">{{ __('Day Off') }}</option>
                        <option value="on_leave">{{ __('On Leave') }}</option>
                    </select>
                    <input type="text" name="note" placeholder="{{ __('Optional note') }}" class="input-glass">
                    <button type="submit" class="btn-glass-primary md:self-end">{{ __('Save') }}</button>
                </form>
            </div>

            <div class="glass-panel p-5 sm:p-6">
                <form method="GET" action="{{ route('admin.schedules.index') }}" class="grid grid-cols-1 gap-3 md:grid-cols-5">
                    <select name="user_id" class="input-glass md:col-span-1">
                        <option value="">{{ __('All employees') }}</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ (string) $selectedUserId === (string) $employee->id ? 'selected' : '' }}>{{ $employee->name }} ({{ $employee->id_number }})</option>
                        @endforeach
                    </select>
                    <input type="date" name="from" value="{{ $from }}" class="input-glass">
                    <input type="date" name="to" value="{{ $to }}" class="input-glass">
                    <button type="submit" class="btn-glass-primary">{{ __('Filter') }}</button>
                    <a href="{{ route('admin.schedules.index') }}" class="btn-glass-secondary inline-flex items-center justify-center">{{ __('Reset') }}</a>
                </form>
            </div>

            <div class="glass-panel overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/40">
                        <thead class="bg-white/50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Date') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Employee') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Status') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Note') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/30 bg-white/25">
                            @forelse ($schedules as $schedule)
                                <tr class="hover:bg-white/45">
                                    <td class="px-5 py-3 text-sm text-gray-800">{{ $schedule->schedule_date->format('M d, Y') }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $schedule->user->name }} ({{ $schedule->user->id_number }})</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $schedule->status)) }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $schedule->note ?? '-' }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">
                                        <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" onsubmit="return confirm('Delete this schedule entry?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-glass-danger px-3 py-1.5 text-xs">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500">{{ __('No schedule entries found.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-center">{{ $schedules->links() }}</div>
        </div>
    </div>
</x-app-layout>
