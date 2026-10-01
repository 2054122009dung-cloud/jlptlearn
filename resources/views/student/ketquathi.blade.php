<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JLPT Practice - Kết quả thi</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

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

    <div class="container mx-auto p-5">
        <h1 class="text-3xl font-bold mb-4">Danh sách các lần thi của bạn</h1>

        @if($examResults->isEmpty())
            <p>Chưa có kết quả thi nào.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Mã Bài Thi</th>
                        <th>Tổng Câu Đúng</th>
                        <th>Điểm</th>
                        <th>Thời gian thi</th>
                        <th>In ra pdf</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($examResults as $result)
                        <tr>
                            <td>{{ $result->MaBaiThi }}</td>
                            <td>{{ $result->TongCauDung }}</td>
                            <td>{{ $result->Diem }}</td>
                            <td>{{ \Carbon\Carbon::parse($result->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                <button onclick="exportRowToPDF(this)">Xuất PDF</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <script>
        function exportRowToPDF(button) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Thêm font hỗ trợ tiếng Việt
            doc.addFileToVFS('Times New Roman');  // Chuyển đổi font sang base64 nếu cần
            doc.setFont('Times New Roman');  // Đặt font mặc định là Arial

            // Lấy hàng chứa nút bấm
            const row = button.closest("tr");
            const cells = row.querySelectorAll("td");

            // Dữ liệu từ hàng
            const examID = cells[0].innerText;
            const correctAnswers = cells[1].innerText;
            const score = cells[2].innerText;
            const examTime = cells[3].innerText;

            // Tiêu đề
            doc.setFontSize(16);
            doc.text("JLPT Practice - Exam Result", 60, 15);

            // Nội dung
            let yPos = 30;
            const data = [
                { label: "Exam ID", value: examID },
                { label: "Total Correct", value: correctAnswers },
                { label: "Score", value: score },
                { label: "Date", value: examTime },
            ];

            data.forEach(item => {
                doc.setFontSize(12);
                doc.text(`${item.label}: ${item.value}`, 20, yPos);
                yPos += 10;
            });

            // Xuất file PDF
            doc.save(`JLPT_Result_${examID}.pdf`);
        }
    </script>

</body>
</html>
