from flask import Flask, request, jsonify
import google.generativeai as genai
import os
import time
from serpapi import GoogleSearch
from retriever import search_data
import logging
from dotenv import load_dotenv
load_dotenv()  # Load file .env vào os.environ

app = Flask(__name__)
# Cấu hình API key từ biến môi trường (không hardcode)
API_KEY = os.getenv("GEMINI_API_KEY")
if not API_KEY:
    raise ValueError("❌ Missing API key for Gemini. Hãy đặt GEMINI_API_KEY trong biến môi trường!")
genai.configure(api_key=API_KEY)
print("✅ API key đã được cấu hình cho Gemini.")

# Cấu hình logging để dễ dàng theo dõi
logging.basicConfig(level=logging.DEBUG)

def calculate_relevance(retrieved_text, user_input):
    """
    Tính điểm tin cậy cho dữ liệu retriever (score từ 0 đến 1).
    Cải tiến: Nếu số từ ít, ta vẫn cho điểm cơ bản tối thiểu.
    """
    words = retrieved_text.split()
    num_words = len(words)
    print(f"🔎 Tính điểm tin cậy: số từ trong retrieved_text = {num_words}")

    # Nếu câu trả lời ngắn, cho điểm cơ bản tối thiểu
    if num_words < 10:
        base_score = 0.5
        print("⚠️ Câu trả lời ngắn, gán điểm cơ bản = 0.5")
    else:
        base_score = num_words / 30.0  # Hệ số 30 thay vì 50
        print(f"💡 Điểm cơ bản tính được từ số từ: {base_score:.2f}")

    # Nếu câu hỏi của người dùng được lặp lại trong câu trả lời, giảm điểm
    if user_input.lower() in retrieved_text.lower():
        base_score -= 0.2
        print("⚠️ Phát hiện câu hỏi lặp lại trong câu trả lời, giảm điểm 0.2")

    final_score = min(max(base_score, 0), 1)
    print(f"✅ Điểm tin cậy cuối cùng: {final_score:.2f}")
    return final_score

def search_duckduckgo(query):
    """Tìm kiếm trên DuckDuckGo và trả về ít nhất 3 kết quả"""
    from duckduckgo_search import DDGS
    results = list(DDGS().text(query, max_results=3))  # Lấy 3 kết quả
    time.sleep(2)
    if results:
        response_text = "<br><br>".join(
            [f"{i+1}. {r['title']}<br>{r['body']}<br>🔗 <a href='{r['href']}' target='_blank'>{r['href']}</a>"
             for i, r in enumerate(results)]
        )
        return response_text

    return "Không tìm thấy kết quả nào."


def search_google(query):
    """Tìm kiếm bằng Google qua SerpAPI, trả về ít nhất 3 kết quả"""
    params = {
        "q": query,
        "api_key": "32967c7d0b1495509908e79e5e3b951845ae52c30a46086bc73f87f5e7b51c50",  # Thay bằng khóa API thật
        "num": 3,
        "hl": "vi",  # Ngôn ngữ kết quả
        "gl": "vn"   # Vị trí Việt Nam
    }

    try:
        search = GoogleSearch(params)
        results = search.get_dict()

        if "organic_results" in results:
            response_text = "<br><br>".join(
                [f"{i+1}. {r.get('title')}<br>{r.get('snippet')}<br>"
                 f"🔗 <a href='{r.get('link')}' target='_blank'>{r.get('link')}</a>"
                 for i, r in enumerate(results["organic_results"][:3])]
            )
            return response_text

        return "❌ Không tìm thấy kết quả nào."

    except Exception as e:
        return f"❌ Lỗi tìm kiếm: {e}"

def call_gemini(user_message):
    """Gọi API Gemini để sinh nội dung trả lời cho thông điệp của người dùng."""
    print(f"\n🔄 Gọi Gemini cho thông điệp: '{user_message}'")

    try:
        # Chọn model phù hợp
        model = genai.GenerativeModel("gemini-2.0-flash-lite-001")
        response = model.generate_content(user_message)

        if response and hasattr(response, 'text') and response.text:
            print("✅ Gemini trả về phản hồi thành công.")
            return response.text

        print("⚠️ Gemini không trả về phản hồi hợp lệ.")
        return "Bot không thể trả lời ngay bây giờ."

    except Exception as e:
        error_msg = f"Lỗi khi gọi Gemini: {e}"
        print("❌", error_msg)

        # Kiểm tra nếu có lỗi 429 (Quota exceeded)
        if "429" in str(e):
            # Tìm kiếm phần retry_delay trong thông báo lỗi
            match = re.search(r"retry_delay\s*{\s*seconds:\s*(\d+)", str(e))
            if match:
                delay_seconds = int(match.group(1))
                error_msg = f"🚨 Server quá tải, hãy thử lại sau {delay_seconds} giây."
                print(f"⏳ {error_msg}")
                return {"error": error_msg, "retry_delay": delay_seconds}

        return error_msg

def process_retrieved_data(user_message, retrieved_data, best_distance):
    """
    Xử lý dữ liệu từ retriever, tính toán độ tự tin và quyết định phản hồi.
    """
    # Đo độ tự tin: 1 nếu độ tương đồng là 1, nếu không tính toán tỷ lệ với best_distance
    confidence = float(max(0, (60 - best_distance) / 60))
    logging.debug(f"👉 Độ tự tin tính được: {confidence:.2f}")

    # Nếu độ tương đồng cực cao, độ tự tin phải bằng 1
    if best_distance == 1:
        confidence = 1.0
        logging.debug("✅ Độ tự tin cao, bằng 1.0 vì độ tương đồng đạt 1.")

    if confidence > 0.8:
        logging.debug("✅ Độ tự tin cao, trả về dữ liệu retriever.")
        return jsonify({"response": retrieved_data, "confidence": confidence})

    elif confidence > 0.5:
        logging.debug("🔀 Độ tự tin trung gian, kết hợp retriever và Gemini.")
        gemini_response = call_gemini(user_message)
        combined_response = f"{retrieved_data}\n\nThông tin bổ sung: {gemini_response}"
        return jsonify({"response": combined_response, "confidence": confidence})

    else:
        logging.debug("⚠️ Độ tự tin thấp, sử dụng phản hồi từ Gemini.")
        return jsonify({"response": call_gemini(user_message), "confidence": confidence})

@app.route('/chat', methods=['POST'])
def chat():
    try:
        data = request.json
        logging.debug("=== Nhận request từ client ===")
        logging.debug("Dữ liệu nhận được: %s", data)

        user_message = (data.get("message") or "").strip()
        if not user_message:
            logging.warning("⚠️ Không có message từ client.")
            return jsonify({"error": "Message is required"}), 400

        if len(user_message) < 3:
            logging.warning("⚠️ Câu hỏi quá ngắn, gọi Gemini luôn.")
            return jsonify({"response": call_gemini(user_message), "confidence": 0})

        # Kiểm tra nếu có bất kỳ từ khóa tìm kiếm nào trong câu
        search_keywords = ["tìm kiếm", "tim kiem", "search", "tra cứu", "find", "lookup", "look up", "探" ,"検索"]
        if any(keyword in user_message.lower() for keyword in search_keywords):
            logging.info("🔎 Phát hiện yêu cầu tìm kiếm, sử dụng tìm kiếm web.")
            search_result = search_google(user_message)
            if search_result:
                return jsonify({"response": search_result, "confidence": 1})
            else:
                logging.warning("⚠️tìm kiếm web không tìm thấy kết quả, fallback sang Gemini.")
                return jsonify({"response": call_gemini(user_message), "confidence": 0})

        # Tiếp tục xử lý dữ liệu nếu không phải tìm kiếm
        retrieved_data, best_distance = search_data(user_message, threshold=60)

        if retrieved_data:
            logging.debug(f"✅ Đã tìm thấy câu trả lời từ data.json: {retrieved_data}")

            # Tính độ tự tin từ khoảng cách tốt nhất (best_distance)
            confidence = float(max(0, (155 - best_distance) / 60))
            logging.debug("👉 Độ tự tin tính được: %.2f", confidence)

            # Nếu độ tương đồng hoàn toàn (1.0), trả về độ tự tin 1.00
            if confidence == 1.0:
                logging.debug(f"✅ Độ tự tin là 100%, trả về câu trả lời từ data.json.")
                return jsonify({"response": retrieved_data, "confidence": 1})

            # Trả về dữ liệu retriever nếu độ tự tin đủ cao (độ tự tin > 0.8)
            elif confidence >= 0.9:
                logging.debug(f"✅ Độ tự tin cao, trả về câu trả lời từ data.json với độ tự tin {confidence:.2f}.")
                return jsonify({"response": retrieved_data, "confidence": confidence})

            elif confidence > 0.5:
                # Trả về kết hợp giữa retriever và Gemini nếu độ tự tin trung gian
                gemini_response = call_gemini(user_message)
                combined_response = f"{retrieved_data}\n\nThông tin bổ sung: {gemini_response}"
                return jsonify({"response": combined_response, "confidence": confidence})

            else:
                # Trả về chỉ Gemini nếu độ tự tin thấp
                return jsonify({"response": call_gemini(user_message), "confidence": confidence})

        # Nếu không tìm thấy dữ liệu từ retriever, gọi Gemini
        logging.warning("⚠️ Retriever không trả về dữ liệu phù hợp, chuyển qua gọi Gemini.")
        return jsonify({"response": call_gemini(user_message), "confidence": 0})

    except Exception as e:
        logging.error("❌ Lỗi trong endpoint /chat: %s", str(e))
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
