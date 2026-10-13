<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Usaha & Akun Baru - MDV POS</title>
    <!-- Select2 CSS & Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

        .register-wrapper {
            width: 100%;
            max-width: 540px;
        }

        .register-card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 1rem;
            padding: 1.35rem 1.65rem;
            box-shadow: 
                0 4px 6px -1px rgba(15, 23, 42, 0.05),
                0 10px 15px -3px rgba(15, 23, 42, 0.05),
                0 20px 25px -5px rgba(15, 23, 42, 0.03);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 0.85rem;
        }

        .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 0.65rem;
            background-color: #eff6ff;
            color: var(--primary);
            margin-bottom: 0.35rem;
            border: 1px solid #dbeafe;
        }

        .brand-icon svg {
            width: 20px;
            height: 20px;
        }

        .brand-header h1 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-heading);
            letter-spacing: -0.015em;
            margin-bottom: 0.1rem;
        }

        .brand-header p {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        /* Notifikasi Alert */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            margin-bottom: 0.75rem;
        }

        .alert svg {
            width: 16px;
            height: 16px;
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

        /* Pemisah Bagian (Section Header) */
        .section-header {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin: 0.75rem 0 0.5rem;
            padding-bottom: 0.2rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .section-header:first-of-type {
            margin-top: 0;
        }

        .section-header svg {
            width: 13px;
            height: 13px;
            color: var(--primary);
        }

        /* Layout Grid Baris Ganda Responsif */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.65rem;
        }

        @media (max-width: 520px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .register-card {
                padding: 1.25rem 1.15rem;
            }
        }

        /* Input dengan Google Material Floating Outline */
        .form-field {
            position: relative;
            margin-bottom: 0.65rem;
        }

        .field-icon {
            position: absolute;
            left: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--text-muted);
            pointer-events: none;
            transition: color 0.15s ease;
            z-index: 2;
        }

        .form-field input,
        .form-field select {
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
            appearance: none;
            -webkit-appearance: none;
        }

        .form-field select {
            cursor: pointer;
            padding-right: 2.25rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.85rem center;
            background-size: 16px 16px;
        }

        .form-field input:hover,
        .form-field select:hover {
            border-color: var(--input-border-hover);
        }

        .form-field input.has-toggle {
            padding-right: 2.85rem;
        }

        /* Custom Styling untuk Select2 agar Seragam dengan Input Google Outlined */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 44px !important;
            background-color: var(--input-bg) !important;
            border: 1px solid var(--input-border) !important;
            border-radius: 0.5rem !important;
            padding-left: 2.75rem !important;
            padding-right: 1.75rem !important;
            display: flex !important;
            align-items: center !important;
            transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
            outline: none !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--text-heading) !important;
            font-size: 0.9375rem !important;
            font-family: inherit !important;
            padding-left: 0 !important;
            line-height: normal !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
            right: 0.75rem !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #64748b transparent transparent transparent !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #64748b transparent !important;
        }

        .select2-container--default:hover .select2-selection--single {
            border-color: var(--input-border-hover) !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--primary) !important;
            border-width: 2px !important;
            padding-left: calc(2.75rem - 1px) !important;
            box-shadow: 0 0 0 3px var(--primary-focus-ring) !important;
        }

        .form-field.is-focused .field-icon {
            color: var(--primary) !important;
        }

        .form-field.has-val label,
        .form-field.is-focused label {
            top: 0 !important;
            left: 0.85rem !important;
            transform: translateY(-50%) !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            color: var(--primary) !important;
        }

        .form-field.has-val:not(.is-focused) label {
            color: var(--text-muted) !important;
        }

        /* Dropdown Panel Select2 */
        .select2-dropdown {
            border: 1px solid var(--card-border) !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.08), 0 4px 6px -2px rgba(15, 23, 42, 0.05) !important;
            font-family: inherit !important;
            z-index: 9999 !important;
            overflow: hidden !important;
        }

        .select2-search--dropdown {
            padding: 0.5rem !important;
        }

        .select2-search--dropdown .select2-search__field {
            height: 36px !important;
            border: 1px solid var(--input-border) !important;
            border-radius: 0.375rem !important;
            padding: 0 0.75rem !important;
            outline: none !important;
            font-size: 0.875rem !important;
            color: var(--text-heading) !important;
            font-family: inherit !important;
            transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 2px var(--primary-focus-ring) !important;
        }

        .select2-results__option {
            padding: 0.45rem 0.75rem !important;
            font-size: 0.875rem !important;
            color: var(--text-body) !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary) !important;
            color: #ffffff !important;
        }

        .select2-results__option--selected {
            background-color: #eff6ff !important;
            color: var(--primary) !important;
            font-weight: 600 !important;
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

        /* Saat input atau select difokuskan atau bernilai */
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

        /* Outline fokus */
        .form-field input:focus,
        .form-field select:focus {
            border-color: var(--primary);
            border-width: 2px;
            padding-left: calc(2.75rem - 1px);
            box-shadow: 0 0 0 3px var(--primary-focus-ring);
        }

        .form-field input.has-toggle:focus {
            padding-right: calc(2.85rem - 1px);
        }

        .form-field input:focus ~ .field-icon,
        .form-field select:focus ~ .field-icon {
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

        /* Tombol Submit */
        .btn-primary {
            width: 100%;
            height: 42px;
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

        /* Tombol Kembali / Link Login */
        .login-link-wrapper {
            margin-top: 0.55rem;
            text-align: center;
        }

        .btn-login-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--text-muted);
            text-decoration: none;
            padding: 0.2rem 0.5rem;
            border-radius: 0.375rem;
            transition: color 0.15s ease, background-color 0.15s ease;
        }

        .btn-login-link:hover {
            color: var(--primary);
            background-color: #f1f5f9;
        }

        .btn-login-link strong {
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
            margin-top: 0.55rem;
            font-size: 0.72rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <div class="register-card">
            <div class="brand-header">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                </div>
                <h1>Daftar Usaha Baru</h1>
                <p>Mulai kelola kasir dan operasional bisnis Anda sekarang</p>
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

            <form action="<?= base_url('register') ?>" method="post" id="registerForm">
                <?= csrf_field() ?>

                <!-- Bagian 1: Informasi Usaha / Bisnis -->
                <div class="section-header">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <span>Informasi Usaha & Bisnis</span>
                </div>

                <div class="form-row">
                    <!-- Nama Usaha -->
                    <div class="form-field">
                        <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <input 
                            type="text" 
                            id="business_name" 
                            name="business_name" 
                            value="<?= esc(old('business_name')) ?>" 
                            placeholder=" " 
                            required 
                            autofocus
                        >
                        <label for="business_name">Nama Usaha / Toko</label>
                    </div>

                    <!-- Jenis / Kategori Bisnis (Select2) -->
                    <div class="form-field select2-field <?= !empty($selectedType) ? 'has-val' : '' ?>" id="businessTypeField">
                        <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        <?php $selectedType = old('business_type'); ?>
                        <select id="business_type" name="business_type" required>
                            <option value=""></option>
                            <option value="F&B / Kuliner" <?= $selectedType === 'F&B / Kuliner' ? 'selected' : '' ?>>F&B / Kuliner & Minuman</option>
                            <option value="Retail & Toko" <?= $selectedType === 'Retail & Toko' ? 'selected' : '' ?>>Retail & Minimarket</option>
                            <option value="Fashion & Pakaian" <?= $selectedType === 'Fashion & Pakaian' ? 'selected' : '' ?>>Fashion & Pakaian</option>
                            <option value="Jasa & Servis" <?= $selectedType === 'Jasa & Servis' ? 'selected' : '' ?>>Jasa & Servis</option>
                            <option value="Kesehatan & Apotek" <?= $selectedType === 'Kesehatan & Apotek' ? 'selected' : '' ?>>Kesehatan & Apotek</option>
                            <option value="Lainnya" <?= $selectedType === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                        <label for="business_type">Kategori Bisnis</label>
                    </div>
                </div>

                <!-- Alamat / Kota Usaha (Opsional) -->
                <div class="form-field">
                    <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <input 
                        type="text" 
                        id="business_address" 
                        name="business_address" 
                        value="<?= esc(old('business_address')) ?>" 
                        placeholder=" "
                    >
                    <label for="business_address">Kota / Alamat Usaha (Opsional)</label>
                </div>

                <!-- Bagian 2: Data Diri & Akun Pengguna -->
                <div class="section-header">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Data Diri & Akun Masuk</span>
                </div>

                <div class="form-row">
                    <!-- Nama Lengkap -->
                    <div class="form-field">
                        <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="<?= esc(old('name')) ?>" 
                            placeholder=" " 
                            required
                        >
                        <label for="name">Nama Lengkap</label>
                    </div>

                    <!-- No. WhatsApp / Telepon -->
                    <div class="form-field">
                        <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            value="<?= esc(old('phone')) ?>" 
                            placeholder=" "
                        >
                        <label for="phone">No. WhatsApp / HP</label>
                    </div>
                </div>

                <div class="form-row">
                    <!-- Username -->
                    <div class="form-field">
                        <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"></circle>
                            <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path>
                        </svg>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="<?= esc(old('username')) ?>" 
                            placeholder=" " 
                            autocomplete="username" 
                            required
                        >
                        <label for="username">Username</label>
                    </div>

                    <!-- Email -->
                    <div class="form-field">
                        <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?= esc(old('email')) ?>" 
                            placeholder=" " 
                            autocomplete="email" 
                            required
                        >
                        <label for="email">Alamat Email</label>
                    </div>
                </div>

                <!-- Bagian 3: Kata Sandi -->
                <div class="section-header">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Kata Sandi Akun</span>
                </div>

                <div class="form-row">
                    <!-- Password -->
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
                        >
                        <label for="password">Password</label>
                        <button type="button" class="btn-toggle-pw" id="togglePasswordBtn" aria-label="Tampilkan password" tabindex="-1">
                            <svg id="eyeIcon1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg id="eyeOffIcon1" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- Konfirmasi Password -->
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
                        <label for="pass_confirm">Konfirmasi Password</label>
                        <button type="button" class="btn-toggle-pw" id="toggleConfirmBtn" aria-label="Tampilkan konfirmasi password" tabindex="-1">
                            <svg id="eyeIcon2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg id="eyeOffIcon2" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-primary" id="submitBtn">
                    <span class="spinner" id="btnSpinner"></span>
                    <span id="btnText">Daftarkan Usaha & Akun</span>
                </button>
            </form>

            <div class="login-link-wrapper">
                <a href="<?= base_url('login') ?>" class="btn-login-link">
                    <span>Sudah memiliki akun?</span>
                    <strong>Masuk ke Sistem</strong>
                </a>
            </div>

            <div class="footer-info">
                MDV POS &bull; Sistem Kasir Terintegrasi
            </div>
        </div>
    </div>

    <script>
        // Inisialisasi Select2 untuk Kategori Bisnis (dapat diketik / dicari)
        $(document).ready(function() {
            const $select = $('#business_type');
            const $field = $('#businessTypeField');

            $select.select2({
                placeholder: '',
                allowClear: false,
                width: '100%',
                tags: true, // Memungkinkan user mengetik kategori sendiri
                language: {
                    noResults: function() {
                        return "Tekan Enter untuk menambahkan kategori ini";
                    }
                }
            });

            function updateFloatingLabel() {
                const val = $select.val();
                if (val && String(val).trim() !== '') {
                    $field.addClass('has-val');
                } else {
                    $field.removeClass('has-val');
                }
            }

            $select.on('select2:open', function() {
                $field.addClass('is-focused');
            });

            $select.on('select2:close', function() {
                $field.removeClass('is-focused');
                updateFloatingLabel();
            });

            $select.on('change', function() {
                updateFloatingLabel();
            });

            // Initial state check
            updateFloatingLabel();
        });

        // Toggle Password
        const pwInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const eyeIcon1 = document.getElementById('eyeIcon1');
        const eyeOffIcon1 = document.getElementById('eyeOffIcon1');

        if (toggleBtn && pwInput) {
            toggleBtn.addEventListener('click', function() {
                const isPw = pwInput.getAttribute('type') === 'password';
                pwInput.setAttribute('type', isPw ? 'text' : 'password');
                eyeIcon1.style.display = isPw ? 'none' : 'block';
                eyeOffIcon1.style.display = isPw ? 'block' : 'none';
                toggleBtn.setAttribute('aria-label', isPw ? 'Sembunyikan password' : 'Tampilkan password');
                pwInput.focus();
            });
        }

        // Toggle Konfirmasi Password
        const confirmInput = document.getElementById('pass_confirm');
        const toggleConfirmBtn = document.getElementById('toggleConfirmBtn');
        const eyeIcon2 = document.getElementById('eyeIcon2');
        const eyeOffIcon2 = document.getElementById('eyeOffIcon2');

        if (toggleConfirmBtn && confirmInput) {
            toggleConfirmBtn.addEventListener('click', function() {
                const isPw = confirmInput.getAttribute('type') === 'password';
                confirmInput.setAttribute('type', isPw ? 'text' : 'password');
                eyeIcon2.style.display = isPw ? 'none' : 'block';
                eyeOffIcon2.style.display = isPw ? 'block' : 'none';
                toggleConfirmBtn.setAttribute('aria-label', isPw ? 'Sembunyikan konfirmasi password' : 'Tampilkan konfirmasi password');
                confirmInput.focus();
            });
        }

        // Loading Spinner on Submit
        const form = document.getElementById('registerForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnSpinner = document.getElementById('btnSpinner');
        const btnText = document.getElementById('btnText');

        if (form && submitBtn) {
            form.addEventListener('submit', function() {
                if (btnSpinner) btnSpinner.style.display = 'inline-block';
                if (btnText) btnText.textContent = 'Mendaftarkan...';
                setTimeout(function() {
                    submitBtn.disabled = true;
                }, 0);
            });
        }
    </script>
</body>
</html>
