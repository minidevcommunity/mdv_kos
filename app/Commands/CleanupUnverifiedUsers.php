<?php

namespace App\Commands;

use App\Services\AuthService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CleanupUnverifiedUsers extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Users';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'user:cleanup-unverified';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Menghapus akun temporary (pending) yang belum diverifikasi dan telah melebihi batas waktu 3 hari.';

    /**
     * Actually run the command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Menjalankan pembersihan akun temporary yang kedaluwarsa (> 3 hari)...', 'yellow');

        $authService = new AuthService();
        $deleted = $authService->cleanupExpiredUnverifiedUsers();

        CLI::write("Selesai. Sebanyak {$deleted} akun temporary berhasil dibersihkan dari database.", 'green');
    }
}
