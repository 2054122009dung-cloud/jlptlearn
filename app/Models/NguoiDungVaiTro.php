<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class NguoiDungVaiTro extends Model
{
    use HasFactory;

    protected $table = 'NguoiDungVaiTro'; // Tên bảng trong database
    protected $primaryKey = ['MaNguoiDung', 'MaVaiTro']; // Khóa chính là composite key
    public $incrementing = false; // Tắt auto-increment vì dùng composite key
    public $timestamps = false; // Không có cột timestamps

    protected $fillable = [
        'MaNguoiDung',
        'MaVaiTro',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    public function vaiTros()
    {
        return $this->belongsToMany(VaiTro::class, 'NguoiDungVaiTro', 'MaNguoiDung', 'MaVaiTro');
    }

}
