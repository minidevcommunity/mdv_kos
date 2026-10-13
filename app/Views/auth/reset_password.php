<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password Baru - MDV POS</title>
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
            padding: 1.5rem;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .reset-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .reset-card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 1rem;
            padding: 2.25rem 2rem;
            box-shadow: 
                0 4px 6px -1px rgba(15, 23, 42, 0.05),
                0 10px 15px -3px rgba(15, 23, 42, 0.05),
                0 20px 25px -5px rgba(15, 23, 42, 0.03);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            border-radius: 0.75rem;
            background-color: #eff6ff;
            color: var(--primary);
            margin-bottom: 1rem;
            border: 1px solid #dbeafe;
        }

        .brand-icon svg {
            width: 26px;
            height: 26px;
        }

        .brand-header h1 {
            font-size: 1.375rem;
            font-weight: 700;
            color: var(--text-heading);
            letter-spacing: -0.015em;
            margin-bottom: 0.25rem;
        }

        .brand-header p {
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        /* Notifikasi Alert */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
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
            margin-bottom: 1.35rem;
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
            height: 50px;
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

        .form-field input.has-toggle {
            padding-right: 2.85rem;
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

        /* Jika terisi teks tapi tidak aktif difokuskan */
        .form-field input:not(:placeholder-shown):not(:focus) ~ label {
            color: var(--text-muted);
        }

        /* Outline fokus */
        .form-field input:focus {
            border-color: var(--primary);
            border-width: 2px;
            padding-left: calc(2.75rem - 1px);
            box-shadow: 0 0 0 3px var(--primary-focus-ring);
        }

        .form-field input.has-toggle:focus {
            padding-right: calc(2.85rem - 1px);
        }

        .form-field input:focus ~ .field-icon {
            color: var(--primary);
        }

        /* Tombol Show/Hide Password */
        .btn-toggle-pw {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 0.45rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.15s, background-color 0.15s;
            z-index: 2;
        }

        .btn-toggle-pw:hover {
            color: var(--text-heading);
            background-color: #f1f5f9;
        }

        .btn-toggle-pw svg {
            width: 18px;
            height: 18px;
        }

        .btn-primary {
            width: 100%;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background-color: var(--primary);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.9375rem;
            font-family: inherit;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: background-color 0.15s ease, box-shadow 0.15s ease;
            margin-top: 0.5rem;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }

        .btn-primary:active {
            background-color: #1e40af;
        }

        .btn-primary:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            box-shadow: none;
        }

        .back-link-wrapper {
            margin-top: 1.25rem;
            text-align: center;
        }

        .btn-back-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            font-size: 0.84375rem;
            font-weight: 500;
            color: var(--text-muted);
            text-decoration: none;
            padding: 0.4rem 0.75rem;
            border-radius: 0.375rem;
            transition: color 0.15s ease, background-color 0.15s ease;
        }

        .btn-back-link:hover {
            color: var(--primary);
            background-color: #f1f5f9;
        }

        .btn-back-link svg {
            width: 15px;
            height: 15px;
        }

        .spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            animation: spin 0.75s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="reset-wrapper">
        <div class="reset-card">
            <div class="brand-header">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <h1>Buat Password Baru</h1>
                <p>Masukkan kata sandi baru untuk akun Anda</p>
            </div>

            <!-- Flash Message: Error -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span><?= esc(session()->getFlashdata('error')) ?></span>
                </div>
            <?php endif; ?>

            <!-- Flash Message: Validation Errors -->
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <ul>
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('reset-password') ?>" method="post" id="resetForm">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= esc($token) ?>">

                <!-- Password Baru dengan Google Material Floating Label & Toggle Mata -->
                <div class="form-field">
                    <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder=" " 
                        autocomplete="new-password" 
                        class="has-toggle"
                        required 
                        autofocus
                    >
                    <label for="password">Password Baru</label>
                    <button type="button" class="btn-toggle-pw" id="togglePasswordBtn" aria-label="Tampilkan password" tabindex="-1">
                        <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg id="eyeOffIcon" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>

                <!-- Konfirmasi Password Baru dengan Google Material Floating Label & Toggle Mata -->
                <div class="form-field">
                    <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <input 
                        type="password" 
                        id="pass_confirm" 
                        name="pass_confirm" 
                        placeholder=" " 
                        autocomplete="new-password" 
                        class="has-toggle"
                        required
                    >
                    <label for="pass_confirm">Konfirmasi Password Baru</label>
                    <button type="button" class="btn-toggle-pw" id="toggleConfirmBtn" aria-label="Tampilkan konfirmasi password" tabindex="-1">
                        <svg id="eyeIconConfirm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg id="eyeOffIconConfirm" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>

                <button type="submit" class="btn-primary" id="submitBtn">
                    <span class="spinner" id="btnSpinner"></span>
                    <span id="btnText">Simpan Password Baru</span>
                </button>
            </form>

            <div class="back-link-wrapper">
                <a href="<?= base_url('login') ?>" class="btn-back-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Batal dan Kembali ke Login</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Toggle Password Baru
        const pwInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        if (toggleBtn && pwInput) {
            toggleBtn.addEventListener('click', function() {
                const isPw = pwInput.getAttribute('type') === 'password';
                pwInput.setAttribute('type', isPw ? 'text' : 'password');
                eyeIcon.style.display = isPw ? 'none' : 'block';
                eyeOffIcon.style.display = isPw ? 'block' : 'none';
                toggleBtn.setAttribute('aria-label', isPw ? 'Sembunyikan password' : 'Tampilkan password');
                pwInput.focus();
            });
        }

        // Toggle Konfirmasi Password
        const confirmInput = document.getElementById('pass_confirm');
        const toggleConfirmBtn = document.getElementById('toggleConfirmBtn');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');
        const eyeOffIconConfirm = document.getElementById('eyeOffIconConfirm');

        if (toggleConfirmBtn && confirmInput) {
            toggleConfirmBtn.addEventListener('click', function() {
                const isPw = confirmInput.getAttribute('type') === 'password';
                confirmInput.setAttribute('type', isPw ? 'text' : 'password');
                eyeIconConfirm.style.display = isPw ? 'none' : 'block';
                eyeOffIconConfirm.style.display = isPw ? 'block' : 'none';
                toggleConfirmBtn.setAttribute('aria-label', isPw ? 'Sembunyikan konfirmasi password' : 'Tampilkan konfirmasi password');
                confirmInput.focus();
            });
        }

        // Loading Spinner on Submit
        const form = document.getElementById('resetForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnSpinner = document.getElementById('btnSpinner');
        const btnText = document.getElementById('btnText');

        if (form && submitBtn) {
            form.addEventListener('submit', function() {
                if (btnSpinner) btnSpinner.style.display = 'inline-block';
                if (btnText) btnText.textContent = 'Menyimpan...';
                setTimeout(function() {
                    submitBtn.disabled = true;
                }, 0);
            });
        }
    </script>
</body>
</html>
