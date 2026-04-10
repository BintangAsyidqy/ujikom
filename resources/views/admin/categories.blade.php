<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Categories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="flex min-h-screen">
        @include('components.sidebar')

        <div class="flex-1 p-8">
            <div class="max-w-6xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-slate-900">Kategori Produk</h1>
                    <p class="mt-2 text-slate-600">Kelola kategori untuk inventory Anda di halaman ini.</p>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200">
                    @if(session('success'))
                        <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">Categories Table</h2>
                            <p class="text-sm text-slate-500">Add, delete, update kategori.</p>
                        </div>
                        <button type="button" id="openAddCategoryBtn" class="inline-flex items-center rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                            <i class="fas fa-plus mr-2"></i> Add Category
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-700 uppercase tracking-[0.15em] text-left text-xs">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Division PJ</th>
                                    <th class="px-4 py-3">Total Items</th>
                                    <th class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="px-4 py-4 text-slate-600">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-4 text-slate-900">{{ $category->name }}</td>
                                        <td class="px-4 py-4 text-slate-600">{{ $category->division }}</td>
                                        <td class="px-4 py-4 text-slate-600">{{ $category->items_count }}</td>
                                        <td class="px-4 py-4 flex gap-2">
                                            <button type="button" class="editCategoryBtn rounded-2xl bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700 transition" data-id="{{ $category->id }}">Edit</button>
                                            <button type="button" class="deleteCategoryBtn rounded-2xl bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600 transition" data-url="{{ route('admin.categories.destroy', $category) }}">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada kategori. Silakan tambah kategori baru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="addCategoryModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-slate-200 p-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-slate-900">Add Category Forms</h2>
                <button type="button" id="closeAddCategoryBtn" class="text-slate-500 hover:text-slate-700 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.categories.add') }}" class="p-6">
                @csrf
                <p class="mb-6 text-slate-600">Please <span class="text-red-600 font-medium">fill all</span> input form with right value.</p>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Alat" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white" />
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Division PJ</label>
                    <select name="division" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                        <option value="">Select Division PJ</option>
                        <option value="Sarpras" {{ old('division') === 'Sarpras' ? 'selected' : '' }}>Sarpras</option>
                        <option value="Tata Usaha" {{ old('division') === 'Tata Usaha' ? 'selected' : '' }}>Tata Usaha</option>
                        <option value="Tefa" {{ old('division') === 'Tefa' ? 'selected' : '' }}>Tefa</option>
                    </select>
                    @error('division')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                        <i class="fas fa-save mr-2"></i> Save
                    </button>
                    <button type="button" id="cancelAddCategoryBtn" class="flex-1 rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-slate-200 p-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-slate-900">Edit Category Forms</h2>
                <button type="button" id="closeEditCategoryBtn" class="text-slate-500 hover:text-slate-700 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="editCategoryForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <p class="mb-6 text-slate-600">Please <span class="text-red-600 font-medium">fill all</span> input form with right value.</p>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                    <input type="text" id="editCategoryNameInput" name="name" placeholder="Alat" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white" />
                </div>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Division PJ</label>
                    <select id="editCategoryDivisionSelect" name="division" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white">
                        <option value="">Select Division PJ</option>
                        <option value="Sarpras">Sarpras</option>
                        <option value="Tata Usaha">Tata Usaha</option>
                        <option value="Tefa">Tefa</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white hover:bg-violet-700 transition">
                        <i class="fas fa-sync mr-2"></i> Update
                    </button>
                    <button type="button" id="cancelEditCategoryBtn" class="flex-1 rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Category Modal -->
    <div id="deleteCategoryModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-2">Hapus Kategori</h2>
            <p class="text-slate-600 mb-6">Kategori ini akan dihapus permanen.</p>
            <div class="flex gap-3 justify-end">
                <button id="cancelDeleteCategoryBtn" class="rounded-2xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Cancel</button>
                <form id="deleteCategoryForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="rounded-2xl bg-red-500 px-6 py-3 text-sm font-semibold text-white hover:bg-red-600 transition">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function modal(id) {
            const el = document.getElementById(id);
            const open  = () => el.classList.remove('hidden');
            const close = () => el.classList.add('hidden');
            el.addEventListener('click', e => { if (e.target === el) close(); });
            return { open, close, el };
        }

        const addModal    = modal('addCategoryModal');
        const editModal   = modal('editCategoryModal');
        const deleteModal = modal('deleteCategoryModal');
        const editForm    = document.getElementById('editCategoryForm');

        document.getElementById('openAddCategoryBtn').addEventListener('click', addModal.open);
        document.getElementById('closeAddCategoryBtn').addEventListener('click', addModal.close);
        document.getElementById('cancelAddCategoryBtn').addEventListener('click', addModal.close);

        @if($errors->hasAny(['name', 'division'])) addModal.open(); @endif

        document.getElementById('closeEditCategoryBtn').addEventListener('click', editModal.close);
        document.getElementById('cancelEditCategoryBtn').addEventListener('click', editModal.close);
        document.getElementById('cancelDeleteCategoryBtn').addEventListener('click', deleteModal.close);

        document.querySelectorAll('.editCategoryBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                fetch(`/admin/categories/${this.dataset.id}/edit`)
                    .then(r => r.json())
                    .then(data => {
                        document.getElementById('editCategoryNameInput').value = data.name;
                        document.getElementById('editCategoryDivisionSelect').value = data.division;
                        editForm.action = `/admin/categories/${this.dataset.id}`;
                        editModal.open();
                    });
            });
        });

        document.querySelectorAll('.deleteCategoryBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('deleteCategoryForm').action = this.dataset.url;
                deleteModal.open();
            });
        });
    </script>

</body>
</html>