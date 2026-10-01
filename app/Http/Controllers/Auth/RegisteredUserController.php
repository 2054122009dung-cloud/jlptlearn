<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function generateUserId()
    {
        $lastUser = \App\Models\NguoiDung::orderBy('MaNguoiDung', 'desc')->first();

        if ($lastUser) {
            $lastId = (int) substr($lastUser->MaNguoiDung, 1); // Lấy số phía sau "U"
            $newId = 'U' . ($lastId + 1);
        } else {
            $newId = 'U1'; // Nếu chưa có user nào, bắt đầu từ U1
        }

        return $newId;
    }
    public function store(Request $request): RedirectResponse
  {
    //  dd($request->all()); // Kiểm tra dữ liệu nhận được từ form



        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:NguoiDung,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:NguoiDung,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
      ]);

        $user = NguoiDung::create([ // Đổi từ User sang NguoiDung
            'MaNguoiDung' => $this->generateUserId(), // Tạo mã tự động
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
