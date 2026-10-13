<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'email',
        'password',
        'name',
        'phone',
        'business_name',
        'business_type',
        'business_address',
        'status',
        'verification_code',
        'verification_expires_at',
        'email_verified_at',
        'remember_token',
        'reset_token',
        'reset_expires_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Cari user berdasarkan username atau email dengan parameter binding (Query Builder)
     *
     * @param string $login Username atau Email
     * @return array|null
     */
    public function findByUsernameOrEmail(string $login): ?array
    {
        return $this->groupStart()
            ->where('username', $login)
            ->orWhere('email', $login)
            ->groupEnd()
            ->first();
    }
}
