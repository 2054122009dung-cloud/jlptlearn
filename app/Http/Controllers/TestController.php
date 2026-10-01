<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function showLevelTests($level)
    {
        // Kiểm tra nếu cấp độ không hợp lệ
        $validLevels = ['n1', 'n2', 'n3', 'n4', 'n5'];
        if (!in_array($level, $validLevels)) {
            abort(404, 'Không tìm thấy cấp độ.');
        }

        // Đường dẫn file JSON
        $jsonPath = public_path("dethi_{$level}/test.json");

        if (!file_exists($jsonPath)) {
            abort(404, "Không tìm thấy file đề thi cho cấp độ {$level}.");
        }

        // Đọc file JSON
        $jsonData = file_get_contents($jsonPath);
        $tests = json_decode($jsonData, true);

        // Kiểm tra lỗi JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            abort(500, 'Lỗi giải mã JSON: ' . json_last_error_msg());
        }

        // Load view theo cấp độ
        return view("{$level}.index", compact('tests', 'level'));
    }
    public function qlBaithi()
    {
        $jsonPath = public_path('dethi_n1/test.json');
        $jsonData = file_get_contents($jsonPath);
        $tests = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            abort(500, 'Lỗi giải mã JSON: ' . json_last_error_msg());
        }

        return view('admin.qlbaithi', compact('tests'));
    }

}
