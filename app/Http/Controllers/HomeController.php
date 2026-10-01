<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\NguoiDung;
use App\Models\VaiTro;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('jlpt.gioithieu');
    }

    public function showRegisterForm()
    {
        $vaiTros = VaiTro::all();
        return view('jlpt.DangKy', compact('vaiTros'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:nguoidung',
            'email' => [
                'required',
                'email',
                'regex:/^[\w\.-]+@[\w-]+\.[a-zA-Z]{2,}$/',
                'unique:nguoidung'
            ],
            'password' => 'required|string|min:6|confirmed',
            'vaitro' => 'required|string', // Thêm rule cho vai_tro
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Tạo MaNguoiDung (ví dụ: U001, U002, ...)
        $lastUser = NguoiDung::orderBy('MaNguoiDung', 'desc')->first();
        $nextId = $lastUser ? (int)substr($lastUser->MaNguoiDung, 1) + 1 : 1;
        $maNguoiDung = 'U' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // Kiểm tra xem MaNguoiDung đã tồn tại chưa, nếu có thì tăng nextId cho đến khi tìm được MaNguoiDung duy nhất
        while (NguoiDung::where('MaNguoiDung', $maNguoiDung)->exists()) {
            $nextId++;
            $maNguoiDung = 'U' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        }

        $nguoiDung = NguoiDung::create([
            'MaNguoiDung' => $maNguoiDung,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Lấy vai trò từ form
        $maVaiTro = $request->vaitro;

        // Gọi function themNguoiDungVaiTro để thêm dữ liệu vào bảng nguoidungvaitro
        $this->themNguoiDungVaiTro($nguoiDung->MaNguoiDung, $maVaiTro);

        // Đăng nhập ngay lập tức nếu muốn
        auth()->login($nguoiDung);

        // Chuyển hướng người dùng đến trang chính
        return redirect('/jlpt')->with('success', 'Đăng ký thành công!');
    }

    // Function thêm dữ liệu vào bảng nguoidungvaitro
    private function themNguoiDungVaiTro($maNguoiDung, $maVaiTro)
    {
        try {
            DB::table('nguoidungvaitro')->insert([
                'MaNguoiDung' => $maNguoiDung,
                'MaVaiTro' => $maVaiTro,
            ]);
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có (ví dụ: ghi log, hiển thị thông báo lỗi)
            return redirect()->back()->withErrors(['message' => 'Gán vai trò thất bại: ' . $e->getMessage()]);
        }
    }

   public function showDashboard()
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
}
