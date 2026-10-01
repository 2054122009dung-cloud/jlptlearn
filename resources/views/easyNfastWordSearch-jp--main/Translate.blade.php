<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>日本語速度辞書 - Create Links</title>
    <link rel="stylesheet" href="{{ asset('easyNfastWordSearch-jp--main/style.css') }}">
</head>
<body>

<h1>日本語速度辞書</h1>

<label for="wordInput">Enter a word:</label>
<input type="text" id="wordInput" oninput="updateColor()">
<button onclick="generateLinks()">Generate Links</button>
<div id="result"></div>
<iframe width="560" height="315"
    src="https://www.youtube.com/embed/jfKfPfyJRdk?autoplay=1&loop=1"
    frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
</iframe>
<!-- Buttons for background color -->

<!-- Buttons for background video change -->
<div id="background-change">
    <button onclick="changeBackground('amazing-mountain-lake-and-waterfall-panorama.mp4')">Mountain Lake</button>
    <button onclick="changeBackground('beautiful-illustration-background.mp4')">Illustration</button>
    <button onclick="changeBackground('backgroundghibili.png', 'image')">Ghibli Background Morning</button>
    <button onclick="changeBackground('nightghibili.png', 'image')">Ghibli Background night</button>

</div>
</div>
<div id="noteContainer" style="margin-top:80px">
    <h2>📝 Quick Notes</h2>
    <textarea id="noteInput" placeholder="Write your note here..."></textarea>
    <button id="saveBtn">Save</button>
    <button id="clearBtn">Clear</button>
    <button id="exportBtn">Export JSON</button>
    <h3>📌 Saved Notes:</h3>
    <ul id="noteList"></ul>
</div>
<script src="{{ asset('easyNfastWordSearch-jp--main/script.js') }}"></script>
<div class="container">
    @include('components.ui')
</div>
{{-- <video class="video-background" autoplay loop muted>
    <source src="{{ asset('videos/amazing-mountain-lake-and-waterfall-panorama.mp4') }}" type="video/mp4">
    Your browser does not support the video tag.
</video> --}}
<video id="background-video" class="video-background" autoplay loop muted>
    <source id="video-source" src="{{ asset('videos/amazing-mountain-lake-and-waterfall-panorama.mp4') }}" type="video/mp4">
    Your browser does not support the video tag.
</video>
</body>
<script>
 function changeBackground(file, type = 'video') {
        let videoElement = document.getElementById('background-video');
        let videoSource = document.getElementById('video-source');

        if (type === 'video') {
            // Show the video background
            videoElement.style.display = "block";
            document.body.style.backgroundImage = "none"; // Remove image background
            videoSource.src = "{{ asset('videos/') }}" + "/" + file;
            videoElement.load();
        } else if (type === 'image') {
            // Set the image background
            videoElement.style.display = "none"; // Hide video
            document.body.style.backgroundImage = "url('{{ asset('images/') }}/" + file + "')";
        }
    }
</script>
</html>
