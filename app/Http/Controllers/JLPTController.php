<?php


namespace App\Http\Controllers;
use App\Models\BaiThi;
use App\Models\DeMuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\VaiTro;

class JLPTController extends Controller
{

    public function index()
    {
        $baiThi = BaiThi::all(); // Lấy danh sách bài thi từ database
        return view('dashboard', compact('baiThi')); // Truyền dữ liệu sang view
    }
    public function trangChu()
    {
        //Kiểm tra xem người dùng đã đăng nhập hay chưa
        if (Auth::check()) {
            // Lấy thông tin người dùng đã đăng nhập
            $user = Auth::user();

            // Lấy MaVaiTro từ bảng nguoidungvaitro dựa trên MaNguoiDung
            $maVaiTro = DB::table('nguoidungvaitro')
                ->where('MaNguoiDung', $user->MaNguoiDung)
                ->value('MaVaiTro');

            // Lấy thông tin vai trò từ bảng vaitro dựa trên MaVaiTro
            $vaiTro = VaiTro::where('MaVaiTro', $maVaiTro)->first();

            if ($vaiTro) {
                // Nếu có vai trò, truyền vaiTro và user vào view
                return view('jlpt.TrangChu', compact('user', 'vaiTro'));
            } else {
                // Nếu không có vai trò, truyền user vào view
                return view('jlpt.TrangChu', compact('user'));
            }
         } else {
            // Nếu người dùng chưa đăng nhập, chuyển hướng đến trang đăng nhập
            return redirect('/dangnhap');
         }
    }

    public function showLevel($level)
    {
        // Kiểm tra level có hợp lệ không (N5 → N1)
        if (!in_array($level, ['N5', 'N4', 'N3', 'N2', 'N1'])) {
            abort(404);
        }

        return view('jlpt.level', compact('level'));
    }
    public function dashboard()
    {
        return view('jlpt.dashboard');
    }
}
