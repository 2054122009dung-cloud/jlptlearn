<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LichSuSai;
use Illuminate\Support\Facades\Auth;

class LichSuSaiController extends Controller
{
    public function index()
    {
        // Lấy mã người dùng hiện tại
        $maNguoiDung = Auth::user()->MaNguoiDung;

        // Lấy danh sách lỗi sai của người dùng đó
        $loiSai = LichSuSai::where('MaNguoiDung', $maNguoiDung)->get();

        // Trả về view trong thư mục student
        return view('student.lichsusai', compact('loiSai'));
    }
}
