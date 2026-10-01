<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JLPT Dashboard</title>
</head>
<body>

    <div class="header">
        <span>JLPT Practice - Dashboard</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Đăng xuất</button>

        </form>
    </div>

    <div class="container">
        <h1>Xin chào, {{ Auth::user()->username }}!</h1>
        @if(isset($vaiTro))
        <p>Vai trò của bạn là: {{ $vaiTro->TenVaiTro }}</p>
        @else
            <p>Bạn chưa có vai trò.</p>
        @endif
        <p>Chào mừng bạn đến với Dashboard.</p>

        @if(auth()->user()->hasRole('HOC VIEN'))
        <a href="/luyenthi" class="btn">Bắt đầu luyện tập</a>
        {{-- <a href="/xephang" class="btn">Xem bảng xếp hạng</a> --}}
        <a href="/flashcard" class="btn">Tạo flashcard</a>
        <a href="/gochoctap" class="btn">Góc học tập</a>
        {{-- <a href="/taocauhoi" class="btn">tạo quiz</a> --}}
        <a href="/lichsusai" class="btn">Các lỗi sai</a>
        <a href="/ketquathi" class="btn">kết quả thi</a>
        <a href="/hoidapcongdong" class="btn">Hỏi đáp cộng đồng</a>
        <a href="/quiz" class="btn">quiz</a>
        {{-- <a href="/themghichu" class="btn">Thêm ghi chú</a> --}}
        {{-- <a href="/danhdaucau" class="btn">Câu hỏi đã đánh dấu</a> --}}
        {{-- <a href="/thongke" class="btn">Thống kê & đánh giá</a> --}}
        @elseif(auth()->user()->hasRole('GIAO VIEN'))
            <a href="/taobaithi" class="btn">Tạo bài thi</a>
            <a href="/xemketqua" class="btn">Xem kết quả thi</a>
            <a href="/xephang" class="btn">Xem bảng xếp hạng</a>
        @elseif(auth()->user()->hasRole('QUAN TRI'))
            <a href="/admin/qlnguoidung" class="btn">Quản lý người dùng</a>
            <a href="/admin/quanlybaithi" class="btn">Quản lý bài thi</a>
        @endif

        @include('components.ui')

    </div>

</body>
</html>
