<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoiDungCauHoi extends Model
{
    use HasFactory;

    protected $table = 'NoiDungCauHoi'; // Tên bảng trong database
    protected $primaryKey = 'MaCauHoi'; // Khóa chính
    public $incrementing = false; // Không auto-increment vì dùng VARCHAR
    public $timestamps = false; // Không có timestamps

    protected $fillable = [
        'MaCauHoi',
        'CauHoi',
        'NguCanh',
        'HinhAnh',
        'AmThanh',
    ];
}
