<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\VaiTro;
use Illuminate\Support\Facades\DB;

class NguoiDungController extends Controller
{
    /**
     * Hiển thị danh sách người dùng
     */
    public function index()
    {
        $vaiTros = VaiTro::all();
        // Lấy danh sách user và ép danh sách vai trò thành mảng
        $users = NguoiDung::all()->map(function ($user) {
            return [
                'MaNguoiDung' => $user->MaNguoiDung,
                'username'    => $user->username,
                'email'       => $user->email,  // Thêm trường email vào đây
                'create_at'   => $user->create_at,  // Thời gian tạo
                'updated_at'  => $user->updated_at, // Thời gian cập nhật
                'roles'       => $user->getRoleNames()->toArray(),
            ];
        });

        // Truyền dữ liệu vào view (ví dụ: admin/qlnguoidung/index.blade.php)
        return view('admin.qlnguoidung', compact('users','vaiTros'));
    }

    /**
     * Hiển thị form tạo người dùng mới
     */
    public function create()
    {
        return view('admin.qlnguoidung.create');
    }

    /**
     * Lưu người dùng mới vào database
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'username' => 'required|string|max:255|unique:nguoidung',
        'email' => 'required|string|email|max:255|unique:nguoidung',
        'password' => 'required|string|min:8|confirmed',
        'vaitro' => 'required|string', // Thêm rule cho vai_tro
    ]);

    // Tạo MaNguoiDung theo logic tuần tự (U001, U002, ...)
    $lastUser = NguoiDung::orderBy('MaNguoiDung', 'desc')->first();
    $nextId = $lastUser ? (int)substr($lastUser->MaNguoiDung, 1) + 1 : 1;
    $maNguoiDung = 'U' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

    // Đảm bảo MaNguoiDung là duy nhất
    while (NguoiDung::where('MaNguoiDung', $maNguoiDung)->exists()) {
        $nextId++;
        $maNguoiDung = 'U' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }

    $nguoiDung = NguoiDung::create([
        'MaNguoiDung' => $maNguoiDung,
        'username' => $validated['username'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    // Gán vai trò cho người dùng
    $this->themNguoiDungVaiTro($nguoiDung->MaNguoiDung, $validated['vaitro']);

    return redirect()->route('admin.qlnguoidung.index')
                     ->with('success', 'Người dùng đã được tạo thành công.');
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
        return redirect()->back()->withErrors(['message' => 'Gán vai trò thất bại: ' . $e->getMessage()]);
    }
}

    /**
     * Hiển thị thông tin người dùng
     */
    public function show($id)
    {
        $vaiTros = VaiTro::all();
        $user = NguoiDung::findOrFail($id);
        return view('admin.qlnguoidung.show', compact('user','vaiTros'));
    }

    /**
     * Hiển thị form chỉnh sửa người dùng
     */
    public function edit($id)
    {
        $user = NguoiDung::findOrFail($id);
        return view('admin.qlnguoidung.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng
     */
    public function update(Request $request, $id)
    {
        $user = NguoiDung::findOrFail($id);

        // Validate dữ liệu, bao gồm trường vai trò (vaitro)
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:NguoiDung,email,' . $user->MaNguoiDung . ',MaNguoiDung',
            'vaitro'   => 'required|string',
        ]);

        // Cập nhật thông tin cơ bản của người dùng
        $user->update([
            'username' => $validated['username'],
            'email'    => $validated['email'],
        ]);

        // Nếu có cập nhật mật khẩu
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Cập nhật vai trò:
        // 1. Xóa vai trò cũ của user
        DB::table('nguoidungvaitro')->where('MaNguoiDung', $user->MaNguoiDung)->delete();

        // 2. Thêm vai trò mới cho user
        DB::table('nguoidungvaitro')->insert([
            'MaNguoiDung' => $user->MaNguoiDung,
            'MaVaiTro'    => $validated['vaitro'],
        ]);

        return redirect()->route('admin.qlnguoidung.index')
                         ->with('success', 'Thông tin người dùng đã được cập nhật.');
    }

    /**
     * Xóa người dùng
     */
    public function destroy($id)
    {
        $user = NguoiDung::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.qlnguoidung.index')
                         ->with('success', 'Người dùng đã được xóa.');
    }
}
