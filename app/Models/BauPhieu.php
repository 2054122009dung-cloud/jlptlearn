<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BauPhieu extends Model
{
    use HasFactory;

    protected $table = 'BauPhieu'; // Tên bảng
    protected $primaryKey = 'MaBauPhieu'; // Khóa chính

    public $timestamps = false; // Không có created_at và updated_at

    protected $fillable = [
        'MaBinhLuan',
        'MaNguoiDung',
        'LoaiPhieu',
    ];

    protected $casts = [
        'LoaiPhieu' => 'string', // ENUM sẽ được lưu dưới dạng string
    ];

    // Quan hệ với bảng BanLuan
    public function banLuan()
    {
        return $this->belongsTo(BanLuan::class, 'MaBinhLuan', 'MaBanLuan');
    }

    // Quan hệ với bảng NguoiDung (giả sử có bảng này)
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }
}
