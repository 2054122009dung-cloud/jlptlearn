

function getNextTargetDate() {
    let now = new Date();
    let year = now.getFullYear();
    let july = new Date(year, 7 - 1, 1, 0, 0, 0);  // Đúng là ngày 1/7
    let december = new Date(year, 12 - 1, 1, 0, 0, 0);

    return now < july ? july : (now < december ? december : new Date(year + 1, 6 - 1, 1, 0, 0, 0));
}

function updateCountdown() {
    let targetDate = getNextTargetDate();
    let now = new Date();
    let timeDiff = targetDate - now;

    let days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
    let hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    let minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

    document.getElementById('days').textContent = days;
    document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
    document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
    document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
}

setInterval(updateCountdown, 1000);
updateCountdown();

//news
async function translateText(text) {
    const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=ja&tl=vi&dt=t&q=${encodeURIComponent(text)}`;

    try {
        const response = await fetch(url);
        const data = await response.json();
        return data[0][0][0]; // Lấy nội dung đã dịch
    } catch (error) {
        console.error("Lỗi dịch:", error);
        return "(Không thể dịch)";
    }
}
async function fetchNews() {
    const url = "https://www3.nhk.or.jp/rss/news/cat0.xml"; // NHK RSS
    try {
        const response = await fetch(url);
        const text = await response.text();
        const parser = new DOMParser();
        const xml = parser.parseFromString(text, "application/xml");

        const items = xml.querySelectorAll("item");
        let newsHTML = "";

        for (let index = 0; index < Math.min(5, items.length); index++) {
            const item = items[index];
            const title = item.querySelector("title").textContent;
            const link = item.querySelector("link").textContent;
            const pubDate = new Date(item.querySelector("pubDate").textContent).toLocaleDateString("vi-VN");

            const imageUrl = "https://upload.wikimedia.org/wikipedia/commons/4/4c/NHK_logo_2020.svg";

            // Dịch tiêu đề sang tiếng Việt
            const translatedTitle = await translateText(title);

            newsHTML += `
                <div class="news-item" style="display: flex; gap: 15px; align-items: center; margin-bottom: 10px;">
                    <div class="news-image">
                        <img src="${imageUrl}" alt="NHK Logo" style="width: 100px; height: auto;">
                    </div>
                    <div class="news-content">
                        <h3><a href="${link}" target="_blank">${title}</a></h3>
                        <p style="color: blue; font-style: italic;">👉 ${translatedTitle}</p>
                        <span class="news-date">${pubDate}</span>
                    </div>
                </div>
            `;
        }

        document.getElementById("news-container").innerHTML = newsHTML;
    } catch (error) {
        console.error("Lỗi khi lấy tin tức:", error);
        document.getElementById("news-container").innerHTML = "<p>Không thể tải tin tức.</p>";
    }
}

// Gọi hàm fetchNews khi trang tải xong
window.onload = fetchNews;
// Gọi hàm khi trang tải xong
function sendMessage() {
    let messageInput = document.getElementById("message");
    let message = messageInput.value.trim();
    if (!message) return;  // Kiểm tra tin nhắn trống

    let sendButton = document.getElementById("send-button");
    sendButton.disabled = true;

    let chatBox = document.getElementById("chat-box");

    // Hiển thị tin nhắn người dùng
    chatBox.innerHTML += `<div class='message user'>Bạn: ${message}</div>`;

    // Xóa nội dung trong input sau khi gửi
    messageInput.value = "";

    // Tạo phần hiển thị cho phản hồi của chatbot
    let botMessageDiv = document.createElement('div');
    botMessageDiv.classList.add('message', 'bot');
    botMessageDiv.innerHTML = "Chatbot đang trả lời...";
    chatBox.appendChild(botMessageDiv);

    // Cuộn xuống cuối hộp chat
    chatBox.scrollTop = chatBox.scrollHeight;

    axios.post("/chat", { message: message })
        .then(response => {
            let data = response.data;
            console.log("Dữ liệu nhận được từ backend:", data);

            if (!data || !data.response) {
                botMessageDiv.innerHTML = "Chatbot không thể trả lời ngay bây giờ.";
            } else {
                // Làm sạch nội dung phản hồi:
                // - Loại bỏ "Bạn:" ở đầu nếu có
                // - Thay *** và ** bằng xuống dòng, còn * thành in nghiêng
                let cleanedResponse = data.response
                    .replace(/^Bạn:\s*/i, '')
                    .replace(/\*{3,}/g, '<br>')
                    .replace(/\*{2,}/g, '<br>')
                    .replace(/\*/g, '<i>');

                // Gán nội dung đã làm sạch cho botMessageDiv
                botMessageDiv.innerHTML = cleanedResponse;

                // Tạo một phần tử riêng để hiển thị mức độ tự tin
                if (data.confidence !== undefined) {
                    let confidenceBadge = document.createElement("li");
                    confidenceBadge.innerText = `->Độ tin cậy: ${(data.confidence * 100).toFixed(0)}% `;
                    confidenceBadge.style.fontSize = "12px"; // hoặc 10px nếu muốn nhỏ hơn nữa
                    confidenceBadge.style.fontFamily = "Times New Roman"; // hoặc font khác tùy chọn
                    botMessageDiv.appendChild(confidenceBadge);
                }

                // Thay thế các liên kết nếu có
                botMessageDiv.innerHTML = botMessageDiv.innerHTML.replace(/🔗\s*(https?:\/\/[^\s]+)/g, (match, url) => {
                    return ` <a href="${url}" target="_blank">${url}</a>`;
                });

                // Nếu có hình ảnh, chèn vào botMessageDiv
                if (data.image) {
                    let imageElement = document.createElement('img');
                    imageElement.src = `data:image/png;base64,${data.image}`;
                    imageElement.alt = 'Generated Image';
                    imageElement.classList.add('chat-image');
                    botMessageDiv.appendChild(imageElement);
                }
            }

            // Cuộn xuống cuối hộp chat sau khi cập nhật nội dung
            setTimeout(() => {
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 100);
        })
        .catch(error => {
            console.error(error);
            alert("Lỗi gửi tin nhắn! Hãy thử lại sau.");
        })
        .finally(() => {
            sendButton.disabled = false;
        });
}

function handleKeyPress(event) {
    if (event.key === "Enter") {
        sendMessage();
    }
}

// Gắn sự kiện keypress cho input "message"
document.getElementById("message").addEventListener("keypress", handleKeyPress);
