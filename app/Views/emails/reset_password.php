<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - MDV POS</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #334155;
            -webkit-text-size-adjust: 100%;
        }
        .email-container {
            max-width: 560px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #2563eb;
            padding: 28px 32px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .header p {
            margin: 4px 0 0;
            font-size: 13px;
            opacity: 0.9;
        }
        .content {
            padding: 32px;
            line-height: 1.6;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .text {
            font-size: 14px;
            color: #475569;
            margin: 12px 0;
        }
        .btn-wrapper {
            text-align: center;
            margin: 28px 0;
        }
        .btn-reset {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            padding: 13px 28px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.25);
        }
        .note-box {
            background-color: #f8fafc;
            border-left: 4px solid #2563eb;
            padding: 12px 16px;
            border-radius: 4px;
            font-size: 13px;
            color: #64748b;
            margin-top: 24px;
        }
        .link-fallback {
            font-size: 12px;
            color: #94a3b8;
            word-break: break-all;
            margin-top: 20px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px 32px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>MDV POS</h1>
            <p>Sistem Kasir Terintegrasi</p>
        </div>
        <div class="content">
            <div class="greeting">Halo, <?= esc($name ?? 'Pengguna') ?>!</div>
            <p class="text">
                Kami menerima permintaan untuk mereset kata sandi akun Anda pada sistem MDV POS.
            </p>
            <p class="text">
                Silakan klik tombol di bawah ini untuk membuat kata sandi baru:
            </p>
            
            <div class="btn-wrapper">
                <a href="<?= esc($resetUrl) ?>" class="btn-reset" target="_blank">Reset Password Saya</a>
            </div>

            <div class="note-box">
                ⏱ <strong>Masa Berlaku:</strong> Link reset password ini hanya berlaku selama <strong>60 menit</strong>. Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini dan akun Anda akan tetap aman.
            </div>

            <p class="link-fallback">
                Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:<br>
                <a href="<?= esc($resetUrl) ?>" style="color: #2563eb;"><?= esc($resetUrl) ?></a>
            </p>
        </div>
        <div class="footer">
            &copy; <?= date('Y') ?> MDV POS. Seluruh hak cipta dilindungi undang-undang.
        </div>
    </div>
</body>
</html>
