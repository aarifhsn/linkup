<header>
    <!-- Navigation -->
    <nav x-data="{ mobileMenuOpen: false, userMenuOpen: false }" class="bg-white shadow">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between">
                <div class="flex">
                    <div class="flex flex-shrink-0 items-center">
                        <a wire:navigate href="{{route('home')}}">
                            <h2 class="font-bold text-sky-800 text-2xl">{{ config('app.name') }}</h2>
                        </a>
                    </div>
                </div>

                <livewire:search />

                <div class="hidden sm:ml-6 sm:flex gap-2 sm:items-center">
                    <!-- Notification dropdown -->
                    @if (auth()->check())

                        <!-- message box  -->
                        <div x-data="{ open: false }" class="relative">

                            <div class="mr-2 flex">
                                <button @click="open = !open" type="button relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" id="message" x="0" y="0"
                                        fill="currentColor" class="w-6 h-6 text-sky-600" version="1.1" viewBox="0 0 20 20">
                                        <path
                                            d="M18 6v7c0 1.1-.9 2-2 2h-4v3l-4-3H4c-1.101 0-2-.9-2-2V6c0-1.1.899-2 2-2h12c1.1 0 2 .9 2 2z">
                                        </path>
                                    </svg>
                                </button>
                            </div>

                            <div class="relative" x-show="open" @click.outside="open = false">
                                <div class="absolute right-0 z-10 mt-6 w-96 origin-top-right">
                                    <livewire:chat />
                                </div>
                            </div>

                        </div>



                        <div x-data="{ open: false }" class="flex relative">

                            <button @click="open = !open" type="button relative">

                                @if (auth()->user()->unreadNotifications->count() > 0)
                                    <span id="notification-count"
                                        class="absolute top-[-8px] right-[-6px] bg-red-600 text-white text-xs font-semibold w-4 h-4 flex items-center justify-center rounded-full">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif

                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-bell-fill text-sky-600" viewBox="0 0 16 16">
                                    <path
                                        d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901" />
                                </svg>

                            </button>
                            <div class="relative" x-show="open" @click.outside="open = false">
                                <div class="absolute right-0 z-10 mt-6 w-96 origin-top-right">
                                    <livewire:notifications />
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Profile dropdown -->
                    <div class="relative ml-3 flex gap-6" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open" type="button"
                                class="flex rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Open user menu</span>
                                @if (auth()->check())
                                    <img class="h-8 w-8 rounded-full" src="{{ auth()->user()->avatar_url }}"
                                        alt="{{ auth()->user()->username }}" />
                                @elseif (auth()->guest())
                                    <!-- Menu icon  -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="w-6 h-6 text-sky-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16m-7 6h7"></path>
                                    </svg>
                                @endif
                            </button>
                        </div>

                        <!-- Dropdown menu -->
                        @include('components.navbar')

                    </div>
                </div>
                <div class="-mr-2 flex items-center sm:hidden">
                    <!-- Mobile menu button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                        class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-gray-500"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <!-- Icon when menu is closed -->
                        <svg x-show="!mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>

                        <!-- Icon when menu is open -->
                        <svg x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        @include ('components.mobile-navbar')
    </nav>

    @livewire('notification-viewer')

</header>