<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TraLoi extends Model
{
    use HasFactory;

    protected $table = 'TraLoi'; // Tên bảng trong database
    protected $primaryKey = 'MaAnswer'; // Khóa chính
    public $incrementing = false; // Không auto-increment vì dùng VARCHAR

    protected $fillable = [
        'MaTraLoi', 'MaPhieu', 'MaBaiThi', 'CauTraLoi', 'LaChinhXac', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'LaChinhXac' => 'boolean', // Chuyển đổi TINYINT thành boolean trong Laravel
    ];

    // Quan hệ với bảng PhieuTraLoi
    public function phieuTraLoi()
    {
        return $this->belongsTo(PhieuTraLoi::class, 'MaSheet', 'MaSheet');
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->MaAnswer = (string) Str::uuid();
        });
    }
}
