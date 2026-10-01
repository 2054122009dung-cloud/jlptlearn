<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JLPT Practice - Dashboard</title>
</head>
<body>

    <div class="header">
        <span>JLPT Practice - Luyện Thi</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Đăng xuất</button>
        </form>
    </div>

    <div class="container mx-auto p-5">
        <h1 class="text-3xl font-bold mb-4">Luyện Thi JLPT</h1>

        <h2 class="text-2xl font-semibold">Chọn cấp độ:</h2>
        <!-- Danh sách cấp độ JLPT -->
        <div class="mb-5" >
            <ul class="list-disc pl-5">
                @foreach(['n1', 'n2', 'n3', 'n4', 'n5'] as $lv)
                    <li>
                        <a href="{{ url($lv) }}"
                           class="text-blue-500 hover:underline {{ isset($level) && $level === $lv ? 'font-bold' : '' }}">
                            {{ strtoupper($lv) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Hiển thị đề thi của cấp độ đang chọn -->
        @if(isset($level))
            <h2 class="text-xl font-semibold mt-5">Đề thi cấp độ {{ strtoupper($level) }}</h2>
            <ul class="list-disc pl-5">
                @foreach($tests as $test)
                    <li>{{ $test['title'] ?? 'Không có tiêu đề' }}</li>
                @endforeach
            </ul>
        @endif
    </div>

</body>
</html>
