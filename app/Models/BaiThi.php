<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiThi extends Model
{
    use HasFactory;

    protected $table = 'BaiThi'; // Tên bảng trong database
    protected $primaryKey = 'MaBaiThi'; // Khóa chính

    public $incrementing = false; // Vì khóa chính là VARCHAR, không tự tăng
    protected $keyType = 'string'; // Định dạng khóa chính

    protected $fillable = [
        'MaBaiThi',
        'CapDo',
        'TiLeDo',
    ];

    protected $casts = [
        'CapDo' => 'string', // Laravel không hỗ trợ ENUM nên dùng string
        'TiLeDo' => 'float',
    ];
}
