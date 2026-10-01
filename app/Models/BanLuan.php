<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BanLuan extends Model
{
    use HasFactory;

    protected $table = 'BanLuan'; // Tên bảng
    protected $primaryKey = 'MaBanLuan'; // Khóa chính

    public $timestamps = false; // Vì cột thời gian không phải created_at và updated_at

    protected $fillable = [
        'MaCauHoi',
        'MaNguoiDung',
        'TieuDeBanLuan',
        'NgayTao',
    ];

    protected $casts = [
        'NgayTao' => 'datetime',
    ];

    // Quan hệ với bảng CauHoi (giả sử có bảng này)
    public function cauHoi()
    {
        return $this->belongsTo(CauHoi::class, 'MaCauHoi', 'MaCauHoi');
    }

    // Quan hệ với bảng NguoiDung (giả sử có bảng này)
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }
}
