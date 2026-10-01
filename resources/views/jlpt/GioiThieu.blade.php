
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JLPT Dashboard</title>


</head>
<body>
    <div class="container" style="width:900px">
        <!-- Navigation header -->
        <div class="header">
            <div class="logo">
                <img src="{{ asset('images/jlpt_icon.png') }}" alt="JLPT Study Logo"
                style="width: 120px; height: auto; border-radius: 10px;">
                       </div>
            <div class="navigation">
                <ul>
                    <li><a href="#">Trang chủ</a></li>
                    <li><a href="#about">Về chúng tôi</a></li>
                    <li><a href="#goal">Mục tiêu của tôi</a></li>
                    <li><a href="#contact">Liên hệ</a></li>
                </ul>
            </div>
        </div>
        <li style="list-style-type: none"><H1 id="play-audio">CHÀO MỪNG BẠN ĐẾN VỚI JLPTLEARN</H1></li>

        <!-- JLPT Level Banner -->
        <div class="level-banner">
            <li class="level n1">Trình độ N1</li>
            <li class="level n2">Trình độ N2</li>
            <li class="level n3">Trình độ N3</li>
            <li class="level n4">Trình độ N4</li>
            <li class="level n5">Trình độ N5</li>
        </div>
        <!-- Main content -->
        <h1 class="header-text">Tài liệu học cho các cấp độ JLPT từ N5 đến N1</h1>
        <div id="goal" class="goal">
            <h2>Mục tiêu của tôi</h2>
            <p>Tôi tạo trang web này để giúp mọi người hiểu rõ hơn về những gì có thể mong đợi trong Kỳ thi Năng lực Tiếng Nhật (日本語能力試験).</p>
            <p>Tôi đã gõ lại các đề thi cũ, dịch câu và tạo một số tài liệu học tập cơ bản, như bài kiểm tra trắc nghiệm, để giúp học viên kiểm tra xem họ có đang đi đúng hướng hay không.</p>
        </div>
        <div id="about" class="background">
            <h2>Bối cảnh</h2>
            <p>Tôi đã đậu cấp độ n2 cũ vào năm 2024 và bắt đầu tạo các trang web bằng cách dịch câu từ các đề thi cũ như một phần trong phương pháp học của mình.</p>
            <p>Vì là một kỹ sư phần mềm, tôi bắt đầu viết các chương trình để biên soạn danh sách và trang web - tự động hóa các tác vụ như thu thập kanji ghép-furigana từ từ điển.</p>
            <p>Tôi thực sự thích làm điều này và nhận ra rằng mình có thể tạo các trang web hữu ích mà không cần phải đột phá trong phương pháp học hay cung cấp điều kiện luyện thi thực tế.</p>
            <p>Tôi chỉ đơn giản sử dụng đề thi cũ để dịch, biên soạn, tạo giao diện tương tác và điền vào chỗ trống - giúp bạn xem văn bản với nhiều 'hỗ trợ' học tập khác nhau.</p>
        </div>
        <h1 >Đếm ngược đến ngày thi</h1>
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
        <div class="call-to-action">
            <div class="container">
                    <a href="{{ url('/dangky') }}" class="btn">Đăng ký ngay</a>
                    <a href="{{ url('/dangnhap') }}" class="btn">Đăng nhập ngay</a>
                <div class="image-container">
                    <img src="{{ asset('images/jlpt_image.jpg') }}" alt="JLPT Image" class="intro-image">
                </div>
            </div>        </div>


        <!-- Study Materials Section -->
        <div class="study-materials">
            <h2>Tài liệu học</h2>
            <p>JLPT Mới từ năm 2010</p>
            <p>Từ năm 2010, các cấp độ đã thay đổi và không còn danh sách chính thức nào nữa - xem <a href="#">So sánh Kỳ thi Mới và Cũ</a>. Để biết thêm thông tin, hãy xem <a href="#">Trang Wiki JLPT</a>.</p>
            <p>Tôi sử dụng các đề thi cũ vì chúng vẫn hữu ích - ngay cả khi định dạng đã thay đổi theo thời gian.</p>
        </div>


        <!-- News Section -->
        <div class="news-section" style="padding: 20px">
            <h2> Tin tức liên quan </h2>
            <div id="news-container"></div>

            <div id="news-container">
                <!-- Tin tức sẽ được hiển thị ở đây -->
            </div>
        <!-- Social Media Section -->
        <div id="social-media" class="social-media">
            <a href="https://zalo.me/g/wazxtv480" target="_blank">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg" alt="Zalo" class="social-icon">
            </a>
            <a href="https://www.facebook.com/messages/t/6230638523731696" target="_blank">
                <img src="{{ asset('images/facebook.png') }}" alt="JLPT Image" class="">
            </a>
            <a href="https://www.youtube.com/@LearnJapaneseJLPT" target="_blank">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/YouTube_Logo_2017.svg" alt="YouTube" class="social-icon">
            </a>
        </div>


        <!-- Contact Section -->
              <!-- Phần Liên Hệ -->
              <div id="contact" class="contact">
                <h2>Liên Hệ</h2>
                <p>Tôi là Dũng, người sáng lập JLPTLearn.</p>
                <p>Nếu bạn có bất kỳ góp ý hoặc câu hỏi nào, hãy liên hệ với tôi qua email: <a href="mailto:admin@jlptlearn.com">admin@jlptlearn.com</a></p>
            </div>

        <!-- Footer -->
        <div class="footer">
            <p>© 2025 JLPT Study Page. All rights reserved.</p>
        </div>
    </div>
    {{-- <video class="video-background" autoplay loop muted>
        <source src="{{ asset('videos/amazing-mountain-lake-and-waterfall-panorama.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video> --}}


    <audio id="bg-audio">
        <source src="{{ asset('audio/output.wav') }}" type="audio/wav">
        Trình duyệt không hỗ trợ phát âm thanh.
    </audio>

    {{-- <iframe class="video-background" id="video-frame" width="560" height="315" src="https://www.youtube.com/embed/zavCTwkGseg?autoplay=1&mute=0"
    title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write;
    encrypted-media; gyroscope; picture-in-picture; web-share" allow="autoplay" allowfullscreen></iframe> --}}

    <iframe class="video-background" id="video-frame" width="560" height="315"
    src="https://www.youtube.com/embed/zavCTwkGseg?enablejsapi=1"
    title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write;
    encrypted-media; gyroscope; picture-in-picture; web-share" allow="autoplay" allowfullscreen></iframe>

</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

{{-- <script>
    document.getElementById("play-audio").addEventListener("click", function () {
        var audio = document.getElementById("bg-audio");
        audio.play().then(() => {
            console.log("Âm thanh đang phát!");
        }).catch(error => {
            console.error("Không thể phát âm thanh:", error);
        });
    });
    document.getElementById("play-audio").addEventListener("click", function() {
        var iframe = document.getElementById("video-frame");
        iframe.src += "&autoplay=1"; // Thêm autoplay khi người dùng bấm
    });
    // Đặt mã JavaScript fetchNews ở đây

</script> --}}
<script>
    let timestamps = [0, 217, 474, 719, 1034, 1279, 1539, 1789, 2057, 2224, 2506, 2786, 3116];
    let player;

    function getRandomTime() {
        return timestamps[Math.floor(Math.random() * timestamps.length)];
    }

    function onYouTubeIframeAPIReady() {
        player = new YT.Player('video-frame', {
            events: {
                'onReady': function(event) {
                    let randomTime = getRandomTime();
                    event.target.seekTo(randomTime, true);
                    event.target.playVideo();
                }
            }
        });
    }

    document.getElementById("play-audio").addEventListener("click", function () {
        let audio = document.getElementById("bg-audio");
        let randomTime = getRandomTime();

        // Phát âm thanh
        audio.play().then(() => {
            console.log("Âm thanh đang phát!");
        }).catch(error => {
            console.error("Không thể phát âm thanh:", error);
        });

        // Tua video đến thời điểm ngẫu nhiên và phát lại
        if (player) {
            player.seekTo(randomTime, true);
            player.playVideo();
        }
    });

    // Nhúng API YouTube
    let tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    document.head.appendChild(tag);

</script>

{{-- <script src="{{ secure_asset('js/app.js') }}"></> --}}

