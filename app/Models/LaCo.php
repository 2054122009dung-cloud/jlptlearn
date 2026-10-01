<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaCo extends Model
{
    use HasFactory;

    protected $table = 'LaCo'; // Tên bảng trong database

    protected $primaryKey = 'MaLaCo'; // Khóa chính

    public $incrementing = false; // Không tự động tăng (vì là VARCHAR)

    public $timestamps = false; // Không có created_at, updated_at

    protected $fillable = [
        'MaLaCo',
        'TenLaCo',
    ];
}
