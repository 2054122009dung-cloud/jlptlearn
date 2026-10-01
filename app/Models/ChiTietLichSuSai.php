<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietLichSuSai extends Model
{
    use HasFactory;

    protected $table = 'ChiTietLichSuSai'; // Tên bảng trong database

    protected $primaryKey = ['MaLoi', 'MaCauHoi', 'MaBaiThi']; // Khóa chính kép
    public $incrementing = false; // Vì là khóa chính kép nên không tự động tăng

    public $timestamps = false; // Không có created_at, updated_at

    protected $fillable = [
        'MaLoi',
        'MaCauHoi',
        'MaBaiThi',
        'DauHayRot',
    ];
}
