<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Items</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="flex min-h-screen">
        @include('components.operator-sidebar')

        <div class="flex-1 p-8">
            <div class="max-w-6xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-slate-900">Items</h1>
                    <p class="mt-2 text-slate-600">Data item inventory.</p>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-slate-900">Items Table</h2>
                        <p class="text-sm text-slate-500">Data <span class="text-violet-500">.items</span></p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-700 uppercase tracking-[0.15em] text-left text-xs">
                                <tr>
                                    <th class="px-4 py-3">#</th>
                                    <th class="px-4 py-3">Category</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Total</th>
                                    <th class="px-4 py-3">Available</th>
                                    <th class="px-4 py-3">Lending Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse($items as $item)
                                    <tr>
                                        <td class="px-4 py-4 text-slate-600">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-4 text-slate-900">{{ $item->category->name }}</td>
                                        <td class="px-4 py-4 text-slate-900">{{ $item->name }}</td>
                                        <td class="px-4 py-4 text-slate-600">{{ $item->total }}</td>
                                        <td class="px-4 py-4 text-slate-600">{{ max(0, $item->total - $item->repair) }}</td>
                                        <td class="px-4 py-4 text-slate-600">{{ $item->lending_total }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada item.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
