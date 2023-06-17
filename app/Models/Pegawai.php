<?php

namespace App\Models;

use CodeIgniter\Model;

class Pegawai extends Model
{
   
    protected $table            = 'pegawai';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['nama', 'jabatan', 'bidang', 'alamat', 'email', 'gambar'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
   
}
