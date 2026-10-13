<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - MDV Kos</title>
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
            padding: 1rem;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .login-wrapper {
            width: 100%;
            max-width: 400px;
        }

        .login-card {
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
            margin-bottom: 1.15rem;
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
            margin-bottom: 0.5rem;
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

        /* Notifikasi Alert */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
            padding: 0.625rem 0.85rem;
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            margin-bottom: 1rem;
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
            margin-bottom: 0.95rem;
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
            height: 46px;
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

        /* Checkbox Ingat Saya (Remember Me) */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 0.85rem;
            margin-top: -0.25rem;
            position: relative;
        }

        .remember-input {
            position: absolute;
            opacity: 0;
            width: 1px;
            height: 1px;
            margin: 0;
            pointer-events: none;
        }

        .checkbox-container {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            cursor: pointer;
            user-select: none;
            font-size: 0.875rem;
            color: var(--text-body);
        }

        .custom-checkbox {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 1.5px solid var(--input-border);
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            flex-shrink: 0;
            pointer-events: none;
        }

        .custom-checkbox svg {
            width: 12px;
            height: 12px;
            color: #ffffff;
            opacity: 0;
            transform: scale(0.6);
            transition: all 0.15s ease;
            pointer-events: none;
        }

        .checkbox-container:hover .custom-checkbox {
            border-color: var(--primary);
        }

        .remember-input:focus-visible + .checkbox-container .custom-checkbox {
            box-shadow: 0 0 0 3px var(--primary-focus-ring);
            border-color: var(--primary);
        }

        .remember-input:checked + .checkbox-container .custom-checkbox {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .remember-input:checked + .checkbox-container .custom-checkbox svg {
            opacity: 1;
            transform: scale(1);
        }

        .remember-text {
            color: var(--text-body);
            font-weight: 500;
            pointer-events: none;
        }

        /* Tombol Submit */
        .btn-primary {
            width: 100%;
            height: 44px;
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
            margin-top: 0.25rem;
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

        /* Tombol Reset Password */
        .reset-link-wrapper {
            margin-top: 0.5rem;
            text-align: center;
        }

        .btn-reset-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--text-muted);
            text-decoration: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            transition: color 0.15s ease, background-color 0.15s ease;
        }

        .btn-reset-link:hover {
            color: var(--primary);
            background-color: #f1f5f9;
        }

        .btn-reset-link svg {
            width: 13px;
            height: 13px;
        }

        /* Divider & Tombol Registrasi Akun */
        .register-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 0.75rem 0 0.6rem;
            color: var(--card-border);
        }

        .register-divider::before,
        .register-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--card-border);
        }

        .register-divider span {
            padding: 0 0.65rem;
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .register-wrapper {
            text-align: center;
        }

        .btn-register-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            width: 100%;
            height: 38px;
            background-color: #f8fafc;
            color: var(--text-heading);
            border: 1.5px solid var(--card-border);
            border-radius: 0.5rem;
            font-size: 0.84375rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-register-link:hover {
            background-color: #eff6ff;
            border-color: #bfdbfe;
            color: var(--primary);
            box-shadow: 0 2px 4px -1px rgba(37, 99, 235, 0.08);
        }

        .btn-register-link svg {
            width: 16px;
            height: 16px;
            color: var(--primary);
        }

        /* Spinner Loading */
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

        .footer-info {
            text-align: center;
            margin-top: 0.85rem;
            font-size: 0.75rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="brand-header">
                <!-- Clean Store/POS Icon -->
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                </div>
                <h1>Masuk ke Akun KOS</h1>
                <p>Silakan masukkan kredensial untuk melanjutkan</p>
            </div>

            <!-- Flash Message: Success/Logout -->
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-success" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span><?= esc(session()->getFlashdata('message')) ?></span>
                </div>
            <?php endif; ?>

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

            <form action="<?= base_url('login') ?>" method="post" id="loginForm">
                <!-- CSRF Protection -->
                <?= csrf_field() ?>

                <!-- Input Username / Email dengan Floating Label -->
                <div class="form-field">
                    <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <input 
                        type="text" 
                        id="login" 
                        name="login" 
                        value="<?= esc(old('login')) ?>" 
                        placeholder=" " 
                        autocomplete="username" 
                        required 
                        autofocus
                    >
                    <label for="login">Username atau Email</label>
                </div>

                <!-- Input Password dengan Floating Label & Toggle Mata -->
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
                        autocomplete="current-password" 
                        class="has-toggle"
                        required
                    >
                    <label for="password">Password</label>

                    <!-- Tombol Lihat / Sembunyikan Password -->
                    <button type="button" class="btn-toggle-pw" id="togglePasswordBtn" aria-label="Tampilkan password" tabindex="-1">
                        <!-- Eye Icon -->
                        <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <!-- Eye Off Icon -->
                        <svg id="eyeOffIcon" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>

                <!-- Checkbox Ingat Saya (Remember Me) -->
                <div class="remember-row">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember" 
                        value="1" 
                        class="remember-input"
                        <?= old('remember') ? 'checked' : '' ?>
                    >
                    <label class="checkbox-container" for="remember">
                        <span class="custom-checkbox" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </span>
                        <span class="remember-text">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Tombol Submit dengan Spinner Loading -->
                <button type="submit" class="btn-primary" id="submitBtn">
                    <span class="spinner" id="btnSpinner"></span>
                    <span id="btnText">Masuk ke Sistem</span>
                </button>

                <!-- Tombol Reset Password -->
                <div class="reset-link-wrapper">
                    <a href="<?= base_url('forgot-password') ?>" class="btn-reset-link" id="resetPasswordBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <span>Lupa Password? Reset di sini</span>
                    </a>
                </div>
            </form>

            <!-- Divider & Tombol Registrasi Akun / Usaha Baru -->
            <div class="register-divider">
                <span>atau</span>
            </div>

            <div class="register-wrapper">
                <a href="<?= base_url('register') ?>" class="btn-register-link" id="registerBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    <span>Daftar Usaha & Akun Baru</span>
                </a>
            </div>

            <div class="footer-info">
                MDV POS &bull; Sistem Kasir Terintegrasi
            </div>
        </div>
    </div>

    <script>
        // Fitur Show/Hide Password
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function() {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                
                if (isPassword) {
                    eyeIcon.style.display = 'none';
                    eyeOffIcon.style.display = 'block';
                    toggleBtn.setAttribute('aria-label', 'Sembunyikan password');
                } else {
                    eyeIcon.style.display = 'block';
                    eyeOffIcon.style.display = 'none';
                    toggleBtn.setAttribute('aria-label', 'Tampilkan password');
                }
                passwordInput.focus();
            });
        }

        // Indikator Loading Saat Form Disubmit
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnSpinner = document.getElementById('btnSpinner');
        const btnText = document.getElementById('btnText');

        if (form && submitBtn) {
            form.addEventListener('submit', function() {
                if (btnSpinner) btnSpinner.style.display = 'inline-block';
                if (btnText) btnText.textContent = 'Memverifikasi...';
                setTimeout(function() {
                    submitBtn.disabled = true;
                }, 0);
            });
        }
    </script>
</body>
</html>
