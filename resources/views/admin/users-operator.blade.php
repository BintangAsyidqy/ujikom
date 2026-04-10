<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users - Operator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="flex min-h-screen">
        @include('components.sidebar')

        <div class="flex-1 p-8">
            <div class="max-w-6xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-slate-900">Operator Accounts</h1>
                    <p class="mt-2 text-slate-600">Kelola akun operator untuk sistem ini.</p>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200">
                    @if(session('success'))
                        <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('generated_password'))
                        <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-200 p-4 text-amber-800">
                            Password akun: <span class="font-bold font-mono">{{ session('generated_password') }}</span> —
                            simpan password ini.
                        </div>
                    @endif

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">Operator Accounts Table</h2>
                            <p class="text-sm text-slate-500">Add, delete, update <span
                                    class="text-violet-500">.operator-accounts</span></p>
                            <p class="text-sm text-pink-500">p.s password <span class="text-slate-500">4 character of
                                    email and nomor.</span></p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.users.operator.export') }}"
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
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Email</th>
                                    <th class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse($users as $user)
                                    <tr>
                                        <td class="px-4 py-4 text-slate-600">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-4 text-slate-900">{{ $user->name }}</td>
                                        <td class="px-4 py-4 text-slate-600">{{ $user->email }}</td>
                                        <td class="px-4 py-4 flex gap-2">
                                            <button type="button" class="reset-btn rounded-2xl bg-amber-400 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-500 transition"
                                                data-url="{{ route('admin.users.reset-password', $user) }}"
                                                data-name="{{ $user->name }}">Reset Password</button>
                                            <button type="button" class="delete-btn rounded-2xl bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600 transition"
                                                data-url="{{ route('admin.users.destroy', $user) }}"
                                                data-name="{{ $user->name }}">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-slate-500">
                                            No users found.
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
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full">
            <div class="sticky top-0 bg-white border-b border-slate-200 p-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-slate-900">Add Operator</h2>
                <button type="button" id="closeAddBtn" class="text-slate-500 hover:text-slate-700"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}" class="p-6">
                @csrf
                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-slate-400 focus:bg-white" />
                    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-slate-400 focus:bg-white" />
                    @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition"><i
                            class="fas fa-save mr-2"></i> Save</button>
                    <button type="button" id="cancelAddBtn"
                        class="flex-1 rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition"><i
                            class="fas fa-times mr-2"></i> Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Reset Password Modal --}}
    <div id="resetPasswordModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-2">Reset Password</h2>
            <p class="text-slate-600 mb-6">Reset password untuk <span id="resetUserName" class="font-semibold"></span>?</p>
            <div class="flex gap-3 justify-end">
                <button id="cancelResetBtn" class="rounded-2xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Cancel</button>
                <form id="resetForm" method="POST">
                    @csrf
                    <button type="submit" class="rounded-2xl bg-amber-400 px-6 py-3 text-sm font-semibold text-white hover:bg-amber-500 transition">Reset</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-2">Hapus User</h2>
            <p class="text-slate-600 mb-6">Hapus akun <span id="deleteUserName" class="font-semibold"></span>? Data tidak bisa dipulihkan.</p>
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

        const addModal          = modal('addModal');
        const resetModal        = modal('resetPasswordModal');
        const deleteModal       = modal('deleteModal');

        document.getElementById('openAddBtn').addEventListener('click', addModal.open);
        document.getElementById('closeAddBtn').addEventListener('click', addModal.close);
        document.getElementById('cancelAddBtn').addEventListener('click', addModal.close);
        document.getElementById('cancelResetBtn').addEventListener('click', resetModal.close);
        document.getElementById('cancelDeleteBtn').addEventListener('click', deleteModal.close);

        @if($errors->hasAny(['name', 'email'])) addModal.open(); @endif

        document.querySelectorAll('.reset-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('resetUserName').textContent = this.dataset.name;
                document.getElementById('resetForm').action = this.dataset.url;
                resetModal.open();
            });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('deleteUserName').textContent = this.dataset.name;
                document.getElementById('deleteForm').action = this.dataset.url;
                deleteModal.open();
            });
        });
    </script>
</body>

</html>