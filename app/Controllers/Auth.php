<?php

namespace App\Controllers;

use App\Services\AuthService;

class Auth extends BaseController
{
    protected ?AuthService $authService = null;

    protected function getAuthService(): AuthService
    {
        return $this->authService ??= new AuthService();
    }

    /**
     * Menampilkan halaman form login (GET /login).
     * Jika user sudah login atau memiliki remember cookie yang valid, diarahkan ke /dashboard.
     */
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        // Cek apakah ada cookie remember_token untuk auto-login
        helper('cookie');
        $rememberCookie = get_cookie('remember_token');
        if ($rememberCookie) {
            $user = $this->getAuthService()->validateRememberToken($rememberCookie);
            if ($user) {
                session()->regenerate();
                session()->set([
                    'user_id'    => $user['id'],
                    'username'   => $user['username'],
                    'email'      => $user['email'],
                    'name'       => $user['name'] ?? $user['username'],
                    'isLoggedIn' => true,
                ]);

                return redirect()->to('/dashboard');
            }

            delete_cookie('remember_token');
        }

        return view('auth/login');
    }

    /**
     * Memproses autentikasi form login (POST /login).
     */
    public function authenticate()
    {
        // Validasi input
        $rules = [
            'login' => [
                'label'  => 'Username / Email',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                ],
            ],
            'password' => [
                'label'  => 'Password',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $login    = (string) $this->request->getPost('login');
        $password = (string) $this->request->getPost('password');
        $remember = ! empty($this->request->getPost('remember'));

        log_message('info', 'Auth::authenticate - Login: ' . $login . ', Remember: ' . ($remember ? 'YES' : 'NO'));

        // Panggil AuthService untuk verifikasi user dan password
        $user = $this->getAuthService()->authenticate($login, $password);

        if (! $user) {
            // Pesan kesalahan dibuat umum demi keamanan
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username/email atau password salah.');
        }

        // Cek jika akun berstatus pending (belum verifikasi kode email)
        if (! empty($user['is_pending'])) {
            session()->setFlashdata('pending_email', $user['email']);
            return redirect()->to('/verify-email?email=' . urlencode($user['email']))
                ->with('warning', 'Akun Anda berstatus sementara dan belum diverifikasi. Silakan masukkan kode verifikasi 6 digit yang dikirim ke email Anda (' . esc($user['email']) . '). Akun akan dihapus otomatis jika tidak diverifikasi dalam 3 hari.');
        }

        // Regenerasi session ID untuk mencegah session fixation attack
        session()->regenerate();

        // Simpan data user ke session (tanpa password)
        session()->set([
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'email'      => $user['email'],
            'name'       => $user['name'] ?? $user['username'],
            'isLoggedIn' => true,
        ]);

        if ($remember) {
            // Buat token baru dan simpan ke cookie selama 30 hari (30 * 86400 detik)
            $token = $this->getAuthService()->setRememberToken((int) $user['id']);
            log_message('info', 'Auth::authenticate - Remember token created: ' . $token);

            return redirect()->to('/dashboard')
                ->setCookie('remember_token', $token, 30 * 86400, '', '/', '', false, true, 'Lax');
        }

        // Jika tidak dicentang, bersihkan remember token lama
        $this->getAuthService()->clearRememberToken((int) $user['id']);

        return redirect()->to('/dashboard')
            ->deleteCookie('remember_token');
    }

    /**
     * Memproses logout user (POST /logout).
     */
    public function logout()
    {
        helper('cookie');
        $userId = (int) session()->get('user_id');
        if ($userId > 0) {
            $this->getAuthService()->clearRememberToken($userId);
        }

        session()->destroy();

        return redirect()->to('/login')
            ->deleteCookie('remember_token')
            ->with('message', 'Anda telah berhasil logout.');
    }

    /**
     * Menampilkan form lupa password (GET /forgot-password).
     */
    public function forgotPassword()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/forgot_password');
    }

    /**
     * Memproses pengiriman tautan reset password ke email (POST /forgot-password).
     */
    public function sendResetLink()
    {
        $rules = [
            'login' => [
                'label'  => 'Username atau Email',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getError('login'));
        }

        $identity  = trim((string) $this->request->getPost('login'));
        $resetData = $this->getAuthService()->generatePasswordReset($identity);

        if (! $resetData) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Akun dengan username atau email tersebut tidak ditemukan atau tidak aktif.');
        }

        $user        = $resetData['user'];
        $rawToken    = $resetData['token'];
        $resetUrl    = base_url('reset-password/' . $rawToken);
        $targetEmail = $user['email'];

        // Kirim email via Service Email (Live SMTP Gmail)
        $emailService = service('email');
        $emailConfig  = config('Email');

        $emailService->setTo($targetEmail);
        $emailService->setFrom($emailConfig->fromEmail ?: 'minidevcustomerservice@gmail.com', $emailConfig->fromName ?: 'MDV POS System');
        $emailService->setSubject('Reset Password Akun MDV POS');
        $emailService->setMessage(view('emails/reset_password', [
            'name'     => $user['name'] ?? $user['username'],
            'resetUrl' => $resetUrl,
        ]));

        $mailSent = false;
        try {
            $mailSent = $emailService->send(false);
        } catch (\Throwable $e) {
            log_message('error', 'Gagal mengirim email reset password: ' . $e->getMessage());
        }

        log_message('info', 'Password Reset requested for ' . $identity . ' -> target: ' . $targetEmail . '. Token generated: ' . $rawToken . '. Mail sent: ' . ($mailSent ? 'YES' : 'NO'));

        if (! $mailSent) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengirim email ke ' . esc($targetEmail) . '. Silakan periksa kembali konfigurasi SMTP email.');
        }

        return redirect()->back()->with('message', 'Tautan reset password telah berhasil dikirim ke email: ' . esc($targetEmail) . '. Silakan periksa kotak masuk (inbox) atau spam.');
    }

    /**
     * Menampilkan form input password baru (GET /reset-password atau /reset-password/(:any)).
     */
    public function resetPassword(?string $token = null)
    {
        $token = $token ?: (string) $this->request->getGet('token');
        $token = trim((string) $token);

        log_message('info', 'Auth::resetPassword checking token: ' . $token);

        $user = $this->getAuthService()->validateResetToken($token);

        if (! $user) {
            log_message('error', 'Auth::resetPassword token invalid/expired: ' . $token);

            return redirect()->to('/forgot-password')
                ->with('error', 'Tautan reset password tidak valid atau telah kedaluwarsa. Silakan ajukan ulang.');
        }

        return view('auth/reset_password', [
            'token' => $token,
            'user'  => $user,
        ]);
    }

    /**
     * Memproses penyimpanan password baru (POST /reset-password).
     */
    public function updatePassword()
    {
        $rules = [
            'token' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Token reset tidak valid.'],
            ],
            'password' => [
                'label'  => 'Password Baru',
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'min_length' => '{field} minimal 6 karakter.',
                ],
            ],
            'pass_confirm' => [
                'label'  => 'Konfirmasi Password',
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'matches'  => '{field} tidak cocok dengan Password Baru.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $token       = trim((string) $this->request->getPost('token'));
        $newPassword = (string) $this->request->getPost('password');

        $success = $this->getAuthService()->resetPassword($token, $newPassword);

        if (! $success) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui password. Tautan reset mungkin telah kedaluwarsa atau sudah digunakan.');
        }

        return redirect()->to('/login')
            ->with('message', 'Password Anda berhasil diperbarui! Silakan login dengan password baru.');
    }

    /**
     * Menampilkan halaman form registrasi usaha & akun baru (GET /register).
     */
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register');
    }

    /**
     * Memproses pendaftaran user dan usaha baru (POST /register).
     */
    public function processRegister()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        $rules = [
            'name' => [
                'label'  => 'Nama Lengkap',
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'min_length' => '{field} minimal 3 karakter.',
                ],
            ],
            'username' => [
                'label'  => 'Username',
                'rules'  => 'required|alpha_numeric_punct|min_length[3]|max_length[50]|is_unique[users.username]',
                'errors' => [
                    'required'            => '{field} wajib diisi.',
                    'min_length'          => '{field} minimal 3 karakter.',
                    'is_unique'           => '{field} sudah digunakan, silakan pilih yang lain.',
                    'alpha_numeric_punct' => '{field} hanya boleh huruf, angka, dan karakter tertentu.',
                ],
            ],
            'email' => [
                'label'  => 'Email',
                'rules'  => 'required|valid_email|max_length[191]|is_unique[users.email]',
                'errors' => [
                    'required'    => '{field} wajib diisi.',
                    'valid_email' => 'Format {field} tidak valid.',
                    'is_unique'   => '{field} sudah terdaftar, silakan gunakan email lain atau masuk ke akun Anda.',
                ],
            ],
            'phone' => [
                'label'  => 'No. WhatsApp / Telepon',
                'rules'  => 'permit_empty|min_length[8]|max_length[20]',
                'errors' => [
                    'min_length' => '{field} minimal 8 digit.',
                ],
            ],
            'business_name' => [
                'label'  => 'Nama Usaha',
                'rules'  => 'required|min_length[2]|max_length[150]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'min_length' => '{field} minimal 2 karakter.',
                ],
            ],
            'business_type' => [
                'label'  => 'Jenis Bisnis',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                ],
            ],
            'password' => [
                'label'  => 'Password',
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'min_length' => '{field} minimal 6 karakter.',
                ],
            ],
            'pass_confirm' => [
                'label'  => 'Konfirmasi Password',
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'matches'  => '{field} tidak cocok dengan Password.',
                ],
            ],
        ];

        // Bersihkan akun pending yang kedaluwarsa (> 3 hari)
        $this->getAuthService()->cleanupExpiredUnverifiedUsers();

        // Jika pendaftar mencoba ulang dengan email/username yang masih berstatus pending, bersihkan terlebih dahulu agar validasi is_unique tidak gagal
        $emailInput    = strtolower(trim((string) $this->request->getPost('email')));
        $usernameInput = trim((string) $this->request->getPost('username'));
        if ($emailInput !== '' || $usernameInput !== '') {
            $db = \Config\Database::connect();
            $db->table('users')
                ->groupStart()
                    ->where('email', $emailInput)
                    ->orWhere('username', $usernameInput)
                ->groupEnd()
                ->where('status', 'pending')
                ->delete();
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $result = $this->getAuthService()->register([
            'name'             => (string) $this->request->getPost('name'),
            'username'         => (string) $this->request->getPost('username'),
            'email'            => (string) $this->request->getPost('email'),
            'phone'            => (string) $this->request->getPost('phone'),
            'business_name'    => (string) $this->request->getPost('business_name'),
            'business_type'    => (string) $this->request->getPost('business_type'),
            'business_address' => (string) $this->request->getPost('business_address'),
            'password'         => (string) $this->request->getPost('password'),
        ]);

        if (! $result) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data pendaftaran. Silakan coba lagi.');
        }

        session()->setFlashdata('pending_email', $result['email']);

        return redirect()->to('/verify-email?email=' . urlencode($result['email']))
            ->with('message', 'Pendaftaran berhasil! Akun sementara Anda telah dibuat. Silakan periksa kotak masuk atau spam email (' . esc($result['email']) . ') untuk kode verifikasi 6 digit. Akun akan otomatis dihapus permanen jika tidak diverifikasi dalam 3 hari.');
    }

    /**
     * Menampilkan halaman verifikasi email akun sementara (GET /verify-email).
     */
    public function verifyEmail()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        $email = (string) ($this->request->getGet('email') ?? session()->getFlashdata('pending_email') ?? '');
        $code  = (string) ($this->request->getGet('code') ?? '');

        return view('auth/verify_email', [
            'email' => $email,
            'code'  => $code,
        ]);
    }

    /**
     * Memproses kode verifikasi 6 digit (POST /verify-email).
     */
    public function processVerifyEmail()
    {
        $rules = [
            'email' => [
                'label'  => 'Email / Username',
                'rules'  => 'required',
                'errors' => ['required' => '{field} wajib diisi.'],
            ],
            'code' => [
                'label'  => 'Kode Verifikasi',
                'rules'  => 'required|numeric|exact_length[6]',
                'errors' => [
                    'required'     => '{field} 6 digit wajib diisi.',
                    'numeric'      => '{field} hanya boleh berupa angka.',
                    'exact_length' => '{field} harus tepat 6 digit angka.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = trim((string) $this->request->getPost('email'));
        $code  = trim((string) $this->request->getPost('code'));

        $result = $this->getAuthService()->verifyEmailCode($email, $code);

        switch ($result['status']) {
            case 'success':
                return redirect()->to('/login')
                    ->with('message', 'Verifikasi berhasil! Akun Anda telah aktif dan terdaftar secara permanen di sistem. Silakan login.');

            case 'already_active':
                return redirect()->to('/login')
                    ->with('message', 'Akun Anda sudah terverifikasi sebelumnya. Silakan login langsung.');

            case 'expired':
                return redirect()->to('/register')
                    ->with('error', 'Masa berlaku kode verifikasi telah habis (> 3 hari) dan akun sementara telah dihapus dari sistem. Silakan lakukan pendaftaran ulang.');

            case 'not_found':
                return redirect()->back()->withInput()
                    ->with('error', 'Akun tidak ditemukan atau pendaftaran sementara Anda telah dihapus.');

            case 'invalid_code':
            default:
                return redirect()->back()->withInput()
                    ->with('error', 'Kode verifikasi tidak cocok. Silakan periksa kembali email Anda.');
        }
    }

    /**
     * Mengirim ulang kode verifikasi ke email pengguna (POST /resend-verification).
     */
    public function resendVerification()
    {
        $email = trim((string) $this->request->getPost('email'));

        if ($email === '') {
            return redirect()->back()->with('error', 'Email tidak boleh kosong untuk mengirim ulang kode verifikasi.');
        }

        $result = $this->getAuthService()->resendVerificationCode($email);

        if ($result['status'] === 'success') {
            return redirect()->to('/verify-email?email=' . urlencode($email))
                ->with('message', $result['message']);
        }

        if ($result['status'] === 'already_active') {
            return redirect()->to('/login')->with('message', $result['message']);
        }

        if ($result['status'] === 'expired') {
            return redirect()->to('/register')->with('error', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }
}

