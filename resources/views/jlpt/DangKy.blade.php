<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản JLPT</title>

</head>
<body>
    <div class="registration-container">
        <h2>Đăng Ký Tài Khoản Mới</h2>

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <div class="form-group">
                <label for="username">Tên người dùng:</label>
                <input type="text" id="username" name="username" required>
                @error('username')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                @error('email')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
                @error('password')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Nhập lại mật khẩu:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <!-- Thêm phần chọn vai trò -->
            <div class="form-group">
                <label for="vaitro">Vai Trò:</label>
                <select id="vaitro" name="vaitro" class="form-control" required>
                    <option value="">Chọn vai trò</option>
                    @foreach($vaiTros as $vaiTro)
                    @if($vaiTro->TenVaiTro != 'QUAN TRI') <!-- Kiểm tra nếu không phải QUAN TRI -->
                    <option value="{{ $vaiTro->MaVaiTro }}">{{ $vaiTro->TenVaiTro }}</option>
                    @endif
                    @endforeach
                </select>
                @error('vaitro')<span class="error">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn-register">Đăng Ký</button>
        </form>
    </div>
</body>
</html>
