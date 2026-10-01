<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JLPT Dashboard</title>

</head>
<body>
    <div class="container">
        <h1 class="header-text">Chào mừng đến với trang giới thiệu của chúng tôi!</h1>
        <p class="intro-text">Chúng tôi cung cấp các dịch vụ tuyệt vời để giúp bạn chuẩn bị cho kỳ thi JLPT một cách hiệu quả.</p>

        <!-- Form đăng nhập -->
        <div class="login-form-container">
            <h2 class="login-form-header">Đăng nhập</h2>
            <form action="{{ url('/dangnhap') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn login-btn">Đăng nhập</button>
                <p class="register-link">Chưa có tài khoản? <a href="{{ url('/dangky') }}">Đăng ký ngay</a></p>
            </form>
        </div>

        <!-- Thông tin giới thiệu -->
        <div class="intro-info">
            <p class="intro-text">Đăng ký hoặc đăng nhập để bắt đầu trải nghiệm!</p>
            <div class="image-container">
                <img src="{{ asset('images/jlpt_image.jpg') }}" alt="JLPT Image" class="intro-image">
            </div>
        </div>
    </div>
</body>
</html>
