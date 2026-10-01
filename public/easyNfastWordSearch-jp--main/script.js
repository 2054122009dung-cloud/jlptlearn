function updateColor() {
    var input = document.getElementById('wordInput');
    var resultDiv = document.getElementById('result');
    var word = input.value;

    // Use regular expressions to identify Kanji, Hiragana, and Katakana characters
    var kanjiRegex = /[\u4e00-\u9faf]/;
    var hiraganaRegex = /[\u3040-\u309F]/;
    var katakanaRegex = /[\u30A0-\u30FF]/;

    var resultHtml = '';

    for (var i = 0; i < word.length; i++) {
        var char = word[i];

        if (kanjiRegex.test(char)) {
            resultHtml += '<a class="kanji">' + char + '</a>';
        } else if (hiraganaRegex.test(char)) {
            resultHtml += '<a class="hiragana">' + char + '</a>';
        } else if (katakanaRegex.test(char)) {
            resultHtml += '<a class="katakana">' + char + '</a>';
        } else {
            resultHtml += char;
        }
    }

    resultDiv.innerHTML = resultHtml;
}

function generateLinks() {
    var word = document.getElementById('wordInput').value;
    var maziiLink = 'https://mazii.net/vi-VN/search/word/javi/' + encodeURIComponent(word);
    var gooLink = 'https://dictionary.goo.ne.jp/srch/all/' + encodeURIComponent(word) + '/m0u/';
    var googleImageLink = 'https://www.google.com/search?q=' + encodeURIComponent(word) + '&tbm=isch&ved=2ahUKEwjnlr_mhKiDAxXtTPUHHQzCCTUQ2-cCegQIABAA&oq=' + encodeURIComponent(word) + '&gs_lcp=CgNpbWcQDDIHCAAQgAQQBDIHCAAQgAQQBDIHCAAQgAQQBDIHCAAQgAQQBDIHCAAQgAQQBDIHCAAQgAQQBDIHCAAQgAQQBDIFCAAQgAQyBQgAEIAEMgUIABCABDoECCMQJzoICAAQgAQQsQNQxAhYohFgoiNoAHAAeACAAaQBiAHIBZIBAzYuMZgBAKABAaoBC2d3cy13aXotaW1nwAEB&sclient=img&ei=Hh-IZeePAe2Z1e8PjISnqAM&bih=1026&biw=2029';
    var wikipediaLink = 'https://ja.wikipedia.org/wiki/' + encodeURIComponent(word);
    var googlePronunciationLink = 'https://www.google.com/search?q=' + encodeURIComponent(word) + 'の発音を聞く';
    var youtubePronunciationLink = 'https://www.youtube.com/results?search_query=' + encodeURIComponent(word) + 'の発音';
    var kotobankLink = 'https://kotobank.jp/search?q=' + encodeURIComponent(word);

    document.getElementById('result').innerHTML = '<p>Mazii Link: <a href="' + maziiLink + '" target="_blank">' + word + '</a></p>' +
                                                    '<p>Goo Dictionary Link: <a href="' + gooLink + '" target="_blank">' + word + '</a></p>' +
                                                    '<p>Google Image Search: <a href="' + googleImageLink + '" target="_blank">' + word + ' (Images)</a></p>' +
                                                    '<p>Wikipedia Link: <a href="' + wikipediaLink + '" target="_blank">' + word + ' (Wikipedia)</a></p>' +
                                                    '<p>Kotobank Link: <a href="' + kotobankLink + '" target="_blank">' + word + ' (Kotobank)</a></p>' +
                                                    '<button onclick="listenGooglePronunciation()">Listen on Google</button>' +
                                                    '<button onclick="listenYouTubePronunciation()">Listen on YouTube</button>';
}

function changeBackgroundColor(color) {
    document.body.style.backgroundColor = color;
}

function listenGooglePronunciation() {
    var word = document.getElementById('wordInput').value;
    window.open('https://www.google.com/search?q=' + encodeURIComponent(word) + 'の発音を聞く', '_blank');
}

function listenYouTubePronunciation() {
    var word = document.getElementById('wordInput').value;
    window.open('https://www.youtube.com/results?search_query=' + encodeURIComponent(word) + 'の発音', '_blank');
}
function changeBackground(color) {
    document.body.style.background = color;
}
document.addEventListener("DOMContentLoaded", function () {
    let notes = JSON.parse(localStorage.getItem("notes")) || [];

    function renderNotes() {
        const noteList = document.getElementById("noteList");
        noteList.innerHTML = "";
        notes.forEach((note, index) => {
            let li = document.createElement("li");
            li.textContent = note;
            li.style.padding = "10px";
            li.style.margin = "5px 0";
            li.style.background = "#fff4a3";
            li.style.borderRadius = "5px";
            li.style.cursor = "pointer";
            li.onclick = () => deleteNote(index);
            noteList.appendChild(li);
        });
    }

    function saveNote() {
        const noteInput = document.getElementById("noteInput").value.trim();
        if (noteInput !== "") {
            notes.push(noteInput);
            localStorage.setItem("notes", JSON.stringify(notes));
            document.getElementById("noteInput").value = "";
            renderNotes();
        }
    }

    function clearNotes() {
        localStorage.removeItem("notes");
        notes = [];
        renderNotes();
    }

    function deleteNote(index) {
        notes.splice(index, 1);
        localStorage.setItem("notes", JSON.stringify(notes));
        renderNotes();
    }

    function exportJSON() {
        const jsonStr = JSON.stringify(notes, null, 4);
        const blob = new Blob([jsonStr], { type: "application/json" });
        const a = document.createElement("a");
        a.href = URL.createObjectURL(blob);
        a.download = "notes.json";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    document.getElementById("saveBtn").addEventListener("click", saveNote);
    document.getElementById("clearBtn").addEventListener("click", clearNotes);
    document.getElementById("exportBtn").addEventListener("click", exportJSON);

    renderNotes();
});
