<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JLPT Practice - Dashboard</title>
    <style>
        .nav-buttons {
            margin: 15px 0;
        }
        .nav-buttons a {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 8px 15px;
            margin-right: 5px;
            text-decoration: none;
            border-radius: 4px;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>

    <div class="header">
        <span>JLPT Practice - Kết quả Thi</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Đăng xuất</button>
        </form>
    </div>

    <div class="nav-buttons">
        @if(auth()->user()->hasRole('HOC VIEN'))
            <a href="/luyenthi" class="btn">Bắt đầu luyện tập</a>
            {{-- <a href="/xephang" class="btn">Xem bảng xếp hạng</a> --}}
            <a href="/gochoctap" class="btn">Góc học tập</a>
            <a href="/lichsusai" class="btn">Các lỗi sai</a>
            <a href="/hoidapcongdong" class="btn">Hỏi đáp cộng đồng</a>
            <a href="/quiz" class="btn">quiz</a>
        @endif
    </div>
    <div class="container">
        <h2>Thêm Câu Hỏi</h2>
        <form id="questionForm">
            <label for="question">Đề bài:</label>
            <input type="text" id="question" required>

            <label>Danh sách đáp án:</label>
            <div class="answers">
                <input type="text" class="answer" required> <select class="correct"><option value="0">Sai</option><option value="1">Đúng</option></select><br>
                <input type="text" class="answer" required> <select class="correct"><option value="0">Sai</option><option value="1">Đúng</option></select><br>
                <input type="text" class="answer" required> <select class="correct"><option value="0">Sai</option><option value="1">Đúng</option></select><br>
                <input type="text" class="answer" required> <select class="correct"><option value="0">Sai</option><option value="1">Đúng</option></select>
            </div>

            <button type="button" onclick="saveQuestion()">Lưu Câu Hỏi</button>
            <button type="button" onclick="generateJSON()">Xem Trước JSON</button>
            <button type="button" onclick="downloadJSON()">Tải Xuống JSON</button>
        </form>

        <h3>Dữ liệu JSON:</h3>
        <div class="json-output" id="jsonOutput"></div>
    </div>

    <script>
            function saveQuestion() {
        let question = document.getElementById('question').value;
        let answers = document.querySelectorAll('.answer');
        let correctOptions = document.querySelectorAll('.correct');
        let answerList = [];
        let correctAnswer = 0;

        answers.forEach((input, index) => {
            answerList.push({ id: index + 1, noi_dung: input.value });
            if (correctOptions[index].value == "1") {
                correctAnswer = index + 1;
            }
        });

        let questionData = {
            de_bai: question,
            dap_an: answerList,
            dap_an_dung: correctAnswer
        };

        fetch('/taocauhoi/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(questionData)
        })
        .then(response => response.json())
        .then(data => alert(data.message))
        .catch(error => console.error('Lỗi:', error));
    }
        function generateJSON() {
            let question = document.getElementById('question').value;
            let answers = document.querySelectorAll('.answer');
            let correctOptions = document.querySelectorAll('.correct');
            let answerList = [];
            let correctAnswer = 0;

            answers.forEach((input, index) => {
                answerList.push({ id: index + 1, noi_dung: input.value });
                if (correctOptions[index].value == "1") {
                    correctAnswer = index + 1;
                }
            });

            let questionData = {
                ma_cau_hoi: Date.now().toString(),
                loai: "ngon_ngu_ki_nang",
                de_bai: question,
                dap_an: answerList,
                dap_an_dung: correctAnswer
            };

            document.getElementById('jsonOutput').textContent = JSON.stringify(questionData, null, 4);
        }

        function downloadJSON() {
            let jsonData = document.getElementById('jsonOutput').textContent;
            let blob = new Blob([jsonData], { type: "application/json" });
            let link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = "cauhoi.json";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>

</body>
</html>
