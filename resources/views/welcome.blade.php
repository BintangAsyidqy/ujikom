<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory Management</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }

        #loginModalToggle:not(:checked) ~ .modal-overlay {
            display: none;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(15, 23, 42, 0.5);
            padding: 1rem;
        }

        .modal-overlay:has(#loginModalToggle:checked) {
            display: flex;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-b from-slate-50 to-slate-200 text-gray-900">
    <div class="w-full max-w-6xl mx-auto px-4 py-10">

        <header class="flex items-center justify-between gap-4 mb-12">
            <a href="#" class="flex items-center gap-3 no-underline text-inherit">
                <div class="w-12 h-12 bg-blue-700 rounded-2xl flex items-center justify-center text-white font-bold text-base">
                    IM</div>
                <span class="font-bold text-sm tracking-widest uppercase">Inventory Management</span>
            </a>
            <button type="button" class="px-6 py-3 rounded-full bg-blue-600 text-white font-semibold shadow-lg hover:-translate-y-0.5 hover:shadow-xl transition-all duration-200">
                <label for="loginModalToggle" class="cursor-pointer">Login</label>
            </button>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div class="max-w-xl">
                <h1 class="text-5xl md:text-6xl font-bold leading-tight tracking-tight">Inventory Management of Personal Items</h1>
                <p class="mt-6 mb-8 text-lg leading-relaxed text-gray-500">Management of incoming and outgoing items</p>
                <button type="button" class="px-6 py-3 rounded-full bg-blue-600 text-white font-semibold shadow-lg hover:-translate-y-0.5 hover:shadow-xl transition-all duration-200 inline-block">
                    <label for="loginModalToggle" class="cursor-pointer">Get Started</label>
                </button>
            </div>

            <div class="grid place-items-center p-6 bg-white rounded-3xl shadow-2xl">
                <svg class="w-full max-w-2xl" viewBox="0 0 900 680" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="bgGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#d6e4ff" />
                            <stop offset="100%" stop-color="#f8fafc" />
                        </linearGradient>
                        <linearGradient id="cardGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#3b82f6" />
                            <stop offset="100%" stop-color="#93c5fd" />
                        </linearGradient>
                    </defs>
                    <rect x="0" y="0" width="900" height="680" rx="48" fill="url(#bgGrad)" />
                    <g opacity="0.95">
                        <rect x="520" y="210" width="310" height="260" rx="32" fill="#e2e8f0" />
                        <rect x="560" y="250" width="230" height="48" rx="16" fill="#fff" />
                        <rect x="560" y="314" width="196" height="24" rx="12" fill="#cbd5e1" />
                        <rect x="560" y="352" width="196" height="24" rx="12" fill="#cbd5e1" />
                        <rect x="560" y="390" width="196" height="24" rx="12" fill="#cbd5e1" />
                    </g>
                    <g>
                        <rect x="120" y="250" width="320" height="270" rx="40" fill="#fff" stroke="#cbd5e1" stroke-width="2" />
                        <rect x="160" y="300" width="220" height="34" rx="16" fill="#2563eb" />
                        <rect x="160" y="354" width="260" height="30" rx="15" fill="#e2e8f0" />
                        <rect x="160" y="404" width="260" height="30" rx="15" fill="#e2e8f0" />
                        <rect x="160" y="454" width="260" height="30" rx="15" fill="#e2e8f0" />
                    </g>
                    <g>
                        <rect x="430" y="470" width="320" height="170" rx="32" fill="#f8fafc" stroke="#cbd5e1" stroke-width="2" />
                        <path d="M520 470L540 410H600L620 470" fill="#93c5fd" />
                        <circle cx="520" cy="530" r="18" fill="#2563eb" />
                        <circle cx="600" cy="550" r="18" fill="#2563eb" />
                    </g>
                    <path d="M135 120C215 85 290 120 310 190C330 260 270 340 190 340C110 340 75 220 135 120Z" fill="#60a5fa" opacity="0.15" />
                    <circle cx="670" cy="125" r="55" fill="#ffffff" opacity="0.8" />
                    <circle cx="620" cy="145" r="18" fill="#2563eb" />
                    <circle cx="700" cy="180" r="14" fill="#93c5fd" />
                    <rect x="90" y="90" width="560" height="20" rx="10" fill="#93c5fd" opacity="0.24" />
                    <path d="M300 180C300 120 360 90 420 120C480 150 480 220 420 250C360 280 300 260 300 200Z" fill="#2563eb" opacity="0.08" />
                    <path d="M140 530C140 510 180 490 210 500C230 505 240 530 225 550C210 570 170 570 150 550Z" fill="#1d4ed8" opacity="0.08" />
                    <path d="M540 120L670 120L700 230L590 260L540 120Z" fill="#2563eb" opacity="0.12" />
                    <rect x="520" y="140" width="60" height="110" rx="24" fill="#e0f2fe" />
                    <rect x="620" y="160" width="70" height="90" rx="20" fill="#bfdbfe" />
                    <rect x="680" y="200" width="70" height="80" rx="20" fill="#93c5fd" />
                </svg>
            </div>
        </section>

        <input type="checkbox" id="loginModalToggle" class="hidden peer" />
        <div class="modal-overlay">
            <div class="w-full max-w-md rounded-3xl bg-white shadow-2xl ring-1 ring-slate-200">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Login ke Inventory</h2>
                        <p class="text-sm text-slate-500">Masuk sebagai Admin atau Operator</p>
                    </div>
                    <label for="loginModalToggle" type="button" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                        <span aria-hidden="true">×</span>
                    </label>
                </div>
                <div class="px-6 py-6">
                    <form id="loginForm" method="POST" action="{{ route('login.attempt') }}" class="space-y-5" novalidate>
                        @csrf

                        @if ($errors->any())
                            <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-red-700">
                                <ul class="list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                            <input id="email" name="email" type="email" required autofocus
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
                            <div id="emailError" class="mt-1 text-sm text-red-600 hidden"></div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                            <input id="password" name="password" type="password" required
                                class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
                            <div id="passwordError" class="mt-1 text-sm text-red-600 hidden"></div>
                        </div>

                        <div class="flex items-center justify-between text-sm text-slate-600">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
                                Ingat saya
                            </label>
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-blue-600 px-4 py-3 text-white font-semibold shadow-lg hover:bg-blue-700 transition">
                            Masuk
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div><!-- end max-w-6xl -->

    <footer class="mt-16 border-t border-slate-200 bg-white">
        <div class="w-full max-w-6xl mx-auto px-4 py-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-700 rounded-xl flex items-center justify-center text-white font-bold text-xs">
                    IM</div>
                <span class="font-bold text-sm tracking-widest uppercase text-gray-700">Inventory Management</span>
            </div>
            <p class="text-sm text-gray-400">&copy; 2026 Inventory Management. All rights reserved.</p>
            <div class="flex gap-6 text-sm text-gray-500">
                <a href="#" class="hover:text-blue-600 transition-colors">Tentang</a>
                <a href="#" class="hover:text-blue-600 transition-colors">Kontak</a>
                <button type="button" class="hover:text-blue-600 transition-colors">
                    <label for="loginModalToggle" class="cursor-pointer">Login</label>
                </button>
            </div>
        </div>
    </footer>

    <script>
        const showLoginModal = @if($showLoginModal ?? false) true @elseif($errors->any()) true @else false @endif;

        if (showLoginModal) {
            document.getElementById('loginModalToggle').checked = true;
        }

       
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value.trim();
                const emailError = document.getElementById('emailError');
                const passwordError = document.getElementById('passwordError');
                const emailInput = document.getElementById('email');
                const passwordInput = document.getElementById('password');

                let hasError = false;

                emailError.classList.add('hidden');
                emailError.textContent = '';
                emailInput.classList.remove('border-red-500', 'ring-red-100');
                emailInput.classList.add('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-100');

                passwordError.classList.add('hidden');
                passwordError.textContent = '';
                passwordInput.classList.remove('border-red-500', 'ring-red-100');
                passwordInput.classList.add('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-100');

                if (!email) {
                    emailError.textContent = 'Email wajib diisi.';
                    emailError.classList.remove('hidden');
                    emailInput.classList.remove('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-100');
                    emailInput.classList.add('border-red-500', 'ring-red-100');
                    hasError = true;
                }

                if (!password) {
                    passwordError.textContent = 'Password wajib diisi.';
                    passwordError.classList.remove('hidden');
                    passwordInput.classList.remove('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-100');
                    passwordInput.classList.add('border-red-500', 'ring-red-100');
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();
                    if (!email) {
                        emailInput.focus();
                    } else if (!password) {
                        passwordInput.focus();
                    }
                    return false;
                }
            });
        }
    </script>
</body>

</html>
