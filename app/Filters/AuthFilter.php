<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Memeriksa apakah user sudah login sebelum mengakses route yang diproteksi.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('isLoggedIn')) {
            return;
        }

        // Cek cookie remember_token untuk auto-login jika session sudah berakhir
        helper('cookie');
        $rememberCookie = get_cookie('remember_token');
        if ($rememberCookie) {
            $authService = new \App\Services\AuthService();
            $user = $authService->validateRememberToken($rememberCookie);

            if ($user) {
                session()->regenerate();
                session()->set([
                    'user_id'    => $user['id'],
                    'username'   => $user['username'],
                    'email'      => $user['email'],
                    'name'       => $user['name'] ?? $user['username'],
                    'isLoggedIn' => true,
                ]);

                return;
            }

            // Hapus cookie jika token tidak valid
            return redirect()->to('/login')
                ->deleteCookie('remember_token')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
    }

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi setelah request
    }
}
