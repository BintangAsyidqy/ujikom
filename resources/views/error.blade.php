<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="text-center">
            <img src="{{ asset('assets/error.jpg') }}" alt="Error" class="mx-auto h-auto max-h-screen w-auto rounded-3xl object-contain shadow-lg" />
            <div class="mt-8">
                <a href="javascript:history.back()" class="inline-flex rounded-full bg-blue-600 px-6 py-3 text-white shadow-lg hover:bg-blue-700 transition">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</body>
</html>
