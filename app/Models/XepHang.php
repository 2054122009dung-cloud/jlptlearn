<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XepHang extends Model
{
    use HasFactory;

    protected $table = 'XepHang'; // Tên bảng trong database
    protected $primaryKey = 'MaBangXepHang'; // Khóa chính
    public $timestamps = false; // Không sử dụng timestamps mặc định của Laravel

    protected $fillable = [
        'XepHang',
        'NgayCapNhat',
        'MaBaiThi',
        'MaNguoiDung',
    ];

    protected $casts = [
        'NgayCapNhat' => 'datetime', // Chuyển đổi TIMESTAMP thành datetime trong Laravel
    ];

    // Quan hệ với bảng BaiThi
    public function baiThi()
    {
        return $this->belongsTo(BaiThi::class, 'MaBaiThi', 'MaBaiThi');
    }

    // Quan hệ với bảng NguoiDung
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }
}
