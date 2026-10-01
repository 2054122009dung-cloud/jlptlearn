<div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <div style="    padding-top:10px;"></div>
    <!-- Chat Box -->
    <div class="chat-container">
        <h2>Chatbot</h2>
        <div class="chat-box" id="chat-box"></div>
        <div class="chat-input">
            <input type="text" id="message" placeholder="送信してください..." onkeypress="handleKeyPress(event)">

            <button id="send-button" onclick="sendMessage()">送信</button>
        </div>
    </div>
    <div class="container-s" > <!-- News Section -->
        <!-- Countdown -->
    <div class="countdown" id="countdown">
        <div class="countdown-box" id="days"></div>
        <div class="countdown-separator"><h1>日</h1></div>
        <div class="countdown-box" id="hours"></div>
        <div class="countdown-separator"><h1>時</h1></div>
        <div class="countdown-box" id="minutes"></div>
        <div class="countdown-separator"><h1>分</h1></div>
        <div class="countdown-box" id="seconds"></div>
        <div class="countdown-separator"><h1>秒</h1></div>
    </div>
    </div>
    <div class="container-s" style="max-width:60%"> <!-- News Section -->

        <div class="news-section" style="padding-left:5%">
            <h2> 関連情報 </h2>
            <div id="news-container"></div>
        </div>
    </div>

</div>

