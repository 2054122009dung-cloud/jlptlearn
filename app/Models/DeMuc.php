<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeMuc extends Model
{
    use HasFactory;

    protected $table = 'DeMuc'; // Tên bảng trong database

    protected $primaryKey = 'MaDeMuc'; // Khóa chính
    public $incrementing = false; // Không tự động tăng (Vì kiểu VARCHAR)

    public $timestamps = false; // Không có created_at, updated_at

    protected $fillable = [
        'MaDeMuc',
        'MaBaiThi',
        'TieuDeBai',
    ];
}
