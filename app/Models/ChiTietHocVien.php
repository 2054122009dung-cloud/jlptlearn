<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietHocVien extends Model
{
    use HasFactory;

    protected $table = 'ChiTietHocVien'; // Tên bảng trong database

    protected $primaryKey = ['MaNguoiDung', 'MaLoi']; // Khóa chính kép
    public $incrementing = false; // Vì là khóa chính kép nên không tự động tăng

    public $timestamps = false; // Không có created_at, updated_at

    protected $fillable = [
        'MaNguoiDung',
        'MaLoi',
        'CapDo',
    ];
}
