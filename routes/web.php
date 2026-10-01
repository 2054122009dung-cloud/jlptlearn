<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\JLPTController;
use App\Http\Controllers\NguoiDungController;
use App\Http\Controllers\LuyenTapController;
use App\Http\Controllers\LichSuSaiController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ThiController;
use App\Http\Controllers\FlashcardController;
use App\Models\NguoiDung;

// ------------------ AUTH ROUTES ------------------
Route::get('/dangnhap', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/dangnhap', [AuthController::class, 'login']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

// ------------------ PUBLIC ROUTES ------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/', [ChatController::class, 'sendMessage']);

Route::prefix('dangky')->group(function () {
    Route::get('/', [HomeController::class, 'showRegisterForm'])->name('register.show');
    Route::post('/', [HomeController::class, 'register'])->name('register.submit');
});

// ------------------ PRIVATE ROUTES (Yêu cầu đăng nhập) ------------------
Route::middleware(['auth'])->group(function () {
    //    ------------------ ADMIN ROUTES (Yêu cầu quyền GIAO VIEN) ------------------
    Route::group(['middleware' => ['role:GIAO VIEN']], function () {
        Route::get('/taobaithi', [ThiController::class, 'taobaithi'])->name('taobaithi');
        Route::post('/taobaithi', [ThiController::class, 'createExam'])->name('taobaithi.create');
        Route::post('/upload-json-answer', [ThiController::class, 'uploadJsonAnswer'])->name('upload.json.answer');
    });

    Route::get('/jlpt', [JLPTController::class, 'trangChu'])->name('jlpt.trangchu');
    Route::get('/chat', [ChatController::class, 'index']);
    Route::post('/chat', [ChatController::class, 'sendMessage']); // Route chat POST
    Route::get('/luyenthi', [LuyenTapController::class, 'index']);
    Route::get('/lichsusai', [LichSuSaiController::class, 'index']);
    Route::get('/ketquathi', [\App\Http\Controllers\ThiController::class, 'showResultsByUser'])->name('ketquathi');
    Route::get('/quiz', function () {
        return view('quiz.n1');
    })->name('quiz.n1');
    Route::match(['get', 'post'], '/taocauhoi', [LuyenTapController::class, 'manageQuestions'])->name('taocauhoi');
    Route::post('/taocauhoi/save', [LuyenTapController::class, 'saveQuestion']);
    Route::get('/flashcard', [FlashcardController::class, 'index']);

    Route::get('/gochoctap', function () {
        return view('easyNfastWordSearch-jp--main.Translate');
    });

    Route::prefix('{level}')->group(function () {
        Route::get('/', [TestController::class, 'showLevelTests']);
        Route::get('/{month}/{year}', [ThiController::class, 'showExam'])->name('exam');
    });
    Route::post('/nop-bai', [ThiController::class, 'nopBai'])->name('nop-bai');



    // ------------------ ADMIN PANEL ROUTES ------------------
    Route::prefix('admin')->group(function () {
        Route::get('qlnguoidung', [NguoiDungController::class, 'index'])->name('admin.qlnguoidung.index');
        Route::get('qlnguoidung/create', [NguoiDungController::class, 'create'])->name('admin.qlnguoidung.create');
        Route::post('qlnguoidung', [NguoiDungController::class, 'store'])->name('admin.qlnguoidung.store');
        Route::get('qlnguoidung/{id}', [NguoiDungController::class, 'show'])->name('admin.qlnguoidung.show');
        Route::get('qlnguoidung/{id}/edit', [NguoiDungController::class, 'edit'])->name('admin.qlnguoidung.edit');
        Route::put('qlnguoidung/{id}', [NguoiDungController::class, 'update'])->name('admin.qlnguoidung.update');
        Route::delete('qlnguoidung/{id}', [NguoiDungController::class, 'destroy'])->name('admin.qlnguoidung.destroy');

        Route::get('/quanlybaithi', [TestController::class, 'qlBaithi'])->name('quanlybaithi');
    });

    // ------------------ PROFILE & DASHBOARD ------------------
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile/edit', function () {
        return view('profile.edit', ['user' => auth()->user()]);
    })->name('profile.edit');

    // ------------------ EMAIL VERIFICATION ------------------
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
});

// ------------------ DEBUG ROUTES ------------------
Route::get('/test-user', function () {
    $user = NguoiDung::where('email', 'dungleviet2002@gmail.com')->first();
    dd($user);
});

Route::middleware(['auth'])->get('/debug-user', function () {
    dd(auth()->user());
});
