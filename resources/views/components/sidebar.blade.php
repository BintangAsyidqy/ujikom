<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="w-64 bg-white shadow-lg">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 bg-blue-600 text-white">
            <span class="text-xl font-bold">Admin</span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 bg-blue-50' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }} rounded-lg transition">
                <i class="fas fa-tachometer-alt w-5 h-5 mr-3 text-center"></i>
                Dashboard
            </a>
            
            <a href="{{ route('admin.categories') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.categories') ? 'text-blue-600 bg-blue-50' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }} rounded-lg transition">
                <i class="fas fa-tags w-5 h-5 mr-3 text-center"></i>
                Categories
            </a>
            
            <a href="{{ route('admin.items') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.items') ? 'text-blue-600 bg-blue-50' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }} rounded-lg transition">
                <i class="fas fa-box w-5 h-5 mr-3 text-center"></i>
                Items
            </a>
            <!-- Users Dropdown -->
            <div class="relative">
                <input type="checkbox" id="usersDropdown" class="hidden" />
                <label for="usersDropdown" class="flex items-center px-4 py-3 text-sm font-medium text-slate-600 hover:text-blue-600 hover:bg-slate-50 rounded-lg transition cursor-pointer w-full">
                    <i class="fas fa-users w-5 h-5 mr-3 text-center"></i>
                    Users
                    <svg class="w-4 h-4 ml-auto transition-transform duration-200" id="usersArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </label>

                <!-- Dropdown Menu -->
                <div id="usersMenu" class="hidden absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-lg shadow-lg z-10">
                    <a href="{{ route('admin.users.admin') }}" class="block px-4 py-3 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600 first:rounded-t-lg transition">
                        <i class="fas fa-shield-alt w-4 h-4 mr-3 inline"></i>
                        Admin
                    </a>
                    <a href="{{ route('admin.users.operator') }}" class="block px-4 py-3 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600 last:rounded-b-lg transition">
                        <i class="fas fa-user-cog w-4 h-4 mr-3 inline"></i>
                        Operator
                    </a>
                </div>
            </div>
        </nav>

        <script>
            // Dropdown toggle functionality
            const usersDropdown = document.getElementById('usersDropdown');
            const usersMenu = document.getElementById('usersMenu');
            const usersArrow = document.getElementById('usersArrow');

            if (usersDropdown && usersMenu && usersArrow) {
                usersDropdown.addEventListener('change', function() {
                    if (this.checked) {
                        usersMenu.classList.remove('hidden');
                        usersMenu.classList.add('block', 'dropdown-enter');
                        usersArrow.classList.add('rotate-180');
                    } else {
                        usersMenu.classList.add('hidden');
                        usersMenu.classList.remove('block', 'dropdown-enter');
                        usersArrow.classList.remove('rotate-180');
                    }
                });
            }
        </script>

        <!-- User Info & Logout -->
        <div class="p-4 border-t border-slate-200">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-slate-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                    <i class="fas fa-sign-out-alt w-4 h-4 mr-2"></i>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>