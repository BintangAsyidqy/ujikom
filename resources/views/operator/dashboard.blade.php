<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('components.operator-sidebar')

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="max-w-6xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-slate-900">Dashboard</h1>
                    <p class="mt-2 text-slate-600">Selamat datang di panel operator, {{ auth()->user()->name }}.</p>
                </div>

                <div class="grid gap-6 sm:grid-cols-3 mb-8">
                    <div class="rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200">
                        <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Total Items</h2>
                        <p class="mt-4 text-3xl font-bold text-slate-900">{{ $totalItems ?? 0 }}</p>
                    </div>
                    <div class="rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200">
                        <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Active Lendings</h2>
                        <p class="mt-4 text-3xl font-bold text-slate-900">{{ $activeLendings ?? 0 }}</p>
                    </div>
                    <div class="rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200">
                        <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Pending Returns</h2>
                        <p class="mt-4 text-3xl font-bold text-slate-900">{{ $pendingReturns ?? 0 }}</p>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-8 shadow-lg ring-1 ring-slate-200">
                    <h2 class="text-2xl font-semibold text-slate-900 mb-4">Ringkasan Sistem</h2>
                    <p class="text-slate-600">Ini adalah halaman dashboard operator. Anda dapat mengelola peminjaman items dan memantau status inventory di sini.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
