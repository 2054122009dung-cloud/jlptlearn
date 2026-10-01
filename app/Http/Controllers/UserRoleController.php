<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use App\Models\VaiTro;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    // Hiển thị tất cả người dùng
    public function index()
    {
        $users = NguoiDung::all(); // Lấy tất cả người dùng
        return view('users.index', compact('users'));
    }

    // Hiển thị form tạo người dùng mới
    public function create()
    {
        $roles = VaiTro::all(); // Lấy tất cả vai trò
        return view('users.create', compact('roles'));
    }

    // Tạo người dùng mới và gắn vai trò
    public function store(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'MaNguoiDung' => 'required|unique:NguoiDung,MaNguoiDung',
            'username' => 'required',
            'email' => 'required|email|unique:NguoiDung,email',
            'password' => 'required|min:6',
        ]);

        // Tạo người dùng mới
        $user = NguoiDung::create([
            'MaNguoiDung' => $validated['MaNguoiDung'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'create_at' => now(),
            'updated_at' => now(),
        ]);

        // Gắn vai trò mặc định 3 (học viên) vào người dùng
        $user->vaiTros()->attach(3); // Mã vai trò mặc định là 3 (học viên)

        return redirect()->route('users.index')->with('success', 'User created successfully with default role!');
    }


    // Hiển thị form chỉnh sửa người dùng
    public function edit($id)
    {
        $user = NguoiDung::findOrFail($id);
        $roles = VaiTro::all();
        return view('users.edit', compact('user', 'roles'));
    }

    // Cập nhật người dùng và vai trò
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:NguoiDung,email,' . $id,
            'password' => 'nullable|min:6',
            'roles' => 'required|array',
        ]);

        $user = NguoiDung::findOrFail($id);
        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? bcrypt($validated['password']) : $user->password,
            'updated_at' => now(),
        ]);

        // Cập nhật lại vai trò
        $user->vaiTros()->sync($validated['roles']); // Sử dụng sync để cập nhật vai trò

        return redirect()->route('users.index')->with('success', 'User updated successfully with roles!');
    }

    // Xóa người dùng
    public function destroy($id)
    {
        $user = NguoiDung::findOrFail($id);
        $user->vaiTros()->detach(); // Gỡ vai trò trước khi xóa
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }

    // Gán vai trò cho người dùng
    public function assignRole(Request $request, $id)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
        ]);

        $user = NguoiDung::findOrFail($id);
        $user->vaiTros()->sync($validated['roles']); // Cập nhật vai trò cho người dùng

        return redirect()->route('users.index')->with('success', 'Roles assigned to user successfully!');
    }
}
