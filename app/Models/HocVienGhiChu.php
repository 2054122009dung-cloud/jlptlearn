<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HocVienGhiChu extends Model
{
    use HasFactory;

    protected $table = 'HocVienGhiChu'; // Tên bảng trong database

    protected $primaryKey = ['MaNguoiDung', 'MaCauHoi']; // Khóa chính là composite
    public $incrementing = false; // Không tự động tăng (Vì dùng VARCHAR)

    public $timestamps = false; // Không có created_at, updated_at

    protected $fillable = [
        'MaNguoiDung',
        'MaCauHoi',
        'MaLaCo',
        'GhiChu',
        'ViTriLaCo',
    ];
}
