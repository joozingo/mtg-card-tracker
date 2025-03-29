<nav class="navbar">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('dashboard') }}"
                style="color: white; text-decoration: none; font-size: 1.5rem; font-weight: bold;">
                MTG Card Tracker
            </a>
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    style="width: 24px; height: 24px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
            <div class="desktop-menu">
                <a href="{{ route('cards.index') }}" style="color: white; text-decoration: none; margin-left: 1rem;">
                    Browse Cards
                </a>
                <a href="{{ route('cards.search') }}" style="color: white; text-decoration: none; margin-left: 1rem;">
                    Advanced Search
                </a>
                <a href="{{ route('collection.index') }}"
                    style="color: white; text-decoration: none; margin-left: 1rem;">
                    My Collection
                </a>
                <a href="{{ route('wishlist.index') }}" style="color: white; text-decoration: none; margin-left: 1rem;">
                    My Wishlist
                </a>
                <a href="{{ route('decks.index') }}" style="color: white; text-decoration: none; margin-left: 1rem;">
                    My Decks
                </a>

                @auth
                    <div class="ml-4 relative" style="margin-left: 1rem;">
                        <div>
                            <button type="button" onclick="toggleUserMenu()"
                                class="flex items-center text-white focus:outline-none">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1"
                            style="z-index: 10;">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" style="color: white; text-decoration: none; margin-left: 1rem;">
                        Login
                    </a>
                    <a href="{{ route('register') }}" style="color: white; text-decoration: none; margin-left: 1rem;">
                        Register
                    </a>
                @endauth
            </div>
        </div>
        <div class="mobile-menu" id="mobileMenu">
            <a href="{{ route('cards.index') }}">Browse Cards</a>
            <a href="{{ route('cards.search') }}">Advanced Search</a>
            <a href="{{ route('collection.index') }}">My Collection</a>
            <a href="{{ route('wishlist.index') }}">My Wishlist</a>
            <a href="{{ route('decks.index') }}">My Decks</a>

            @auth
                <a href="{{ route('profile.edit') }}">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left py-2 text-white"
                        style="background: none; border: none;">
                        Log Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobileMenu');
        mobileMenu.classList.toggle('active');
    }

    function toggleUserMenu() {
        const userMenu = document.getElementById('userMenu');
        userMenu.classList.toggle('hidden');
    }
</script>