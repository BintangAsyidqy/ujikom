<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</style>
<div class="w-64 bg-white shadow-lg">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 bg-blue-600 text-white">
            <span class="text-xl font-bold">Operator</span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="{{ route('operator.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('operator.dashboard') ? 'text-blue-600 bg-blue-50' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }} rounded-lg transition">
                <i class="fas fa-tachometer-alt w-5 h-5 mr-3 text-center"></i>
                Dashboard
            </a>

            <a href="{{ route('operator.lending') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('operator.lending') ? 'text-blue-600 bg-blue-50' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }} rounded-lg transition">
                <i class="fas fa-hand-holding-usd w-5 h-5 mr-3 text-center"></i>
                Lending
            </a>
            
            <a href="{{ route('operator.items') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('operator.items') ? 'text-blue-600 bg-blue-50' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }} rounded-lg transition">
                <i class="fas fa-box w-5 h-5 mr-3 text-center"></i>
                Items
            </a>

            <div class="relative">
                <button type="button" id="usersDropdownBtn" class="flex items-center w-full px-4 py-3 text-sm font-medium {{ request()->routeIs('operator.users') ? 'text-blue-600 bg-blue-50' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }} rounded-lg transition">
                    <i class="fas fa-users w-5 h-5 mr-3 text-center"></i>
                    Users
                    <svg class="w-4 h-4 ml-auto transition-transform duration-200" id="usersArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="usersMenu" class="hidden mt-1 bg-white border border-slate-200 rounded-lg shadow-lg">
                    <a href="{{ route('operator.users') }}" class="block px-4 py-3 text-sm {{ request()->routeIs('operator.users') ? 'text-blue-600 bg-blue-50' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600' }} rounded-lg transition">
                        <i class="fas fa-user-edit w-4 h-4 mr-3 inline"></i>
                        Edit
                    </a>
                </div>
            </div>

            <script>
                const btn = document.getElementById('usersDropdownBtn');
                const menu = document.getElementById('usersMenu');
                const arrow = document.getElementById('usersArrow');
                @if(request()->routeIs('operator.users'))
                    menu.classList.remove('hidden');
                    arrow.style.transform = 'rotate(180deg)';
                @endif
                btn.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                    arrow.style.transform = menu.classList.contains('hidden') ? '' : 'rotate(180deg)';
                });
            </script>
        </nav>

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