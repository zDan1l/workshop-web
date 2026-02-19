<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 480px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: #7B5CB8; padding: 28px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; }
        .body { padding: 32px; color: #333; }
        .otp-box { background: #f0ebfa; border: 2px dashed #7B5CB8; border-radius: 8px; text-align: center; padding: 20px; margin: 24px 0; }
        .otp-code { font-size: 40px; font-weight: bold; letter-spacing: 12px; color: #7B5CB8; }
        .footer { background: #f9f9f9; padding: 16px; text-align: center; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verifikasi Login</h1>
        </div>
        <div class="body">
            <p>Halo, <strong>{{ $userName }}</strong>!</p>
            <p>Gunakan kode OTP berikut untuk menyelesaikan proses login Anda:</p>
            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
            </div>
            <p>Kode ini berlaku selama <strong>5 menit</strong>. Jangan bagikan kode ini kepada siapapun.</p>
            <p>Jika Anda tidak merasa melakukan login, abaikan email ini.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} App Laravel Workshop. Semua hak dilindungi.
        </div>
    </div>
</body>
</html>
