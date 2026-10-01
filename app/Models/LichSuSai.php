<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichSuSai extends Model
{
    use HasFactory;

    protected $table = 'LichSuSai';
    protected $primaryKey = 'MaLoi';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'MaLoi',
        'MaNguoiDung',
        'SoLoiSai',
        'LanLamBaiCuoi',
        'DapAnSai',
        'DapAnDung',

    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }
    public static function generateMaLoi()
    {
        // Lấy số lớn nhất hiện có
        $soCuoi = self::max(\DB::raw("CAST(SUBSTRING(MaLoi, 2, LENGTH(MaLoi) - 1) AS UNSIGNED)"));
        return '咎' . ($soCuoi + 1);
    }
        public function setAnswers($question, $questionIndex, $answers)
    {
        $wrongAnswer = $answers[$questionIndex];
        $correctAnswer = $question['dap_an_dung'];
        // Thực hiện logic bạn cần với $wrongAnswer và $correctAnswer
    }
}
