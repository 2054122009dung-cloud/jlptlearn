<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JLPT N2 - Danh sách đề thi</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/styles.css') }}">

    <script>
        function filterTests() {
            let selectedYear = document.getElementById("yearFilter").value;
            let testItems = document.querySelectorAll(".test-item");

            testItems.forEach(item => {
                if (selectedYear === "all" || item.dataset.year === selectedYear) {
                    item.style.display = "block";
                } else {
                    item.style.display = "none";
                }
            });
        }
    </script>
</head>
<body>

    <div class="header">
        <span>JLPT N2 - Danh sách đề thi</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Đăng xuất</button>
        </form>
    </div>

    <div class="container">
        <h1>Chọn đề thi JLPT N2</h1>

        <div class="filter-container">
            <!-- Dropdown chọn năm -->
            <label for="yearFilter" class="year-select">Chọn năm:</label>
            <select class="year-select" id="yearFilter" onchange="filterTests()">
                <option value="all">Tất cả</option>
                @foreach(array_keys($tests) as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>

        <ul class="test-list">
            @foreach($tests as $year => $months)
                @foreach($months as $month)
                    <li class="test-item" data-year="{{ $year }}">
                        <a href="{{ url("/n2/{$month}/{$year}") }}" class="test-link">
                            Đề thi JLPT N2 - {{ $month }}/{{ $year }}
                        </a>
                    </li>
                @endforeach
            @endforeach
        </ul>
    </div>

</body>
</html>
