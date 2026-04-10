<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Lending</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="flex min-h-screen">
        @include('components.operator-sidebar')

        <div class="flex-1 p-8">
            <div class="max-w-6xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-slate-900">Lending</h1>
                    <p class="mt-2 text-slate-600">Kelola data peminjaman item.</p>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200">
                    @if(session('success'))
                        <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">Lending Table</h2>
                            <p class="text-sm text-slate-500">Data of <span class="text-violet-500">.lendings</span></p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('operator.lending.export') }}"
                                class="inline-flex items-center rounded-full bg-violet-600 px-5 py-3 text-sm font-semibold text-white hover:bg-violet-700 transition">
                                <i class="fas fa-file-excel mr-2"></i> Export Excel
                            </a>
                            <button type="button" id="openAddBtn"
                                class="inline-flex items-center rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                                <i class="fas fa-plus mr-2"></i> Add
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-700 uppercase tracking-[0.15em] text-left text-xs">
                                <tr>
                                    <th class="px-4 py-3">No
                                    </th>
                                    <th class="px-4 py-3">Item</th>
                                    <th class="px-4 py-3">Total</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Ket.</th>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Returned</th>
                                    <th class="px-4 py-3">Edit By</th>
                                    <th class="px-4 py-3">Back By</th>
                                    <th class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse($lendings as $lending)
                                    <tr>
                                        <td class="px-4 py-4 text-slate-600">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-4 text-slate-900">
                                            @foreach($lending->items as $li)
                                                <div>{{ $li['name'] }}</div>
                                            @endforeach
                                        </td>
                                        <td class="px-4 py-4 text-slate-600">
                                            @foreach($lending->items as $li)
                                                <div>{{ $li['total'] }}</div>
                                            @endforeach
                                        </td>
                                        <td class="px-4 py-4 text-slate-900">{{ $lending->name }}</td>
                                        <td class="px-4 py-4 text-slate-600">{{ $lending->ket }}</td>
                                        <td class="px-4 py-4 text-slate-600">
                                            {{ \Carbon\Carbon::parse($lending->date)->format('d F, Y') }}
                                        </td>
                                        <td class="px-4 py-4">
                                            @if($lending->returned)
                                                <span class="px-3 py-1 text-xs text-emerald-600">{{ $lending->updated_at->format('d F, Y') }}</span>
                                            @else
                                                <span class="px-3 py-1 text-xs text-amber-600">not returned</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 font-semibold text-slate-900">{{ $lending->edit_by }}</td>
                                        <td class="px-4 py-4 text-slate-600">{{ $lending->back_by ?? '-' }}</td>
                                        <td class="px-4 py-4 flex gap-2">
                                            @if(!$lending->returned)
                                                <form method="POST" action="{{ route('operator.lending.returned', $lending) }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="rounded-2xl bg-amber-400 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-500 transition">Returned</button>
                                                </form>
                                            @endif
                                            <button type="button"
                                                class="delete-btn rounded-2xl bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600 transition"
                                                data-url="{{ route('operator.lending.destroy', $lending) }}">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-4 py-8 text-center text-slate-500">Belum ada data lending.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-slate-200 p-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-slate-900">Add Lending</h2>
                <button type="button" id="closeAddBtn" class="text-slate-500 hover:text-slate-700"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <form method="POST" action="{{ route('operator.lending.store') }}" class="p-6">
                @csrf
                <p class="mb-6 text-slate-600">Please <span class="text-red-600 font-medium">fill-all</span> input form
                    with right value.</p>

                @if(session('overlimit'))
                    <div class="mb-4 rounded-2xl bg-red-50 border border-red-200 p-4 text-red-600">
                        {{ session('overlimit') }}
                    </div>
                @endif

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Name"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-slate-400 focus:bg-white" />
                    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div id="itemsContainer">
                    <div class="item-row mb-4">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Items</label>
                        <select name="item_id[]"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-slate-400 focus:bg-white mb-2">
                            <option value="">Select Items</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Total</label>
                        <input type="number" name="total[]" min="1" placeholder="total item"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-slate-400 focus:bg-white" />
                    </div>
                </div>

                <button type="button" id="addMoreBtn"
                    class="mb-6 flex items-center gap-1 text-sm text-cyan-500 hover:text-cyan-600 font-medium">
                    <i class="fas fa-chevron-down text-xs"></i> More
                </button>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Ket.</label>
                    <textarea name="ket" rows="3"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-slate-400 focus:bg-white">{{ old('ket') }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Date</label>
                    <input type="date" name="date" value="{{ old('date') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-slate-400 focus:bg-white" />
                    @error('date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="rounded-2xl bg-violet-600 px-6 py-3 text-sm font-semibold text-white hover:bg-violet-700 transition">Submit</button>
                    <button type="button" id="cancelAddBtn"
                        class="rounded-2xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-2">Sudah Yakin?</h2>
            <p class="text-slate-600 mb-6">Data lending ini akan dihapus permanen.</p>
            <div class="flex gap-3 justify-end">
                <button id="cancelDeleteBtn" class="rounded-2xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="rounded-2xl bg-red-500 px-6 py-3 text-sm font-semibold text-white hover:bg-red-600 transition">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function modal(id) {
            const el = document.getElementById(id);
            const open = () => el.classList.remove('hidden');
            const close = () => el.classList.add('hidden');
            el.addEventListener('click', e => { if (e.target === el) close(); });
            return { open, close };
        }

        const addModal = modal('addModal');
        document.getElementById('openAddBtn').addEventListener('click', addModal.open);
        document.getElementById('closeAddBtn').addEventListener('click', addModal.close);
        document.getElementById('cancelAddBtn').addEventListener('click', addModal.close);

        @if($errors->hasAny(['item_id', 'total', 'name', 'date']) || session('overlimit')) addModal.open(); @endif

        const itemOptions = `@foreach($items as $item)<option value="{{ $item->id }}">{{ $item->name }}</option>@endforeach`;

        document.getElementById('addMoreBtn').addEventListener('click', function () {
            const container = document.getElementById('itemsContainer');
            const row = document.createElement('div');
            row.className = 'item-row mb-4 relative';
            row.innerHTML = `
                <div class="flex items-center justify-between mb-1">
                    <label class="text-sm font-medium text-slate-700">Items</label>
                    <button type="button" class="remove-row text-slate-400 hover:text-red-500"><i class="fas fa-times"></i></button>
                </div>
                <select name="item_id[]" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-slate-400 focus:bg-white mb-2">
                    <option value="">Select Items</option>
                    ${itemOptions}
                </select>
                <label class="mb-1 block text-sm font-medium text-slate-700">Total</label>
                <input type="number" name="total[]" min="1" placeholder="total item" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-slate-400 focus:bg-white" />
            `;
            row.querySelector('.remove-row').addEventListener('click', () => row.remove());
            container.appendChild(row);
        });

        const deleteModal = modal('deleteModal');
        document.getElementById('cancelDeleteBtn').addEventListener('click', deleteModal.close);

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('deleteForm').action = this.dataset.url;
                deleteModal.open();
            });
        });
    </script>
</body>

</html>