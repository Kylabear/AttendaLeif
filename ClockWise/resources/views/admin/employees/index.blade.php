<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-center gap-2 sm:flex-row sm:justify-center sm:gap-4">
            <h2 class="bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 bg-clip-text text-xl font-semibold leading-tight text-transparent">
                {{ __('Employee Management') }}
            </h2>
            <span class="rounded-full border border-indigo-200/80 bg-white/70 px-3 py-1 text-sm font-medium text-indigo-700 shadow-sm backdrop-blur-sm">
                {{ $employees->total() }} {{ __('Users') }}
            </span>
        </div>
    </x-slot>

    <div class="py-8 sm:py-10">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-xl border border-emerald-200/80 bg-emerald-50/90 px-4 py-3 text-center text-sm font-medium text-emerald-800 shadow-sm backdrop-blur-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-xl border border-red-200/80 bg-red-50/90 px-4 py-3 text-center text-sm font-medium text-red-800 shadow-sm backdrop-blur-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="glass-panel mb-4 p-4 sm:p-5">
                <form method="GET" action="{{ route('admin.employees.index') }}" class="grid grid-cols-1 gap-3 md:grid-cols-4">
                    <div class="md:col-span-2">
                        <label for="q" class="mb-1 block text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Search') }}</label>
                        <input
                            id="q"
                            name="q"
                            type="text"
                            value="{{ $search }}"
                            placeholder="{{ __('Name, email, or ID number') }}"
                            class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <div>
                        <label for="role" class="mb-1 block text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Role') }}</label>
                        <select id="role" name="role" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all" {{ $selectedRole === 'all' ? 'selected' : '' }}>{{ __('All Roles') }}</option>
                            <option value="admin" {{ $selectedRole === 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                            <option value="employee" {{ $selectedRole === 'employee' ? 'selected' : '' }}>{{ __('Employee') }}</option>
                        </select>
                    </div>

                    <div class="flex flex-wrap items-end justify-center gap-2 md:justify-start">
                        <button type="submit" class="btn-glass-primary text-xs font-bold uppercase tracking-wide">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.employees.index') }}" class="btn-glass-secondary text-xs font-bold uppercase tracking-wide">
                            {{ __('Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <div class="mb-3 text-center text-sm text-gray-600">
                {{ __('Showing') }} {{ $employees->firstItem() ?? 0 }}-{{ $employees->lastItem() ?? 0 }} {{ __('of') }} {{ $employees->total() }} {{ __('users') }}
            </div>

            <div class="glass-panel overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/40">
                        <thead class="bg-white/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('ID Number') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Email') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Role') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Joined') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/30 bg-white/30">
                            @forelse ($employees as $employee)
                                <tr class="hover:bg-white/50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $employee->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $employee->id_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $employee->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $employee->role === 'admin' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ ucfirst($employee->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ optional($employee->created_at)->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="{{ route('admin.employees.update-role', $employee) }}" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role" class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <option value="employee" {{ $employee->role === 'employee' ? 'selected' : '' }}>{{ __('Employee') }}</option>
                                                    <option value="admin" {{ $employee->role === 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                                </select>
                                                <button type="submit" class="btn-glass-primary px-3 py-1.5 text-xs">
                                                    {{ __('Save') }}
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" onsubmit="return confirm('Remove this user account?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-glass-danger px-3 py-1.5 text-xs">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">{{ __('No users found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
