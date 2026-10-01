<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauHoi extends Model
{
    use HasFactory;

    protected $table = 'CauHoi'; // Tên bảng
    protected $primaryKey = 'MaCauHoi'; // Khóa chính
    public $incrementing = false; // Vì MaCauHoi là VARCHAR

    public $timestamps = false; // Không có created_at, updated_at

    protected $fillable = [
        'MaCauHoi',
        'LoaiCauHoi',
        'NgayTao',
    ];

    protected $casts = [
        'NgayTao' => 'datetime',
    ];
}
