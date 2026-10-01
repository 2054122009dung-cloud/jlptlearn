<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JLPT Practice - Dashboard</title>
  <!-- Bao gồm jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    /* Một số style cơ bản */
    .popup { display: none; background: #fff; padding: 20px; border: 1px solid #ccc; }
    .overlay { display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.5); }
  </style>
  <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
  <div class="header">
    <span>JLPT Practice - Luyện Thi</span>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="logout-btn">Đăng xuất</button>
    </form>
  </div>

  <div class="container">
    <h1>Tạo Bài Thi JLPT</h1>
    <button onclick="openPopup('examPopup')">Tạo bài thi</button>
    <button onclick="openPopup('answerPopup')">Tạo đáp án</button>

    <div id="examPopup" class="popup">
        <h2>Tạo Bài Thi</h2>
        <form id="examForm" action="{{ route('taobaithi.create') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Trường chọn cấp độ -->
        <div>
            <label for="CapDo">Cấp độ:</label>
            <select id="CapDo" name="CapDo" required>
            <option value="n1">N1</option>
            <option value="n2">N2</option>
            <option value="n3">N3</option>
            <option value="n4">N4</option>
            <option value="n5">N5</option>
            </select>
        </div>
        <div>
            <label for="tenBaiThi">Tên bài thi:</label>
            <input type="text" id="tenBaiThi" name="tenBaiThi" required>
        </div>
        <div>
            <label for="month">Tháng:</label>
            <select id="month" name="month" required>
                <option value="07">07</option>
                <option value="12">12</option>
              </select>
        </div>
        <div>
            <label for="year">Năm:</label>
            <input type="number" id="year" name="year" required>
        </div>
        <!-- Drop Zone -->
        <div class="drop-zone" id="dropZone" style="border:2px dashed #ccc; padding:20px; text-align:center; cursor:pointer;">
            Kéo thả file PDF vào đây hoặc nhấp để chọn
        </div>
        <!-- Input file ẩn -->
        <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf" required style="display: none;">
        <button type="submit">Tạo bài thi</button>
        </form>
        <button onclick="closePopup('examPopup')">Đóng</button>
    </div>

    <!-- Popup Tạo Đáp Án (nếu cần) -->
    <div id="answerPopup" class="popup">
        <h2>Tạo Đáp Án</h2>
        <div>
            <label for="CapDoExam">Cấp độ:</label>
            <select id="CapDoExam" name="CapDo" required>
                <option value="n1">N1</option>
                <option value="n2">N2</option>
                <option value="n3">N3</option>
                <option value="n4">N4</option>
                <option value="n5">N5</option>
            </select>
        </div>
        <div>
        <label for="answerMonth">Tháng:</label>
        <select id="answerMonth" required>
            <option value="">Chọn tháng</option>
            <option value="07">07</option>
            <option value="12">12</option>
        </select>    </div>
        <div>
        <label for="answerYear">Năm:</label>
        <input type="number" id="answerYear" required />
        </div>
        <div class="scrollable">
        <form id="answerForm">
            <div id="answerFields"></div>
            <button type="button" onclick="uploadAnswers()">gửi lên server</button>
        </form>
        </div>
        <button onclick="closePopup('answerPopup')">Đóng</button>
    </div>

    <div class="overlay" id="overlay" onclick="closeAllPopups()"></div>
        <!-- Popup Tạo Đáp Án -->

    @include('components.ui')</div>



  <script>
      $(document).ready(function(){
    $("#month").on("change", function(){
        let value = $(this).val();
        if (value === "7") {
            $(this).val("07");
        }
    });
});

    $(document).ready(function(){
      // Sự kiện submit form gửi qua AJAX
      $('#examForm').on('submit', function(e) {
        e.preventDefault(); // Ngăn reload trang
        console.log("Form submit được gọi.");

        let formData = new FormData(this);
        $.ajax({
          url: $(this).attr('action'),
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            console.log("Response từ server:", response);
            // Bạn có thể thêm thông báo thành công, cập nhật giao diện, v.v.
          },
          error: function(xhr) {
            console.error("Lỗi khi gửi form:", xhr.responseText);
          }
        });
      });
    });

    console.log("Debug check: Script đã chạy.");
    @if(isset($debug))
      console.log("Debug từ controller:", @json($debug));
    @endif

    // Xử lý Drop Zone
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('pdf_file');

    dropZone.addEventListener('click', () => {
      fileInput.click();
    });

    dropZone.addEventListener('dragover', (e) => {
      e.preventDefault();
      dropZone.style.borderColor = "#333";
      dropZone.style.backgroundColor = "#f0f0f0";
    });

    dropZone.addEventListener('dragleave', (e) => {
      e.preventDefault();
      dropZone.style.borderColor = "#ccc";
      dropZone.style.backgroundColor = "#fff";
    });

    dropZone.addEventListener('drop', (e) => {
      e.preventDefault();
      dropZone.style.borderColor = "#ccc";
      dropZone.style.backgroundColor = "#fff";

      const files = e.dataTransfer.files;
      if (files.length) {
        if(files[0].type === "application/pdf") {
          fileInput.files = files;
          dropZone.textContent = files[0].name;
        } else {
          alert("Vui lòng chọn file PDF.");
        }
      }
    });

    fileInput.addEventListener('change', () => {
      if(fileInput.files.length) {
        dropZone.textContent = fileInput.files[0].name;
      }
    });

    function openPopup(id) {
      document.getElementById(id).style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    function closePopup(id) {
      document.getElementById(id).style.display = 'none';
      document.getElementById('overlay').style.display = 'none';
    }

    function closeAllPopups() {
      document.querySelectorAll('.popup').forEach(popup => popup.style.display = 'none');
      document.getElementById('overlay').style.display = 'none';
    }

    //dapan
    function uploadAnswers() {
        // Thu thập dữ liệu đáp án
        let answers = {};
        for (let i = 1; i <= 70; i++) {
            let value = document.getElementById('answer' + i).value;
            answers[i] = value;
        }

        // Lấy dữ liệu cấp độ, tháng, năm
        let level = document.getElementById('CapDoExam').value;
        let month = document.getElementById('answerMonth').value;
        let year = document.getElementById('answerYear').value;
        // Định dạng tháng với 2 chữ số
        month = ('0' + month).slice(-2);
        // Tên file theo định dạng mong muốn, ví dụ: "07_2015.json"
        let filename = `${month}_${year}.json`;

        // Tạo FormData để gửi lên server
        let formData = new FormData();
        formData.append('json_data', JSON.stringify(answers, null, 2));
        formData.append('level', level);
        formData.append('month', month);
        formData.append('year', year);
        formData.append('filename', filename);

        // Lấy CSRF token từ meta tag (đảm bảo meta này đã được chèn vào head của trang)
        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Gửi dữ liệu lên server
        fetch('/upload-json-answer', {
            method: 'POST',
            headers: {
            'X-CSRF-TOKEN': token
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải file lên.');
        });
        }

    window.addEventListener('load', function() {
    // Code xử lý khi trang load
    let container = document.getElementById('answerFields');
    for (let i = 1; i <= 70; i++) {
        let div = document.createElement('div');
        div.classList.add('form-group');
        div.innerHTML = `<label for="answer${i}">Câu ${i}:</label>
                        <select id="answer${i}" name="answer${i}">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>`;
        container.appendChild(div);
    }
    });


  </script>
</body>
</html>
