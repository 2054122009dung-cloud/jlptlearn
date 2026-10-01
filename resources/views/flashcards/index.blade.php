<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JLPT Practice - Dashboard</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    .popup { display: none; background: #fff; padding: 20px; border: 1px solid #ccc; }
    .overlay { display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.5); }
  </style>
</head>
<body>
  <div class="header">
    <span>JLPT Practice - Giáo sư</span>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="logout-btn">Đăng xuất</button>
    </form>
  </div>
  <style>
  .message.user {
    display: none !important;
}
</style>
  <div class="chat-container">
    <h2>Giáo sư tiếng nhật</h2>
    <div class="chat-box" id="chat-box"></div>
    <div class="chat-input">
      <!-- Ô input người dùng -->
      <input type="text" id="mess" placeholder="送信してください..." onkeypress="handleKeyPress(event)">
      <!-- Input ẩn chứa message sau khi thêm prompt -->
      <input type="hidden" id="message">
      <button id="send-button" onclick="prepareMessageAndSend()">送信</button>
    </div>
  </div>

  <script>
    const prompt = "Bạn là một giảng viên, thạc sĩ tiếng Nhật giải thích, phiên âm, đặt 3 câu, liệt kê từ đồng nghĩa, trái nghĩa, đánh giá mức độ thường gặp. Nội dung người dùng: ";

    // Khi nhấn Enter
    function handleKeyPress(event) {
      if (event.key === "Enter") {
        event.preventDefault(); // không reload
        prepareMessageAndSend();
      }
    }

        // Ghép prompt + message và gửi
        function prepareMessageAndSend() {
    const userInput = document.getElementById("mess").value.trim();
    if (!userInput) return;

    const combined = prompt + userInput;
    document.getElementById("message").value = combined;

    // 👇 Hiển thị user input THẬT lên chat-box (không hiển thị prompt)
    appendUserMessage(userInput);

    sendMessage();
    document.getElementById("mess").value = ""; // clear input
    }

    // ✅ Thêm hàm này để show nội dung thật của người dùng
    function appendUserMessage(message) {
    const chatBox = document.getElementById("chat-box");
    const messageDiv = document.createElement("div");
    messageDiv.textContent = "🧑‍🎓 " + message;
    messageDiv.style.margin = "5px 0";
    chatBox.appendChild(messageDiv);
    }


    // Gửi message đã có prompt
    function sendMessage() {
      const finalMessage = document.getElementById("message").value;
      axios.post("/chat", { message: finalMessage })
        .then(response => {
          console.log("✅ Phản hồi từ server:", response.data);
          // có thể update chat-box ở đây nếu muốn
        })
        .catch(error => {
          console.error("❌ Lỗi khi gửi:", error);
        });
    }
  </script>
</body>
</html>
