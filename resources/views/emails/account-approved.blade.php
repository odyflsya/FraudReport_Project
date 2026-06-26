<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Akun Disetujui</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937;">
    <div style="max-width: 600px; margin: 0 auto; padding: 32px; background: #f9fafb; border-radius: 12px;">
        <h1 style="font-size: 24px; margin-bottom: 16px;">Akun Anda Telah Disetujui</h1>
        <p style="font-size: 16px; margin-bottom: 16px;">
            Halo {{ $user->name }},
        </p>
        <p style="font-size: 16px; margin-bottom: 16px;">
            Pendaftaran akun Fraud Report Anda telah disetujui oleh administrator.
            Anda sekarang dapat login menggunakan email dan password yang telah didaftarkan.
        </p>
        <p style="font-size: 14px; color: #4b5563;">
            Email: <strong>{{ $user->email }}</strong>
        </p>
    </div>
</body>
</html>
