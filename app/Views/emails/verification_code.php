<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Akun - MDV POS</title>
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
        .code-wrapper {
            text-align: center;
            margin: 24px 0;
        }
        .code-box {
            display: inline-block;
            background-color: #f8fafc;
            border: 2px dashed #2563eb;
            border-radius: 10px;
            padding: 16px 36px;
            letter-spacing: 8px;
            font-size: 32px;
            font-weight: 800;
            color: #2563eb;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        }
        .btn-wrapper {
            text-align: center;
            margin: 24px 0 16px;
        }
        .btn-verify {
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
        .warning-box {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 14px 16px;
            border-radius: 4px;
            font-size: 13px;
            color: #92400e;
            margin-top: 24px;
            line-height: 1.5;
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
                Terima kasih telah mendaftarkan usaha Anda di <strong>MDV POS</strong>. Akun Anda saat ini berstatus sementara (temporary) dan memerlukan verifikasi email untuk diaktifkan.
            </p>
            <p class="text">
                Gunakan kode verifikasi berikut untuk mengaktifkan akun Anda:
            </p>
            
            <div class="code-wrapper">
                <div class="code-box"><?= esc($code) ?></div>
            </div>

            <div class="btn-wrapper">
                <a href="<?= esc($verifyUrl) ?>" class="btn-verify" target="_blank">Verifikasi Akun Sekarang</a>
            </div>

            <div class="warning-box">
                ⏳ <strong>Ketentuan Akun Sementara:</strong><br>
                Kode verifikasi ini berlaku selama <strong>3 hari</strong> (hingga <?= esc($expiresFormatted ?? '3 hari ke depan') ?>). Jika Anda tidak memasukkan kode verifikasi dalam waktu 3 hari, akun sementara Anda akan <strong>otomatis dihapus secara permanen</strong> dari sistem kami agar tidak menjadi data sampah.
            </div>

            <p class="text" style="font-size: 12px; color: #94a3b8; margin-top: 24px;">
                Jika Anda tidak pernah mendaftar di MDV POS, abaikan email ini dan akun sementara akan otomatis terhapus setelah 3 hari.
            </p>
        </div>
        <div class="footer">
            &copy; <?= date('Y') ?> MDV POS. Seluruh hak cipta dilindungi undang-undang.
        </div>
    </div>
</body>
</html>
