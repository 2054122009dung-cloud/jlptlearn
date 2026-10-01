<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('jlpt.dangnhap');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        // Validate form
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Thực hiện đăng nhập
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/jlpt');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ]);
    }
}
