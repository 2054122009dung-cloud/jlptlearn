<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JLPT N1 Quiz</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/styles.css') }}">
</head>
<body>

    <div class="header">
        <span>JLPT N1 - Luyện Thi</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Đăng xuất</button>
        </form>
    </div>

    <div class="container">
        <h1>Bài Test JLPT N1</h1>
        <p>Hãy chọn câu trả lời đúng cho các câu hỏi dưới đây:</p>

        <livewire:quiz-component level="n1" />
    </div>

</body>
</html>
