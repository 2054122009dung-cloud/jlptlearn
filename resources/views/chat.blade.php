@extends('layouts.app')

@section('content')
    <div class="chat-container">
        <h2>Chatbot</h2>
        <div class="chat-box" id="chat-box"></div>
        <div class="chat-input">
            <input type="text" id="message" placeholder="Nhập tin nhắn..." onkeypress="handleKeyPress(event)">
            <label>
                <input type="checkbox" id="use-duckduckgo"> Sử dụng DuckDuckGo
            </label>
            <button id="send-button" onclick="sendMessage()">Gửi</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function sendMessage() {
            let messageInput = document.getElementById("message");
            let message = messageInput.value.trim();
            if (!message) return;

            let sendButton = document.getElementById("send-button");
            sendButton.disabled = true; // Vô hiệu hóa nút gửi để chống spam

            let useDuckDuckGo = document.getElementById("use-duckduckgo").checked;

            axios.post("/chat", { message: message, use_duckduckgo: useDuckDuckGo })
                .then(response => {
                    let data = response.data;
                    let chatBox = document.getElementById("chat-box");

                    // Hiển thị tin nhắn người dùng
                    chatBox.innerHTML += `<div class='message user'>Bạn: ${message}</div>`;

                    // Hiển thị phản hồi từ bot
                    let confidenceText = data.confidence ? ` (Độ tự tin: ${(data.confidence * 100).toFixed(0)}%)` : "";
                    chatBox.innerHTML += `<div class='message bot'>Bot: ${data.response.replace(/\n/g, '<br>')}${confidenceText}</div>`;

                    // Nếu có ảnh trả về, hiển thị ảnh
                    if (data.image) {
                        chatBox.innerHTML += `<img src='data:image/png;base64,${data.image}' alt='Generated Image' class='chat-image'>`;
                    }

                    messageInput.value = "";
                    chatBox.scrollTop = chatBox.scrollHeight;
                })
                .catch(error => {
                    console.error(error);
                    alert("Lỗi gửi tin nhắn! Hãy thử lại sau.");
                })
                .finally(() => {
                    sendButton.disabled = false; // Kích hoạt lại nút gửi
                });
        }

        function handleKeyPress(event) {
            if (event.key === "Enter") {
                sendMessage();
            }
        }
    </script>

@endsection
