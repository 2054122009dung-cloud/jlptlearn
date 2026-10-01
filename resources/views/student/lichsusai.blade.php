<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JLPT Practice - Lịch Sử Sai</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/styles.css') }}">
    @livewireStyles
</head>
<body>

    <div class="header">
        <span>JLPT Practice - Lịch Sử Sai</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Đăng xuất</button>
        </form>
    </div>

    <div class="container">
        <h2>Lịch Sử Sai</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>📌 Mã Lỗi</th>
                    <th>❌ Số Lỗi Sai</th>
                    <th> Lần Làm Bài Cuối</th>
                    <th> Đáp án sai</th>
                    <th> Đáp án đúng</th>

                </tr>
            </thead>
            <tbody>
                @forelse($loiSai as $loi)
                    <tr>
                        <td>{{ $loi->MaLoi }}</td>
                        <td>{{ $loi->SoLoiSai }}</td>
                        <td>{{ $loi->LanLamBaiCuoi }}</td>
                        <td>{{ $loi->DapAnSai }}</td>
                        <td>{{ $loi->DapAnDung }}</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Không có dữ liệu</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @livewireScripts
</body>
</html>
