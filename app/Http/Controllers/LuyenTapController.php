<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LuyenTapController extends Controller
{
    public function index()
    {
        return view('student.luyenthi'); // Trả về giao diện luyenthi.blade.php
    }
    public function showAddQuestionForm()
    {
        return view('student.taocauhoi'); // Giao diện thêm câu hỏi
    }

    public function saveQuestion(Request $request)
    {
        $filePath = storage_path('app/user/quiz/cauhoi.json');

        // Đọc dữ liệu cũ nếu có
        if (file_exists($filePath)) {
            $jsonData = json_decode(file_get_contents($filePath), true);
        } else {
            $jsonData = [];
        }

        // Thêm câu hỏi mới
        $questionData = [
            'ma_cau_hoi' => time(),
            'loai' => 'ngon_ngu_ki_nang',
            'de_bai' => $request->input('de_bai'),
            'dap_an' => $request->input('dap_an'),
            'dap_an_dung' => $request->input('dap_an_dung')
        ];
        $jsonData[] = $questionData;

        // Ghi lại file
        file_put_contents($filePath, json_encode($jsonData, JSON_PRETTY_PRINT));

        return response()->json(['message' => 'Câu hỏi đã được lưu thành công!']);
    }
    public function manageQuestions(Request $request)
    {
        // Lấy danh sách câu hỏi từ session
        $questions = session('questions', []);

        // Nếu request là POST, xử lý việc lưu câu hỏi
        if ($request->isMethod('post')) {
            $newQuestion = [
                "ma_cau_hoi" => $request->ma_cau_hoi,
                "loai" => "ngon_ngu_ki_nang",
                "de_bai" => $request->de_bai,
                "dap_an" => [
                    ["id" => 1, "noi_dung" => $request->dap_an_1],
                    ["id" => 2, "noi_dung" => $request->dap_an_2],
                    ["id" => 3, "noi_dung" => $request->dap_an_3],
                    ["id" => 4, "noi_dung" => $request->dap_an_4],
                ],
                "dap_an_dung" => (int) $request->dap_an_dung,
            ];

            $questions[] = $newQuestion;
            session(['questions' => $questions]);

            return redirect()->route('taocauhoi')->with('success', 'Câu hỏi đã được thêm!');
        }

        // Nếu có request tải file
        if ($request->has('download')) {
            $jsonData = json_encode([
                "de_thi" => "quiz từ vựng",
                "cau_hoi" => $questions
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $fileName = "quiz_data.json";

            return response($jsonData)
                ->header('Content-Type', 'application/json')
                ->header('Content-Disposition', "attachment; filename={$fileName}");
        }

        return view('student.taocauhoi', compact('questions'));
    }

}
