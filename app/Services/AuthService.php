<?php

namespace App\Services;

use App\Models\UserModel;

class AuthService
{
    protected UserModel $userModel;

    public function __construct(?UserModel $userModel = null)
    {
        $this->userModel = $userModel ?? new UserModel();
    }

    /**
     * Memvalidasi kredensial login (username/email dan password).
     *
     * Catatan: Service ini TIDAK menyentuh Session, Redirect, View, atau HTTP Response,
     * sehingga dapat digunakan kembali oleh Web Controller maupun API Mobile.
     *
     * @param string $login Username atau Email
     * @param string $password Password plaintext
     * @return array|null Mengembalikan data user (tanpa password) jika valid, atau null jika gagal
     */
    /**
     * Menghapus akun temporary (status 'pending') yang telah melebihi batas waktu 3 hari
     * agar tidak memenuhi database dengan akun sampah yang tidak diverifikasi.
     *
     * @return int Jumlah baris yang dihapus
     */
    public function cleanupExpiredUnverifiedUsers(): int
    {
        $now = date('Y-m-d H:i:s');
        $db = \Config\Database::connect();
        $builder = $db->table('users');

        $builder->where('status', 'pending')
                ->where('verification_expires_at <', $now);

        $builder->delete();

        return (int) $db->affectedRows();
    }

    /**
     * Memvalidasi kredensial login (username/email dan password).
     *
     * @param string $login Username atau Email
     * @param string $password Password plaintext
     * @return array|null Mengembalikan data user jika aktif, ['is_pending' => true, ...] jika belum verifikasi, atau null jika gagal
     */
    public function authenticate(string $login, string $password): ?array
    {
        // Bersihkan user temporary yang sudah expired lebih dari 3 hari
        $this->cleanupExpiredUnverifiedUsers();

        $login = trim($login);

        if ($login === '' || $password === '') {
            return null;
        }

        // Cari user berdasarkan username atau email
        $user = $this->userModel->findByUsernameOrEmail($login);

        if (! $user) {
            return null;
        }

        // Verifikasi kecocokan password menggunakan password_verify
        if (empty($user['password']) || ! password_verify($password, $user['password'])) {
            return null;
        }

        // Cek jika akun berstatus pending (belum verifikasi kode email)
        if (isset($user['status']) && strtolower((string) $user['status']) === 'pending') {
            // Cek apakah kode verifikasi sudah kedaluwarsa 3 hari
            if (! empty($user['verification_expires_at']) && strtotime($user['verification_expires_at']) < time()) {
                $this->userModel->delete($user['id']);
                return null;
            }

            return [
                'is_pending' => true,
                'email'      => $user['email'],
                'username'   => $user['username'],
                'name'       => $user['name'] ?? $user['username'],
            ];
        }

        // Cek status user: hanya user dengan status 'active' yang boleh login
        if (isset($user['status']) && strtolower((string) $user['status']) !== 'active') {
            return null;
        }

        // Hapus password hash dari data user demi keamanan
        unset($user['password']);

        return $user;
    }

    /**
     * Mendaftarkan pengguna dan usaha baru ke dalam sistem sebagai akun temporary (pending).
     * Akun akan kedaluwarsa dan dihapus jika tidak diverifikasi dalam 3 hari.
     *
     * @param array $data
     * @return array|false Informasi pendaftaran baru atau false jika gagal
     */
    public function register(array $data)
    {
        // Bersihkan akun pending yang expired > 3 hari
        $this->cleanupExpiredUnverifiedUsers();

        $email    = strtolower(trim($data['email'] ?? ''));
        $username = trim($data['username'] ?? '');

        // Jika ada akun temporary lama yang belum verifikasi dengan email/username ini, hapus dulu agar bisa mendaftar ulang
        $oldPending = $this->userModel
            ->groupStart()
                ->where('email', $email)
                ->orWhere('username', $username)
            ->groupEnd()
            ->where('status', 'pending')
            ->first();

        if ($oldPending) {
            $this->userModel->delete($oldPending['id']);
        }

        $verificationCode = (string) random_int(100000, 999999);
        $expiresAt        = date('Y-m-d H:i:s', strtotime('+3 days'));

        $userData = [
            'name'                    => trim($data['name'] ?? ''),
            'username'                => $username,
            'email'                   => $email,
            'phone'                   => ! empty($data['phone']) ? trim($data['phone']) : null,
            'password'                => password_hash($data['password'], PASSWORD_DEFAULT),
            'business_name'           => trim($data['business_name'] ?? ''),
            'business_type'           => trim($data['business_type'] ?? ''),
            'business_address'        => ! empty($data['business_address']) ? trim($data['business_address']) : null,
            'status'                  => 'pending',
            'verification_code'       => $verificationCode,
            'verification_expires_at' => $expiresAt,
            'email_verified_at'       => null,
        ];

        $userId = $this->userModel->insert($userData);
        if (! $userId) {
            return false;
        }

        // Kirim email kode verifikasi
        $this->sendVerificationEmail($email, $userData['name'], $verificationCode, $expiresAt);

        return [
            'user_id'           => $userId,
            'email'             => $email,
            'verification_code' => $verificationCode,
            'expires_at'        => $expiresAt,
        ];
    }

    /**
     * Mengirimkan kode verifikasi akun ke email pengguna.
     *
     * @param string $email
     * @param string $name
     * @param string $code
     * @param string $expiresAt
     * @return bool
     */
    public function sendVerificationEmail(string $email, string $name, string $code, string $expiresAt): bool
    {
        $emailService = \Config\Services::email();

        $expiresTimestamp = strtotime($expiresAt);
        $expiresFormatted = date('d M Y, H:i', $expiresTimestamp) . ' WIB';
        $verifyUrl        = base_url('verify-email?email=' . urlencode($email) . '&code=' . urlencode($code));

        $body = view('emails/verification_code', [
            'name'             => $name,
            'code'             => $code,
            'verifyUrl'        => $verifyUrl,
            'expiresFormatted' => $expiresFormatted,
        ]);

        $emailService->setTo($email);
        $emailService->setSubject('Kode Verifikasi Akun MDV POS: ' . $code);
        $emailService->setMessage($body);

        if (! $emailService->send()) {
            log_message('error', 'Gagal mengirim email verifikasi ke ' . $email . ': ' . $emailService->printDebugger(['headers']));
            return false;
        }

        log_message('info', 'Email verifikasi berhasil dikirim ke ' . $email . ' dengan kode: ' . $code);
        return true;
    }

    /**
     * Memverifikasi kode verifikasi yang dimasukkan pengguna.
     * Jika valid, akun diaktifkan (status = 'active').
     * Jika kode kedaluwarsa (> 3 hari), akun dihapus dari database.
     *
     * @param string $emailOrLogin
     * @param string $code
     * @return array ['status' => 'success'|'invalid_code'|'expired'|'not_found'|'already_active', 'user' => ?array]
     */
    public function verifyEmailCode(string $emailOrLogin, string $code): array
    {
        $this->cleanupExpiredUnverifiedUsers();

        $emailOrLogin = trim($emailOrLogin);
        $code         = trim($code);

        if ($emailOrLogin === '' || $code === '') {
            return ['status' => 'invalid_code', 'user' => null];
        }

        $user = $this->userModel
            ->groupStart()
                ->where('email', $emailOrLogin)
                ->orWhere('username', $emailOrLogin)
            ->groupEnd()
            ->first();

        if (! $user) {
            return ['status' => 'not_found', 'user' => null];
        }

        // Jika sudah aktif sebelumnya
        if (isset($user['status']) && strtolower((string) $user['status']) === 'active') {
            return ['status' => 'already_active', 'user' => $user];
        }

        // Cek masa berlaku 3 hari
        if (! empty($user['verification_expires_at']) && strtotime($user['verification_expires_at']) < time()) {
            $this->userModel->delete($user['id']);
            return ['status' => 'expired', 'user' => null];
        }

        // Cek kecocokan kode
        if ((string) $user['verification_code'] !== $code) {
            return ['status' => 'invalid_code', 'user' => null];
        }

        // Kode valid: aktifkan akun user
        $this->userModel->update($user['id'], [
            'status'                  => 'active',
            'verification_code'       => null,
            'verification_expires_at' => null,
            'email_verified_at'       => date('Y-m-d H:i:s'),
        ]);

        $verifiedUser = $this->userModel->find($user['id']);
        unset($verifiedUser['password'], $verifiedUser['verification_code']);

        return ['status' => 'success', 'user' => $verifiedUser];
    }

    /**
     * Mengirim ulang kode verifikasi ke email pengguna.
     *
     * @param string $emailOrLogin
     * @return array ['status' => 'success'|'not_found'|'expired'|'already_active', 'message' => string]
     */
    public function resendVerificationCode(string $emailOrLogin): array
    {
        $this->cleanupExpiredUnverifiedUsers();

        $emailOrLogin = trim($emailOrLogin);
        if ($emailOrLogin === '') {
            return ['status' => 'not_found', 'message' => 'Email atau username wajib diisi.'];
        }

        $user = $this->userModel
            ->groupStart()
                ->where('email', $emailOrLogin)
                ->orWhere('username', $emailOrLogin)
            ->groupEnd()
            ->first();

        if (! $user) {
            return ['status' => 'not_found', 'message' => 'Akun tidak ditemukan atau pendaftaran telah kedaluwarsa.'];
        }

        if (isset($user['status']) && strtolower((string) $user['status']) === 'active') {
            return ['status' => 'already_active', 'message' => 'Akun Anda sudah terverifikasi dan aktif. Silakan masuk.'];
        }

        // Cek jika sudah kedaluwarsa
        if (! empty($user['verification_expires_at']) && strtotime($user['verification_expires_at']) < time()) {
            $this->userModel->delete($user['id']);
            return ['status' => 'expired', 'message' => 'Pendaftaran akun telah melewati batas 3 hari dan telah dihapus. Silakan daftar kembali.'];
        }

        // Buat kode baru dan perbarui masa kedaluwarsa (3 hari dari sekarang)
        $newCode   = (string) random_int(100000, 999999);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+3 days'));

        $this->userModel->update($user['id'], [
            'verification_code'       => $newCode,
            'verification_expires_at' => $expiresAt,
        ]);

        $this->sendVerificationEmail($user['email'], $user['name'] ?? $user['username'], $newCode, $expiresAt);

        return ['status' => 'success', 'message' => 'Kode verifikasi baru berhasil dikirim ke ' . $user['email']];
    }

    /**
     * Membuat dan menyimpan token remember-me untuk user tertentu.
     * Mengembalikan string token yang siap disimpan pada cookie.
     *
     * @param int $userId
     * @return string format "{userId}:{rawToken}"
     */
    public function setRememberToken(int $userId): string
    {
        $token = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $token);

        $this->userModel->update($userId, [
            'remember_token' => $hashedToken,
        ]);

        return $userId . ':' . $token;
    }

    /**
     * Memvalidasi token remember-me dari cookie.
     * Mengembalikan data user (tanpa password & token) jika valid, atau null jika tidak valid.
     *
     * @param string $cookieValue
     * @return array|null
     */
    public function validateRememberToken(string $cookieValue): ?array
    {
        $cookieValue = urldecode($cookieValue);

        if (! str_contains($cookieValue, ':')) {
            return null;
        }

        [$userId, $token] = explode(':', $cookieValue, 2);
        $userId = (int) $userId;

        if ($userId <= 0 || empty($token)) {
            return null;
        }

        $user = $this->userModel->find($userId);

        if (! $user) {
            return null;
        }

        // Cek status user aktif
        if (isset($user['status']) && strtolower((string) $user['status']) !== 'active') {
            return null;
        }

        if (empty($user['remember_token'])) {
            return null;
        }

        // Timing-safe comparison token
        if (! hash_equals($user['remember_token'], hash('sha256', $token))) {
            return null;
        }

        unset($user['password'], $user['remember_token']);

        return $user;
    }

    /**
     * Menghapus remember token dari database saat user logout.
     *
     * @param int $userId
     * @return void
     */
    public function clearRememberToken(int $userId): void
    {
        if ($userId > 0) {
            $this->userModel->update($userId, [
                'remember_token' => null,
            ]);
        }
    }

    /**
     * Membuat token reset password untuk user berdasarkan username atau email.
     * Mengembalikan data user, raw token, dan waktu kedaluwarsa jika user ditemukan.
     *
     * @param string $identity Username atau Email
     * @return array|null ['user' => array, 'token' => string, 'expires_at' => string]
     */
    public function generatePasswordReset(string $identity): ?array
    {
        $user = $this->userModel->findByUsernameOrEmail($identity);

        if (! $user) {
            return null;
        }

        if (isset($user['status']) && strtolower((string) $user['status']) !== 'active') {
            return null;
        }

        $rawToken     = bin2hex(random_bytes(32));
        $hashedToken  = hash('sha256', $rawToken);
        $expiresAt    = date('Y-m-d H:i:s', time() + 3600); // Berlaku 1 jam

        // Simpan ke tabel password_resets
        $db = \Config\Database::connect();
        $db->table('password_resets')->insert([
            'email'      => $user['email'],
            'token'      => $hashedToken,
            'expires_at' => $expiresAt,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Simpan juga ke tabel users sebagai fallback
        $this->userModel->update($user['id'], [
            'reset_token'      => $hashedToken,
            'reset_expires_at' => $expiresAt,
        ]);

        return [
            'user'       => $user,
            'token'      => $rawToken,
            'expires_at' => $expiresAt,
        ];
    }

    /**
     * Memvalidasi apakah raw reset token valid dan belum kedaluwarsa.
     *
     * @param string $rawToken
     * @return array|null data user jika valid, null jika tidak valid/kedaluwarsa
     */
    public function validateResetToken(string $rawToken): ?array
    {
        $rawToken = trim(urldecode($rawToken));
        if (empty($rawToken)) {
            return null;
        }

        $hashedToken = hash('sha256', $rawToken);
        $currentTime = date('Y-m-d H:i:s');
        $db          = \Config\Database::connect();

        // 1. Cek pada tabel password_resets
        $resetRow = $db->table('password_resets')
            ->where('token', $hashedToken)
            ->where('expires_at >=', $currentTime)
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();

        if ($resetRow && ! empty($resetRow['email'])) {
            $user = $this->userModel->where('email', $resetRow['email'])->first();
            if ($user && (! isset($user['status']) || strtolower((string) $user['status']) === 'active')) {
                return $user;
            }
        }

        // 2. Fallback: Cek pada tabel users
        $user = $this->userModel
            ->where('reset_token', $hashedToken)
            ->where('reset_expires_at >=', $currentTime)
            ->first();

        if (! $user) {
            return null;
        }

        if (isset($user['status']) && strtolower((string) $user['status']) !== 'active') {
            return null;
        }

        return $user;
    }

    /**
     * Memperbarui password user dan menghapus token reset password.
     *
     * @param string $rawToken
     * @param string $newPassword
     * @return bool
     */
    public function resetPassword(string $rawToken, string $newPassword): bool
    {
        $user = $this->validateResetToken($rawToken);

        if (! $user) {
            return false;
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update user
        $updated = (bool) $this->userModel->update($user['id'], [
            'password'         => $hashedPassword,
            'reset_token'      => null,
            'reset_expires_at' => null,
            'remember_token'   => null,
        ]);

        // Bersihkan seluruh token reset untuk email user ini
        $db = \Config\Database::connect();
        $db->table('password_resets')->where('email', $user['email'])->delete();

        return $updated;
    }
}
