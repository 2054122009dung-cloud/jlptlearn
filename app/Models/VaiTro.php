<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class VaiTro extends SpatieRole
{
    // Use your custom table name and primary key
    protected $table = 'VaiTro';
    protected $primaryKey = 'MaVaiTro';
    public $incrementing = false; // Because MaVaiTro is a string (e.g., 'ADMIN')
    public $timestamps = false;   // If your table has no timestamp columns

    protected $fillable = [
        'MaVaiTro',
        'TenVaiTro',
        'guard_name', // Thêm dòng này
    ];


    /**
     * Spatie expects a "name" attribute on the Role model.
     * We map it to your "TenVaiTro" column using accessors/mutators.
     */
    public function getNameAttribute()
    {
        return $this->TenVaiTro;
    }

    public function setNameAttribute($value)
    {
        $this->TenVaiTro = $value;
    }

    /**
     * If you don't have a "guard_name" column in your table,
     * we can return a default value here.
     */
    public function getGuardNameAttribute()
    {
        // If you have a 'guard_name' column, you can return $this->attributes['guard_name'];
        return 'web';
    }

    /**
     * Define the belongsToMany relationship with your NguoiDung model.
     * This acts as the pivot for model_has_roles.
     */
    public function nguoiDungs()
    {
        return $this->belongsToMany(
            NguoiDung::class,    // The related model
            'nguoidungvaitro',   // The pivot table name
            'MaVaiTro',          // Foreign key on pivot table for this model
            'MaNguoiDung'        // Foreign key on pivot table for the related model
        );
    }
}
