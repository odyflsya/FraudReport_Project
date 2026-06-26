<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Persetujuan - Fraud Report</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="h-screen w-screen overflow-hidden">

<div class="relative h-full w-full flex items-center justify-center">
    <img src="{{ asset('assets/img/image1.png') }}" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative z-10 w-full max-w-lg mx-auto px-6">
        <div class="bg-white/95 backdrop-blur-sm p-8 md:p-10 rounded-2xl shadow-2xl border border-white/20 text-center">
            <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-yellow-100 flex items-center justify-center">
                <i class="fa-solid fa-hourglass-half text-2xl text-yellow-600"></i>
            </div>

            <h1 class="text-2xl font-bold text-gray-800 mb-3">Email Terverifikasi</h1>

            @if(session('status'))
                <p class="text-green-700 bg-green-50 border border-green-200 rounded-lg p-4 text-sm mb-4">
                    {{ session('status') }}
                </p>
            @endif

            <p class="text-gray-600 text-sm leading-relaxed mb-6">
                Akun Anda sedang <strong>menunggu persetujuan administrator</strong>.
                Setelah disetujui, Anda dapat login dengan <strong>email dan password</strong> yang didaftarkan.
            </p>

            <div class="bg-slate-50 rounded-xl p-4 text-left text-sm text-gray-600 mb-6 space-y-2">
                <p class="font-semibold text-gray-700">Alur pendaftaran:</p>
                <p><i class="fa-solid fa-check text-green-500 w-5"></i> 1. Daftar akun</p>
                <p><i class="fa-solid fa-check text-green-500 w-5"></i> 2. Verifikasi email (OTP)</p>
                <p><i class="fa-solid fa-clock text-yellow-500 w-5"></i> 3. Tunggu admin menyetujui</p>
                <p><i class="fa-solid fa-circle text-gray-300 w-5"></i> 4. Login dengan email & password</p>
            </div>

            <a href="{{ route('login') }}"
               class="inline-flex items-center justify-center gap-2 w-full bg-brand-blue hover:bg-brand-orange text-white py-3 px-4 rounded-lg font-medium transition-colors">
                Ke Halaman Login
            </a>
        </div>
    </div>
</div>

</body>
</html>
