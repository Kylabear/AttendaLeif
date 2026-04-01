<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employee Management') }}
            </h2>
            <span class="text-sm px-3 py-1 rounded-full bg-indigo-100 text-indigo-700">
                {{ $employees->total() }} {{ __('Users') }}
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-4 rounded-lg border border-gray-200 bg-white p-4">
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

                    <div class="flex items-end gap-2">
                        <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white hover:bg-indigo-700">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-gray-700 hover:bg-gray-200">
                            {{ __('Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <div class="mb-3 text-sm text-gray-600">
                {{ __('Showing') }} {{ $employees->firstItem() ?? 0 }}-{{ $employees->lastItem() ?? 0 }} {{ __('of') }} {{ $employees->total() }} {{ __('users') }}
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ID Number') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Email') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Role') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Joined') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($employees as $employee)
                                <tr>
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
                                                <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700">
                                                    {{ __('Save') }}
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" onsubmit="return confirm('Remove this user account?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">
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
