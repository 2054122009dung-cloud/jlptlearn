<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiThiCuaThiSinh extends Model
{
    use HasFactory;

    protected $table = 'BaiThiCuaThiSinh'; // Tên bảng
    protected $primaryKey = ['MaBaiThi', 'MaNguoiDung']; // Khóa chính có nhiều cột

    public $incrementing = false; // Không tự tăng vì dùng VARCHAR
    protected $keyType = 'string'; // Kiểu dữ liệu khóa chính

    protected $fillable = [
        'MaBaiThi',
        'MaNguoiDung',
        'TongCauDung',
        'Diem',
    ];

    protected $casts = [
        'TongCauDung' => 'integer',
        'Diem' => 'decimal:2',
    ];

    // Khai báo quan hệ với bảng BaiThi
    public function baiThi()
    {
        return $this->belongsTo(BaiThi::class, 'MaBaiThi', 'MaBaiThi');
    }

    // Khai báo quan hệ với bảng NguoiDung (giả sử có bảng này)
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }
}
