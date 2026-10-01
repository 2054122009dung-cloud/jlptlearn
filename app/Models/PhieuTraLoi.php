<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhieuTraLoi extends Model
{
    use HasFactory;

    protected $table = 'PhieuTraLoi'; // Tên bảng trong database
    protected $primaryKey = 'MaSheet'; // Khóa chính
    public $incrementing = false; // Không auto-increment vì dùng VARCHAR
    public $timestamps = false; // Không có timestamps

    protected $fillable = [
        'MaSheet',
        'MaNguoiDung',
        'MaBaiThi',
        'NgayThangThi',
        'SoBaoDanh',
    ];

    protected $casts = [
        'NgayThangThi' => 'datetime',
    ];
     public function traLoi()
{
    return $this->hasMany(TraLoi::class, 'MaSheet', 'MaSheet');
}


}
