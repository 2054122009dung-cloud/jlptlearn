<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bài thi</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>

    <div class="header">
        <span>JLPT Practice - Quản lý bài thi</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Đăng xuất</button>
        </form>
    </div>

    <div class="container">
        <h2>Danh sách bài thi</h2>

        <!-- Nút chọn cấp độ -->
        <div class="level-buttons">
            <button onclick="loadTestData('n1')">N1</button>
            <button onclick="loadTestData('n2')">N2</button>
            <button onclick="loadTestData('n3')">N3</button>
            <button onclick="loadTestData('n4')">N4</button>
            <button onclick="loadTestData('n5')">N5</button>
        </div>

        <table id="testTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Năm</th>
                    <th>Tháng</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>
    @include('components.ui')

    <script>
        $(document).ready(function() {
            $('#testTable').DataTable();
        });

        function loadTestData(level) {
            $.ajax({
                url: `/dethi_${level}/test.json`, // Điều chỉnh đường dẫn JSON
                type: "GET",
                dataType: "json",
                success: function(data) {
                    let table = $('#testTable').DataTable();
                    table.clear();

                    Object.entries(data).forEach(([year, months]) => {
                        let monthList = Array.isArray(months) ? months.join(', ') : months;
                        table.row.add([year, monthList]);
                    });

                    table.draw();
                },
                error: function() {
                    alert("Không thể tải dữ liệu cấp độ " + level);
                }
            });
        }
    </script>

</body>
</html>
