<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Items</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="flex min-h-screen">
        @include('components.sidebar')

        <div class="flex-1 p-8">
            <div class="max-w-6xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-slate-900">Items</h1>
                    <p class="mt-2 text-slate-600">Kelola items untuk inventory Anda di halaman ini.</p>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200">
                    @if(session('success'))
                        <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">Items Table</h2>
                            <p class="text-sm text-slate-500">Add, delete, update items.</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.items.export') }}" class="inline-flex items-center rounded-full bg-violet-600 px-5 py-3 text-sm font-semibold text-white hover:bg-violet-700 transition">
                                <i class="fas fa-file-excel mr-2"></i> Export Excel
                            </a>
                            <button type="button" id="openAddItemBtn" class="inline-flex items-center rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                                <i class="fas fa-plus mr-2"></i> Add
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-700 uppercase tracking-[0.15em] text-left text-xs">
                                <tr>
                                    <th class="px-4 py-3">#</th>
                                    <th class="px-4 py-3">Category</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Total</th>
                                    <th class="px-4 py-3">Repair</th>
                                    <th class="px-4 py-3">Lending</th>
                                    <th class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse($items as $item)
                                    <tr>
                                        <td class="px-4 py-4 text-slate-600">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-4 text-slate-900">{{ $item->category->name }}</td>
                                        <td class="px-4 py-4 text-slate-900">{{ $item->name }}</td>
                                        <td class="px-4 py-4 text-slate-600">{{ $item->total }}</td>
                                        <td class="px-4 py-4 text-slate-600">{{ $item->repair }}</td>
                                        <td class="px-4 py-4 text-slate-600">0</td>
                                        <td class="px-4 py-4">
                                            <button type="button" class="editItemBtn rounded-2xl bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700 transition" data-id="{{ $item->id }}">Edit</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-slate-500">Belum ada item. Silakan tambah item baru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Item Modal -->
    <div id="addItemModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-slate-200 p-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-slate-900">Add Item Forms</h2>
                <button type="button" id="closeAddItemBtn" class="text-slate-500 hover:text-slate-700 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.items.add') }}" class="p-6">
                @csrf
                <p class="mb-6 text-slate-600">Please <span class="text-red-600 font-medium">fill all</span> input form with right value.</p>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Category</label>
                    <select name="category_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Piring" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white" />
                    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Total</label>
                    <input type="number" name="total" value="{{ old('total', 0) }}" min="0" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white" />
                    @error('total') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Repair</label>
                    <input type="number" name="repair" value="{{ old('repair', 0) }}" min="0" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white" />
                    @error('repair') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                        <i class="fas fa-save mr-2"></i> Save
                    </button>
                    <button type="button" id="cancelAddItemBtn" class="flex-1 rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div id="editItemModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-slate-200 p-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-slate-900">Edit Item Forms</h2>
                <button type="button" id="closeEditItemBtn" class="text-slate-500 hover:text-slate-700 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="editItemForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <p class="mb-6 text-slate-600">Please <span class="text-red-600 font-medium">fill all</span> input form with right value.</p>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Category</label>
                    <select id="editItemCategory" name="category_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                    <input type="text" id="editItemName" name="name" placeholder="Piring" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white" />
                </div>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Total</label>
                    <input type="number" id="editItemTotal" name="total" min="0" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white" />
                </div>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        New Broke Item <span id="editItemRepairLabel" class="text-amber-500 font-normal text-sm"></span>
                    </label>
                    <input type="number" id="editItemRepair" name="new_repair" value="0" min="0" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white" />
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white hover:bg-violet-700 transition">
                        <i class="fas fa-sync mr-2"></i> Update
                    </button>
                    <button type="button" id="cancelEditItemBtn" class="flex-1 rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function modal(id) {
            const el = document.getElementById(id);
            const open  = () => el.classList.remove('hidden');
            const close = () => el.classList.add('hidden');
            el.addEventListener('click', e => { if (e.target === el) close(); });
            return { open, close };
        }

        const addModal  = modal('addItemModal');
        const editModal = modal('editItemModal');
        const editForm  = document.getElementById('editItemForm');

        document.getElementById('openAddItemBtn').addEventListener('click', addModal.open);
        document.getElementById('closeAddItemBtn').addEventListener('click', addModal.close);
        document.getElementById('cancelAddItemBtn').addEventListener('click', addModal.close);

        @if($errors->hasAny(['category_id', 'name', 'total', 'repair'])) addModal.open(); @endif

        document.getElementById('closeEditItemBtn').addEventListener('click', editModal.close);
        document.getElementById('cancelEditItemBtn').addEventListener('click', editModal.close);

        document.querySelectorAll('.editItemBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                fetch(`/admin/items/${this.dataset.id}/edit`)
                    .then(r => r.json())
                    .then(data => {
                        document.getElementById('editItemCategory').value = data.category_id;
                        document.getElementById('editItemName').value = data.name;
                        document.getElementById('editItemTotal').value = data.total;
                        document.getElementById('editItemRepair').value = 0;
                        document.getElementById('editItemRepairLabel').textContent = `(currently: ${data.repair})`;
                        editForm.action = `/admin/items/${this.dataset.id}`;
                        editModal.open();
                    });
            });
        });
    </script>
</body>
</html>
