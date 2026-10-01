<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JLPT N1 - {{ $month }}/{{ $year }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Bố trí container thành flex để PDF bên trái và bảng đáp án bên phải */
        .container {
            display: flex;
            gap: 20px;
            padding: 10px;
        }
        /* PDF Viewer chiếm phần lớn bên trái */
        .pdf-viewer {
            flex: 2;
            height: 90vh;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        /* Answer sheet bên phải */
        .answer-sheet {
            flex: 1;
            max-width: 300px;
            height: 90vh;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 20px;
            position: relative;
            background: #f9f9f9;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }
        /* Khi bảng đáp án bị đóng */
        .answer-sheet.collapsed {
            transform: translateX(100%);
        }
        /* Style cho bảng đáp án bên trong answer-sheet */
        .answer-sheet h3 {
            margin-top: 40px;
            margin-bottom: 10px;
            font-family: Arial, sans-serif;
            font-size: 18px;
            color: #444;
        }
        .answer-sheet p {
            font-family: Arial, sans-serif;
            font-size: 16px;
            margin: 5px 0;
        }
        .answer-sheet table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .answer-sheet table th,
        .answer-sheet table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 14px;
        }
        .answer-sheet table th {
            background-color: #007bff;
            color: #fff;
        }
        .save-btn, .export-btn, .import-btn {
            margin-top: 15px;
            padding: 8px 15px;
            font-size: 16px;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .save-btn {
            background-color: #28a745;
        }
        .save-btn:hover {
            background-color: #218838;
        }
        .export-btn {
            background-color: #007bff;
        }
        .export-btn:hover {
            background-color: #0056b3;
        }
        .import-btn {
            background-color: #ffc107;
            color: #000;
        }
        .import-btn:hover {
            background-color: #e0a800;
        }
        /* File input ẩn */
        #import-file {
            display: none;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 2500px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        /* Close button */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div  style="padding-bottom:20px">
        <button id="start-exam-button">Bắt đầu thi</button>
    </div>

    <div class="modal" id="exam-modal">
        <div class="modal-content">
            <button class="close-btn" id="close-exam">✖</button>
            <p>Thời gian còn lại: <span id="exam-timer">Chưa bắt đầu!</span></p>
            <div class="container">
                <iframe class="pdf-viewer" src="{{ $filePath }}#zoom=120"></iframe>
                <div class="answer-sheet">
                    <h3>Phiếu Trả Lời</h3>
                    <p><strong>Mã Phiếu:</strong> {{ $maPhieu }}</p>
                    <p><strong>Mã Bài Thi:</strong> {{ $maBaiThi }}</p>
                    <table>
                        <tr>
                            <th>Câu</th>
                            <th>Đáp Án</th>
                        </tr>
                        @for ($i = 1; $i <= 70; $i++)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>
                                <select name="answer_{{ $i }}" class="answer">
                                    <option value="">--</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </td>
                        </tr>
                        @endfor
                    </table>
                    <button class="save-btn" onclick="saveAnswers()">Nộp bài</button>
                    <button class="export-btn" onclick="exportAnswers()">Xuất File JSON</button>
                    <button class="import-btn" onclick="document.getElementById('import-file').click()">Nạp File JSON</button>
                    <input type="file" id="import-file" accept="application/json" onchange="importAnswersFile(event)">
                    <div style="height:50px"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="nav-buttons">
        @if(auth()->user()->hasRole('HOC VIEN'))
            <a href="/ketquathi" class="btn">Xem kết quả</a>
        @endif
    </div>
    <div class="container-s">
        @include('components.ui')
    </div>
    <div class="modal" id="congrats-modal">
        <div class="modal-content">
            <h2>🎉 Chúc mừng! 🎉</h2>
            <p>Bạn đã hoàn thành bài thi.</p>
            <button onclick="closeCongratsModal()">Đóng</button>
        </div>
    </div>

    <script>
        function closeAllPopups() {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.style.display = 'none';
            });
        }

        // Lưu đáp án lên server (đã có sẵn)
        function saveAnswers() {
            let answers = {};
            document.querySelectorAll('.answer').forEach((select, index) => {
                answers[index + 1] = select.value;
            });

            fetch("{{ route('nop-bai') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({
                    MaPhieu: "{{ $maPhieu }}",
                    MaBaiThi: "{{ $maBaiThi }}",
                    answers: answers
                })
            })
            .then(response => response.text())
            .then(text => {
                console.log("Raw server response:", text);
                try {
                    let data = JSON.parse(text);
                    alert(data.message);
                    window.location.href = "/ketquathi";
                } catch (e) {
                    console.error("JSON parse error:", e);
                }
            })
            .catch(error => console.error("Fetch Error:", error));
        }

        // Xuất đáp án ra file JSON
        function exportAnswers() {
            let answers = {};
            document.querySelectorAll('.answer').forEach((select, index) => {
                answers[index + 1] = select.value;
            });
            let dataStr = JSON.stringify(answers, null, 2);
            let blob = new Blob([dataStr], { type: "application/json" });
            let url = URL.createObjectURL(blob);
            let a = document.createElement('a');
            a.href = url;
            a.download = 'answers.json';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        // Nạp đáp án từ file JSON đã chọn
        function importAnswersFile(event) {
            let file = event.target.files[0];
            if (!file) return;
            let reader = new FileReader();
            reader.onload = function(e) {
                try {
                    let answers = JSON.parse(e.target.result);
                    Object.keys(answers).forEach(key => {
                        let select = document.querySelector(`select[name="answer_${key}"]`);
                        if (select) {
                            select.value = answers[key];
                        }
                    });
                    alert("Nạp đáp án thành công!");
                } catch(error) {
                    alert("Lỗi khi đọc file JSON!");
                    console.error(error);
                }
            }
            reader.readAsText(file);
        }

        // Timer thi
        let examTimerInterval; // Biến toàn cục để lưu ID của setInterval

        function startExam(durationMinutes) {
            let now = new Date().getTime();
            let examEndTime = now + durationMinutes * 60 * 1000;
            sessionStorage.setItem("examEndTime", examEndTime);
            sessionStorage.setItem("currentExam", window.location.pathname);
            updateExamTimer();
        }

        function updateExamTimer() {
            let examEndTime = sessionStorage.getItem("examEndTime");
            let currentExam = sessionStorage.getItem("currentExam");
            let pageExamId = window.location.pathname;
            if (currentExam !== pageExamId) {
                sessionStorage.removeItem("examEndTime");
                sessionStorage.setItem("currentExam", pageExamId);
                document.getElementById("exam-timer").textContent = "Chưa bắt đầu!";
                return;
            }
            if (!examEndTime) return;
            if (examTimerInterval) { clearInterval(examTimerInterval); }
            examTimerInterval = setInterval(() => {
                let now = new Date().getTime();
                let timeLeft = examEndTime - now;
                if (timeLeft <= 0) {
                    clearInterval(examTimerInterval);
                    document.getElementById("exam-timer").textContent = "Hết giờ!";
                    alert("Hết giờ! Nộp bài ngay.");
                } else {
                    let minutes = Math.floor(timeLeft / (1000 * 60));
                    let seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                    document.getElementById("exam-timer").textContent = `${minutes} phút ${seconds} giây`;
                }
            }, 1000);
        }

        // Xử lý các sự kiện khi DOM đã load
        document.addEventListener("DOMContentLoaded", function () {
            updateExamTimer();
            document.getElementById("start-exam-button").addEventListener("click", function () {
                document.getElementById("exam-modal").style.display = "flex";
                startExam(90);
            });
            document.getElementById("close-exam").addEventListener("click", function () {
            let confirmClose = confirm("Bạn có chắc chắn muốn đóng bài thi? Dữ liệu sẽ không được lưu.");
            if (confirmClose) {
                document.getElementById("exam-modal").style.display = "none";
                alert("Bạn đã đóng bài thi, dữ liệu sẽ không được lưu!");
            }
            });
        });

    </script>
</body>
</html>
