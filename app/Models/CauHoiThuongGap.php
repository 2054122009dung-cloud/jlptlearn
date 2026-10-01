<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauHoiThuongGap extends Model
{
    use HasFactory;

    protected $table = 'CauHoiThuongGap'; // Tên bảng

    protected $primaryKey = ['MaBanLuan', 'MaBinhLuan']; // Composite Key
    public $incrementing = false; // Không dùng auto-increment

    public $timestamps = false; // Không có created_at, updated_at

    protected $fillable = [
        'MaBanLuan',
        'MaBinhLuan',
        'MaNguoiDung',
        'NgayTao',
    ];

    protected $casts = [
        'NgayTao' => 'datetime',
    ];
}
