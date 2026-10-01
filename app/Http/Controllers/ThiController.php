<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\NguoiDung;
use App\Models\BaiThiCuaThiSinh;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
class ThiController extends Controller
{
        /**
     * Display the teacher's exam creation view.
     *
     * @return \Illuminate\View\View
     */
    public function taobaithi()
    {
        return view('teacher.taobaithi');
    }

    /**
     * Xử lý tạo bài thi và lưu file PDF.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    // Hiển thị bài thi
    public function showExam($level, $month, $year)
    {
        $user = auth()->user();
        $maPhieu = Str::random(5); // Tạo mã phiếu mới với 5 ký tự ngẫu nhiên
        $maBaiThi = strtoupper($level) . "_{$year}_{$month}"; // Mã bài thi, e.g., "N2_2024_07"
        $filePath = asset("pdf/{$level}_{$month}_{$year}.pdf"); // Đường dẫn file PDF

        return view('pdf-view', compact('filePath', 'month', 'year', 'maPhieu', 'maBaiThi'));
    }



    // Lưu bài làm
    public function nopBai(Request $request)
    {
        $data = $request->validate([
            'MaPhieu'  => 'required|string',
            'MaBaiThi' => 'required|string',
            'answers'  => 'required|array',
        ]);

        $maPhieu    = $data['MaPhieu'];
        $maBaiThi   = $data['MaBaiThi']; // e.g. "N1_2015_07"
        $userAnswers = $data['answers'];

        // Extract exam level and generate SoBaoDanh
        $examLevel = strtoupper(substr($maBaiThi, 0, 2)); // "N1", "N2", etc.
        $randomNumber = rand(1000, 9999);
        $soBaoDanh = $examLevel . $randomNumber; // e.g. "N14785"

        // Ensure the parent record in 'phieutraloi' exists
        DB::table('phieutraloi')->updateOrInsert(
            ['MaSheet' => $maPhieu],
            [
                'MaNguoiDung'  => auth()->user()->MaNguoiDung,
                'MaBaiThi'     => $maBaiThi,
                'NgayThangThi' => now(),
                'SoBaoDanh'    => $soBaoDanh,
            ]
        );

        // (Optional) Calculate exam score
        $dapAnDung = $this->getDapAnDung($maBaiThi);
        $soCauDung = count(array_intersect_assoc($userAnswers, $dapAnDung));
        $tongCauDung =$soCauDung;
        $diem = round(($soCauDung / 70) * 100, 2);

        // Cập nhật/insert vào bảng baithicuathisinh
        $this->updateOrInsertBaiThi($maBaiThi, auth()->user()->MaNguoiDung, $soCauDung, $diem);

        echo json_encode($userAnswers, JSON_PRETTY_PRINT);
        echo json_encode($dapAnDung, JSON_PRETTY_PRINT);
        echo json_encode($soCauDung, JSON_PRETTY_PRINT);

        // Insert/update the child record in 'traloi'
        DB::table('traloi')->updateOrInsert(
            ['MaPhieu' => $maPhieu, 'MaBaiThi' => $maBaiThi],
            [
                'MaTraLoi'   => Str::uuid(),
                'CauTraLoi'  => json_encode($userAnswers),
                'LaChinhXac' => $soCauDung,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $record = $this->updateOrInsertBaiThi($maBaiThi, auth()->user()->MaNguoiDung, $soCauDung, $diem);

        return response()->json([
            'message'     => 'Lưu đáp án thành công!',
            'MaBaiThi'    => $record->MaBaiThi,
            'MaNguoiDung' => $record->MaNguoiDung,
            // Chú ý: Query Builder trả về object với tên cột y hệt trong DB
            // nên bạn cần gọi ->TongCauDung, ->Diem
            'TongCauDung' => $record->TongCauDung,
            'Diem'        => $record->Diem,
            'created_at'  => $record->created_at,
            'updated_at'  => $record->updated_at,
        ]);
    }


/**
 * Updates or creates a record in the baithicuathisinh table.
 *
 * @param  string  $maBaiThi
 * @param  string  $maNguoiDung
 * @param  int     $tongCauDung
 * @param  float   $diem
 * @return \App\Models\BaiThiCuaThiSinh
 */
    public function updateOrInsertBaiThi($maBaiThi, $maNguoiDung, $tongCauDung, $diem)
    {
        // updateOrCreate will look for a record matching the composite key
        // and update if found, or create if not found.
        DB::table('baithicuathisinh')->updateOrInsert(
            [
                'MaBaiThi'    => $maBaiThi,
                'MaNguoiDung' => $maNguoiDung,
            ],
            [
                'TongCauDung' => $tongCauDung,
                'Diem'        => $diem,
                // updateOrInsert không tự động cập nhật created_at/updated_at
                // nên ta cần set thủ công (nếu có cột timestamp)
                'updated_at'  => now(),
                'created_at'  => now(),
            ]
        );

        // Sau khi updateOrInsert, nếu bạn muốn lấy record vừa cập nhật để trả về:
        $record = DB::table('baithicuathisinh')
            ->where('MaBaiThi', $maBaiThi)
            ->where('MaNguoiDung', $maNguoiDung)
            ->first();

        return $record;
    }
    private function getDapAnDung($maBaiThi)
    {
        \Log::info("getDapAnDung: Nhận vào mã bài thi - {$maBaiThi}");

        // Tách mã bài thi theo dấu gạch dưới
        $parts = explode('_', $maBaiThi);
        if (count($parts) < 3) {
            \Log::error("getDapAnDung: Mã bài thi không đúng định dạng - {$maBaiThi}");
            return [];
        }

        // Lấy cấp độ, năm, tháng từ mã bài thi
        $level = strtolower($parts[0]); // ví dụ: "n1", "n2", v.v.
        $year  = $parts[1];
        $month = $parts[2];

        // Xây dựng đường dẫn file JSON theo cấp độ (ví dụ: storage/app/n2/07_2024.json)
        $fileFullPath = storage_path("app/{$level}/{$month}_{$year}.json");
        \Log::info("Full file path: " . $fileFullPath);

        if (!file_exists($fileFullPath)) {
            \Log::error("getDapAnDung: File không tồn tại - " . $fileFullPath);
            return [];
        }

        // Đọc và giải mã file JSON
        $jsonContent = file_get_contents($fileFullPath);
        \Log::info("getDapAnDung: Đọc được nội dung JSON - " . substr($jsonContent, 0, 100));

        $data = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error("getDapAnDung: Lỗi JSON - " . json_last_error_msg());
            return [];
        }

        return $data ?? [];
    }

    public function createExam(Request $request)
    {
        $data = $request->validate([
            'CapDo'     => 'required|string|in:n1,n2,n3,n4,n5',
            'tenBaiThi' => 'required|string|max:255',
            'month'     => 'required|numeric|min:1|max:12',
            'year'      => 'required|integer',
            'pdf_file'  => 'required|mimes:pdf|max:20480', // 20MB
        ]);

        Log::debug('Dữ liệu nhận được:', $data);

        $CapDo = strtolower($data['CapDo']); // n1, n2, n3, n4, n5
        $tenBaiThi = $data['tenBaiThi'];
        $month = $data['month'];
        $year = $data['year'];

        // Tạo tên file PDF
        $fileName = "{$CapDo}_{$month}_{$year}.pdf";
        $destinationPath = public_path('pdf');

        // Tạo thư mục nếu chưa có
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // Lưu file PDF
        $pdfFile = $request->file('pdf_file');
        $pdfFile->move($destinationPath, $fileName);

        // Mã bài thi
        $maBaiThi = strtoupper($CapDo) . "_{$year}_{$month}";

        // Lưu vào database
        DB::table('baithi')->insert([
            'MaBaiThi'  => $maBaiThi,
            'CapDo'     => strtoupper($CapDo),
            'TenBaiThi' => $tenBaiThi,
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);

        Log::info("Bài thi {$maBaiThi} được tạo, file PDF lưu tại: " . $destinationPath . DIRECTORY_SEPARATOR . $fileName);

        // --- CẬP NHẬT FILE JSON ---
        $jsonPath = public_path("dethi_{$CapDo}/test.json");

        // Tạo thư mục nếu chưa tồn tại
        if (!file_exists(dirname($jsonPath))) {
            mkdir(dirname($jsonPath), 0777, true);
        }

        // Đọc dữ liệu từ JSON
        if (file_exists($jsonPath)) {
            $jsonData = file_get_contents($jsonPath);
            $testsArray = json_decode($jsonData, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($testsArray)) {
                $testsArray = [];
            }
        } else {
            $testsArray = [];
        }

        // Chuẩn hóa tháng thành 2 chữ số
        $monthFormatted = str_pad($month, 2, '0', STR_PAD_LEFT);

        // Thêm tháng vào danh sách của năm
        if (!isset($testsArray[$year])) {
            $testsArray[$year] = [];
        }
        if (!in_array($monthFormatted, $testsArray[$year])) {
            $testsArray[$year][] = $monthFormatted;
        }

        // Sắp xếp dữ liệu trước khi ghi
        sort($testsArray[$year]);
        ksort($testsArray);

        // Ghi file JSON
        file_put_contents($jsonPath, json_encode($testsArray, JSON_PRETTY_PRINT));
        Log::info("File JSON {$jsonPath} đã được cập nhật.");

        // Debug gửi lên view
        $debug = [
            'message'   => 'Bài thi đã được tạo thành công!',
            'maBaiThi'  => $maBaiThi,
            'tenBaiThi' => $tenBaiThi,
            'file_path' => $destinationPath . DIRECTORY_SEPARATOR . $fileName,
        ];

        return view('teacher.taobaithi', compact('debug'));
    }


    public function uploadJsonAnswer(Request $request)
    {
        $data = $request->validate([
            'json_data' => 'required|string',
            'level'     => 'required|string|in:n1,n2,n3,n4,n5',
            'month'     => 'required|string', // đã định dạng 2 số
            'year'      => 'required|string',
            'filename'  => 'required|string'
        ]);

        // Tạo đường dẫn lưu file, ví dụ: storage/app/n1/07_2015.json
        $destinationPath = storage_path("app/{$data['level']}");
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $filePath = $destinationPath . DIRECTORY_SEPARATOR . $data['filename'];

        // Lưu nội dung JSON vào file và kiểm tra lỗi
        $result = file_put_contents($filePath, $data['json_data']);
        if ($result === false) {
            \Log::error("Lỗi khi ghi file JSON tại: {$filePath}");
            return response()->json(['message' => 'Lỗi khi lưu file JSON'], 500);
        }

        return response()->json([
            'message'   => 'File đáp án đã được lưu thành công!',
            'file_path' => $filePath,
        ]);
    }
            public function showResultsByUser()
        {
            // Lấy MaNguoiDung của user đang đăng nhập (giả sử trường là MaNguoiDung)
            $MaNguoiDung = auth()->user()->MaNguoiDung;

            // Truy vấn danh sách các bài thi của user từ bảng 'baithicuathisinh'
            $examResults = DB::table('baithicuathisinh')
                            ->where('MaNguoiDung', $MaNguoiDung)
                            ->orderBy('created_at', 'desc')
                            ->get();

            // Trả về view cùng với dữ liệu
            return view('student.ketquathi', compact('examResults'));
        }


}
