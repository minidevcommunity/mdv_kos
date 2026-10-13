<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun - MDV POS</title>
    <style>
        :root {
            --bg-page: #f8fafc;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --text-placeholder: #94a3b8;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-focus-ring: rgba(37, 99, 235, 0.15);
            --input-border: #cbd5e1;
            --input-border-hover: #94a3b8;
            --input-bg: #ffffff;
            --danger-bg: #fef2f2;
            --danger-border: #fecaca;
            --danger-text: #991b1b;
            --warning-bg: #fffbeb;
            --warning-border: #fde68a;
            --warning-text: #92400e;
            --success-bg: #f0fdf4;
            --success-border: #bbf7d0;
            --success-text: #166534;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .verify-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .verify-card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 1rem;
            padding: 1.5rem 1.65rem;
            box-shadow: 
                0 4px 6px -1px rgba(15, 23, 42, 0.05),
                0 10px 15px -3px rgba(15, 23, 42, 0.05),
                0 20px 25px -5px rgba(15, 23, 42, 0.03);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 1.1rem;
        }

        .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 0.65rem;
            background-color: #eff6ff;
            color: var(--primary);
            margin-bottom: 0.45rem;
            border: 1px solid #dbeafe;
        }

        .brand-icon svg {
            width: 22px;
            height: 22px;
        }

        .brand-header h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-heading);
            letter-spacing: -0.015em;
            margin-bottom: 0.15rem;
        }

        .brand-header p {
            color: var(--text-muted);
            font-size: 0.8125rem;
        }

        /* Badge Temporary */
        .badge-temp {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
            border-radius: 9999px;
            padding: 0.2rem 0.65rem;
            font-size: 0.725rem;
            font-weight: 600;
            margin-top: 0.35rem;
        }

        /* Notifikasi Alert */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
            padding: 0.625rem 0.85rem;
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            margin-bottom: 0.9rem;
            line-height: 1.4;
        }

        .alert svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .alert-danger {
            background-color: var(--danger-bg);
            border: 1px solid var(--danger-border);
            color: var(--danger-text);
        }

        .alert-warning {
            background-color: var(--warning-bg);
            border: 1px solid var(--warning-border);
            color: var(--warning-text);
        }

        .alert-success {
            background-color: var(--success-bg);
            border: 1px solid var(--success-border);
            color: var(--success-text);
        }

        .alert ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .alert ul li + li {
            margin-top: 0.25rem;
        }

        /* Input dengan Google Material Floating Outline */
        .form-field {
            position: relative;
            margin-bottom: 0.9rem;
        }

        .field-icon {
            position: absolute;
            left: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            width: 19px;
            height: 19px;
            color: var(--text-muted);
            pointer-events: none;
            transition: color 0.15s ease;
            z-index: 2;
        }

        .form-field input {
            width: 100%;
            height: 44px;
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 0.5rem;
            padding: 0 1rem 0 2.75rem;
            font-size: 0.9375rem;
            color: var(--text-heading);
            font-family: inherit;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-field input:hover {
            border-color: var(--input-border-hover);
        }

        /* Animasi Label Melayang ala Google */
        .form-field label {
            position: absolute;
            left: 2.75rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.9375rem;
            color: var(--text-muted);
            pointer-events: none;
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: var(--card-bg);
            padding: 0 0.35rem;
            z-index: 1;
            white-space: nowrap;
        }

        /* Saat input difokuskan atau terisi teks */
        .form-field input:focus ~ label,
        .form-field input:not(:placeholder-shown) ~ label {
            top: 0;
            left: 0.85rem;
            transform: translateY(-50%);
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary);
        }

        .form-field input:not(:placeholder-shown):not(:focus) ~ label {
            color: var(--text-muted);
        }

        .form-field input:focus {
            border-color: var(--primary);
            border-width: 2px;
            padding-left: calc(2.75rem - 1px);
            box-shadow: 0 0 0 3px var(--primary-focus-ring);
        }

        .form-field input:focus ~ .field-icon {
            color: var(--primary);
        }

        /* Khusus input kode 6-digit */
        .form-field input.code-input {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 6px;
            text-align: center;
            padding-left: 1rem;
            padding-right: 1rem;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        }

        .form-field input.code-input ~ label {
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .form-field input.code-input:focus ~ label,
        .form-field input.code-input:not(:placeholder-shown) ~ label {
            top: 0;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* 3-day notice banner */
        .notice-banner {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 0.5rem;
            padding: 0.65rem 0.85rem;
            font-size: 0.775rem;
            color: #475569;
            margin-bottom: 1rem;
            line-height: 1.45;
        }

        .notice-banner strong {
            color: #0f172a;
        }

        .btn-submit {
            width: 100%;
            height: 44px;
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.15s ease, transform 0.05s ease, box-shadow 0.15s ease;
            box-shadow: 0 1px 2px 0 rgba(37, 99, 235, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .resend-box {
            text-align: center;
            margin-top: 1rem;
            padding-top: 0.85rem;
            border-top: 1px solid var(--card-border);
            font-size: 0.8125rem;
            color: var(--text-muted);
        }

        .btn-resend {
            background: none;
            border: none;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.8125rem;
            cursor: pointer;
            padding: 0;
            text-decoration: underline;
            transition: color 0.15s;
        }

        .btn-resend:hover {
            color: var(--primary-hover);
        }

        .card-footer-links {
            display: flex;
            justify-content: space-between;
            margin-top: 0.75rem;
            font-size: 0.8125rem;
        }

        .card-footer-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s;
        }

        .card-footer-links a:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <main class="verify-wrapper">
        <div class="verify-card">
            <header class="brand-header">
                <div class="brand-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h1>Verifikasi Akun</h1>
                <p>Masukkan 6 digit kode yang dikirim ke email</p>
                <div class="badge-temp">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Status: Akun Sementara (Pending)
                </div>
            </header>

            <!-- Alert Sukses -->
            <?php if (session()->getFlashdata('message')) : ?>
                <div class="alert alert-success" role="alert">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div><?= session()->getFlashdata('message') ?></div>
                </div>
            <?php endif; ?>

            <!-- Alert Peringatan -->
            <?php if (session()->getFlashdata('warning')) : ?>
                <div class="alert alert-warning" role="alert">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div><?= session()->getFlashdata('warning') ?></div>
                </div>
            <?php endif; ?>

            <!-- Alert Error Umum -->
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger" role="alert">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div><?= session()->getFlashdata('error') ?></div>
                </div>
            <?php endif; ?>

            <!-- Alert Validasi -->
            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="alert alert-danger" role="alert">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <ul>
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Notice Ketentuan 3 Hari -->
            <div class="notice-banner">
                <span style="font-size: 1rem; line-height: 1;">⏳</span>
                <div>
                    <strong>Pembersihan Otomatis:</strong> Akun ini berstatus sementara. Jika kode verifikasi tidak dimasukkan dalam <strong>3 hari</strong>, akun akan dihapus permanen untuk mencegah penumpukan data.
                </div>
            </div>

            <!-- Form Verifikasi -->
            <form action="<?= base_url('verify-email') ?>" method="post" id="form-verify">
                <?= csrf_field() ?>

                <!-- Input Email/Username -->
                <div class="form-field">
                    <svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                    </svg>
                    <input 
                        type="text" 
                        name="email" 
                        id="email" 
                        value="<?= esc(old('email', $email ?? '')) ?>" 
                        placeholder=" " 
                        required 
                        autocomplete="email"
                    >
                    <label for="email">Alamat Email Terdaftar</label>
                </div>

                <!-- Input Kode 6 Digit -->
                <div class="form-field">
                    <input 
                        type="text" 
                        name="code" 
                        id="code" 
                        class="code-input"
                        value="<?= esc(old('code', $code ?? '')) ?>" 
                        placeholder=" " 
                        required 
                        maxlength="6"
                        pattern="[0-9]{6}"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        autofocus
                    >
                    <label for="code">6 Digit Kode Verifikasi</label>
                </div>

                <button type="submit" class="btn-submit" id="btn-verify-submit">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Verifikasi & Aktifkan Akun
                </button>
            </form>

            <!-- Form Kirim Ulang Kode -->
            <div class="resend-box">
                Belum menerima kode atau kode kedaluwarsa?
                <form action="<?= base_url('resend-verification') ?>" method="post" style="display: inline; margin-left: 4px;" id="form-resend">
                    <?= csrf_field() ?>
                    <input type="hidden" name="email" id="resend-email" value="<?= esc(old('email', $email ?? '')) ?>">
                    <button type="submit" class="btn-resend" id="btn-resend">Kirim Ulang Kode</button>
                </form>
            </div>

            <!-- Footer Links -->
            <div class="card-footer-links">
                <a href="<?= base_url('login') ?>">&larr; Kembali ke Login</a>
                <a href="<?= base_url('register') ?>">Daftar Ulang &rarr;</a>
            </div>
        </div>
    </main>

    <script>
        // Sinkronisasi nilai email saat user mengetik jika ingin mengirim ulang
        const emailInput = document.getElementById('email');
        const resendEmailInput = document.getElementById('resend-email');
        if (emailInput && resendEmailInput) {
            emailInput.addEventListener('input', function() {
                resendEmailInput.value = this.value;
            });
        }

        // Auto submit jika 6 digit angka sudah lengkap diketik atau di-paste
        const codeInput = document.getElementById('code');
        if (codeInput) {
            codeInput.addEventListener('input', function(e) {
                // Hanya perbolehkan angka
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length === 6 && emailInput && emailInput.value.trim() !== '') {
                    // Optional smooth auto-submit atau biarkan user menekan tombol
                }
            });
        }
    </script>
</body>
</html>
