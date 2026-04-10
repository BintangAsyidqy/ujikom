<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator - Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="flex min-h-screen">
        @include('components.operator-sidebar')

        <div class="flex-1 p-8">
            <div class="max-w-6xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-slate-900">My Account</h1>
                    <p class="mt-2 text-slate-600">Kelola akun Anda.</p>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200">
                    @if(session('success'))
                        <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-slate-900">Account Table</h2>
                        <p class="text-sm text-slate-500">Edit <span class="text-violet-500">.your-account</span></p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-700 uppercase tracking-[0.15em] text-left text-xs">
                                <tr>
                                    <th class="px-4 py-3">#</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Email</th>
                                    <th class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                <tr>
                                    <td class="px-4 py-4 text-slate-600">1</td>
                                    <td class="px-4 py-4 text-slate-900">{{ $user->name }}</td>
                                    <td class="px-4 py-4 text-slate-600">{{ $user->email }}</td>
                                    <td class="px-4 py-4">
                                        <button type="button" class="editBtn rounded-2xl bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700 transition" data-id="{{ $user->id }}">Edit</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full">
            <div class="sticky top-0 bg-white border-b border-slate-200 p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Edit Account Forms</h2>
                    <p class="text-sm text-slate-500 mt-1">Please <span class="text-pink-500">.fill-all</span> input form with right value.</p>
                </div>
                <button type="button" id="closeEditBtn" class="text-slate-500 hover:text-slate-700"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="editForm" method="POST" class="p-6">
                @csrf @method('PUT')
                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                    <input type="text" id="editName" name="name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-slate-400 focus:bg-white" />
                </div>
                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" id="editEmail" name="email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-slate-400 focus:bg-white" />
                </div>
                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-slate-700">New Password <span class="text-amber-500 font-normal">optional</span></label>
                    <input type="password" name="new_password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-slate-400 focus:bg-white" />
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" id="cancelEditBtn" class="rounded-2xl bg-slate-400 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-500 transition">Cancel</button>
                    <button type="submit" class="rounded-2xl bg-violet-600 px-6 py-3 text-sm font-semibold text-white hover:bg-violet-700 transition">Submit</button>
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

        const editModal = modal('editModal');
        document.getElementById('closeEditBtn').addEventListener('click', editModal.close);
        document.getElementById('cancelEditBtn').addEventListener('click', editModal.close);

        document.querySelectorAll('.editBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('editName').value = '{{ $user->name }}';
                document.getElementById('editEmail').value = '{{ $user->email }}';
                document.getElementById('editForm').action = '{{ route("operator.users.update", $user) }}';
                editModal.open();
            });
        });
    </script>
</body>
</html>
