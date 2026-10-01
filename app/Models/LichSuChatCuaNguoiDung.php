<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichSuChatCuaNguoiDung extends Model
{
    use HasFactory;

    protected $table = 'LichSuChatCuaNguoiDung'; // Tên bảng trong database

    protected $primaryKey = ['MaNguoiDung', 'MaTinNhan']; // Khóa chính gồm 2 cột

    public $incrementing = false; // Không dùng auto-increment

    public $timestamps = false; // Không có created_at, updated_at

    protected $fillable = [
        'MaNguoiDung',
        'MaTinNhan',
    ];
}
