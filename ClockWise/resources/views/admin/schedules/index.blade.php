<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Schedule Management') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">{{ __('Add / Update Schedule') }}</h3>
                <form method="POST" action="{{ route('admin.schedules.store') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    @csrf
                    <select name="user_id" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">{{ __('Select employee') }}</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->id_number }})</option>
                        @endforeach
                    </select>
                    <input type="date" name="schedule_date" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <select name="status" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="workday">{{ __('Workday') }}</option>
                        <option value="day_off">{{ __('Day Off') }}</option>
                        <option value="on_leave">{{ __('On Leave') }}</option>
                    </select>
                    <input type="text" name="note" placeholder="{{ __('Optional note') }}" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">{{ __('Save') }}</button>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-5">
                <form method="GET" action="{{ route('admin.schedules.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <select name="user_id" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('All employees') }}</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ (string) $selectedUserId === (string) $employee->id ? 'selected' : '' }}>{{ $employee->name }} ({{ $employee->id_number }})</option>
                        @endforeach
                    </select>
                    <input type="date" name="from" value="{{ $from }}" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <input type="date" name="to" value="{{ $to }}" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="submit" class="rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">{{ __('Filter') }}</button>
                    <a href="{{ route('admin.schedules.index') }}" class="inline-flex items-center justify-center rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200">{{ __('Reset') }}</a>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Date') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Employee') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Status') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Note') }}</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider text-gray-500">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($schedules as $schedule)
                                <tr>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $schedule->schedule_date->format('M d, Y') }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $schedule->user->name }} ({{ $schedule->user->id_number }})</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $schedule->status)) }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">{{ $schedule->note ?? '-' }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700">
                                        <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" onsubmit="return confirm('Delete this schedule entry?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-5 py-6 text-center text-sm text-gray-500">{{ __('No schedule entries found.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>{{ $schedules->links() }}</div>
        </div>
    </div>
</x-app-layout>
