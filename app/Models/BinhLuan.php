<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinhLuan extends Model
{
    use HasFactory;

    protected $table = 'BinhLuan'; // Tên bảng
    protected $primaryKey = 'MaBinhLuan'; // Khóa chính

    public $timestamps = false; // Không có created_at và updated_at

    protected $fillable = [
        'MaBanLuan',
        'MaNguoiDung',
        'NoiDung',
        'NgayTao',
    ];

    protected $casts = [
        'NgayTao' => 'datetime',
    ];

    // Quan hệ với bảng BanLuan
    public function banLuan()
    {
        return $this->belongsTo(BanLuan::class, 'MaBanLuan', 'MaBanLuan');
    }

    // Quan hệ với bảng NguoiDung (giả sử có bảng này)
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    // Quan hệ với bảng BauPhieu (bình luận có thể có nhiều phiếu bầu)
    public function bauPhieu()
    {
        return $this->hasMany(BauPhieu::class, 'MaBinhLuan', 'MaBinhLuan');
    }
}
