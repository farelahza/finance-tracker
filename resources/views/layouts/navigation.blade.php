<nav x-data="{ open: false }"
class="bg-gradient-to-r from-[#14213D] via-[#1B2B52] to-[#14213D] border-b border-[#E3BE5B]/40 shadow-md">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex">

                <!-- Brand -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3" aria-label="Finance Tracker">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl border border-[#E3BE5B]/50 bg-[#E3BE5B]/10">
                            <svg class="h-5 w-5 text-[#F0D27A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 17l5-5 4 4 8-8" />
                                <path d="M15 8h5v5" />
                            </svg>
                        </span>
                        <span class="hidden lg:block font-['Playfair_Display',serif] text-lg font-semibold tracking-tight text-[#F0D27A]">
                            Finance Tracker
                        </span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    <x-nav-link
                        :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')">
                        <x-icon name="chart" class="h-[1.15em] w-[1.15em] align-[-0.2em] mr-2" /> Dashboard
                    </x-nav-link>

                    <x-nav-link
                        :href="route('transactions.index')"
                        :active="request()->routeIs('transactions.*')">
                        <x-icon name="clipboard" class="h-[1.15em] w-[1.15em] align-[-0.2em] mr-2" /> Transaksi
                    </x-nav-link>

                    <x-nav-link
                        :href="route('savings-goals.index')"
                        :active="request()->routeIs('savings-goals.*')">
                        <x-icon name="target" class="h-[1.15em] w-[1.15em] align-[-0.2em] mr-2" /> Target Tabungan
                    </x-nav-link>

                </div>
            </div>

            <!-- Right Menu -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                <!-- Dropdown User -->
                <x-dropdown align="right" width="w-64">

                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-3 rounded-full border border-[#E3BE5B]/40 bg-[#E3BE5B]/10 py-1.5 pl-1.5 pr-3.5 text-sm font-medium text-[#F0D27A] transition hover:bg-[#E3BE5B]/20 focus:outline-none focus:ring-2 focus:ring-[#E3BE5B]/40">

                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-b from-[#EBCB68] to-[#A67C1A] text-sm font-bold text-[#2A210B]">
                                {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                            </span>

                            <span class="max-w-[9rem] truncate">{{ Auth::user()->name }}</span>

                            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">

                        <div class="border-b border-[#BE9A35]/60 px-4 py-3">
                            <p class="truncate text-sm font-semibold text-[#2A210B]">{{ Auth::user()->name }}</p>
                            <p class="truncate text-xs text-[#54421A]">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link
                            :href="route('profile.edit')">
                            <x-icon name="user" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Profile
                        </x-dropdown-link>

                        <form method="POST"
                              action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault();
                                this.closest('form').submit();">

                                <x-icon name="logout" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Logout

                            </x-dropdown-link>

                        </form>

                    </x-slot>

                </x-dropdown>

            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">

                <button
                    @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-[#B9A56B] hover:text-[#F0D27A] hover:bg-[#E3BE5B]/10 transition">

                    <svg class="h-6 w-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24">

                        <path
                            :class="{'hidden': open, 'inline-flex': !open }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />

                        <path
                            :class="{'hidden': !open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />

                    </svg>

                </button>

            </div>

        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': !open}"
         class="hidden sm:hidden">

        <div class="pt-2 pb-3 space-y-1">

            <x-responsive-nav-link
                :href="route('dashboard')"
                :active="request()->routeIs('dashboard')">

                <x-icon name="chart" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Dashboard

            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="route('transactions.index')"
                :active="request()->routeIs('transactions.*')">

                <x-icon name="clipboard" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Transaksi

            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="route('savings-goals.index')"
                :active="request()->routeIs('savings-goals.*')">

                <x-icon name="target" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Target Tabungan

            </x-responsive-nav-link>

        </div>

        <div class="border-t border-[#E3BE5B]/25 pt-4 pb-1">

            <div class="flex items-center gap-3 px-4">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-b from-[#EBCB68] to-[#A67C1A] text-base font-bold text-[#2A210B]">
                    {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                </span>
                <div class="min-w-0">
                    <div class="truncate font-medium text-base text-[#F0D27A]">{{ Auth::user()->name }}</div>
                    <div class="truncate font-medium text-sm text-[#B9A56B]">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">

                <x-responsive-nav-link
                    :href="route('profile.edit')">

                    <x-icon name="user" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Profile

                </x-responsive-nav-link>

                <form method="POST"
                      action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link
                        :href="route('logout')"
                        onclick="event.preventDefault();
                        this.closest('form').submit();">

                        <x-icon name="logout" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Logout

                    </x-responsive-nav-link>

                </form>

            </div>

        </div>

    </div>

</nav>