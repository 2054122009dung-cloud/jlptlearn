<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatBotLichSu extends Model
{
    use HasFactory;

    protected $table = 'ChatBotLichSu'; // Tên bảng trong database

    protected $primaryKey = 'MaTinNhan'; // Khóa chính
    public $incrementing = true; // INT với AI sẽ tự động tăng

    public $timestamps = false; // Không có created_at, updated_at

    protected $fillable = [
        'LoaiTinNhan',
        'NoiDung',
        'ThoiGian',
    ];

    protected $casts = [
        'ThoiGian' => 'datetime',
    ];
}
