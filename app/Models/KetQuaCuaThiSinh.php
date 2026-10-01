<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetQuaCuaThiSinh extends Model
{
    use HasFactory;

    protected $table = 'KetQuaCuaThiSinh'; // Tên bảng trong database

    protected $primaryKey = ['MaBaiThi', 'MaNguoiDung']; // Khóa chính là composite
    public $incrementing = false; // Không tự động tăng

    public $timestamps = false; // Không có created_at, updated_at

    protected $fillable = [
        'MaBaiThi',
        'MaNguoiDung',
        'DauHayRot',
        'NgayHoanThanh',
    ];
}
