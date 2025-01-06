<div x-show="open" @click.outside="open = false"
    class="absolute right-0 z-10 mt-10 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
    role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">

    <!-- check if user logged in -->
    @if (auth()->check())
        <a href="{{route('profile', auth()->user()->username)}}"
            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1"
            id="user-menu-item-0">Your Profile
        </a>

        <a href="{{route('edit-profile')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
            tabindex="-1" id="user-menu-item-1">Edit Profile
        </a>

        <a href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1"
            id="user-menu-item-2">Sign out
        </a>

        <form id="logout-form" action="{{route('logout')}}" method="POST" class="hidden">
            @csrf
        </form>

    @else
        <a href="{{route('login')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
            tabindex="-1" id="user-menu-item-0">Login
        </a>

        <a href="{{route('register')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
            tabindex="-1" id="user-menu-item-1">Register
        </a>

    @endif
</div>