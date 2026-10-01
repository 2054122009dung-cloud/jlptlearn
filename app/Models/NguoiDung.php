<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class NguoiDung extends Authenticatable
{
    use HasRoles;

    use HasFactory, Notifiable;

    protected $table = 'NguoiDung'; // Tên bảng
    protected $primaryKey = 'MaNguoiDung'; // Khóa chính
    protected $guard_name = 'web';
    public $incrementing = false; // MaNguoiDung không phải auto-increment
    public $timestamps = true; // Laravel mặc định dùng `created_at` và `updated_at`

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'MaNguoiDung',
        'username',
        'email',
        'password',
        'create_at',
        'updated_at',
    ];

    protected $hidden = [
        'password',
    ];

    public function getAuthIdentifierName()
    {
        return 'email'; // Cột dùng để xác thực
    }

    public function getAuthPassword()
    {
        return $this->password; // Trả về giá trị mật khẩu đã hash
    }

}
