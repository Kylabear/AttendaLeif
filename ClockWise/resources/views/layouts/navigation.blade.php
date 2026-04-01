<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-white/40 bg-white/55 shadow-sm shadow-black/5 backdrop-blur-xl">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @if (Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.employees.index')" :active="request()->routeIs('admin.employees.*')">
                            {{ __('Employees') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.attendance.daily')" :active="request()->routeIs('admin.attendance.*')">
                            {{ __('Attendance') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.schedules.index')" :active="request()->routeIs('admin.schedules.*')">
                            {{ __('Schedules') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button type="button" class="inline-flex items-center rounded-xl border border-white/50 bg-white/50 px-3 py-2 text-sm font-medium text-gray-700 shadow-sm backdrop-blur-md transition duration-150 ease-in-out hover:border-indigo-200 hover:bg-white/80 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-300/50">
                            @php $user = Auth::user(); @endphp
                            @if ($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="Profile Photo" class="w-8 h-8 rounded-full object-cover mr-2">
                            @else
                                <span class="inline-block w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 mr-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </span>
                            @endif
                            <div>
                                {{ $user->name }}
                                <span class="text-xs text-gray-400">({{ ucfirst($user->role) }})</span>
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button type="button" @click="open = ! open" class="inline-flex items-center justify-center rounded-xl border border-white/40 bg-white/40 p-2 text-gray-600 backdrop-blur-sm transition duration-150 ease-in-out hover:border-indigo-200 hover:bg-white/70 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-300/40">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-white/30 bg-white/40 backdrop-blur-lg sm:hidden">
        <div class="space-y-1 pb-3 pt-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @if (Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.employees.index')" :active="request()->routeIs('admin.employees.*')">
                    {{ __('Employees') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.attendance.daily')" :active="request()->routeIs('admin.attendance.*')">
                    {{ __('Attendance') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.schedules.index')" :active="request()->routeIs('admin.schedules.*')">
                    {{ __('Schedules') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="border-t border-white/30 pb-1 pt-4">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                <div class="font-medium text-sm text-gray-500">{{ __('Role') }}: {{ ucfirst(Auth::user()->role) }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
