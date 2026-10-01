import json
import logging
from difflib import SequenceMatcher

def get_similarity(user_message, question):
    """
    Tính toán độ tương đồng giữa câu hỏi người dùng và câu hỏi trong dữ liệu.
    :param user_message: Câu hỏi của người dùng
    :param question: Câu hỏi trong dữ liệu
    :return: Độ tương đồng (từ 0 đến 1)
    """
    # Sử dụng SequenceMatcher để tính độ tương đồng giữa câu hỏi người dùng và câu hỏi trong dữ liệu
    return SequenceMatcher(None, user_message.lower(), question.lower()).ratio()

def search_data(user_message, threshold=60):
    """
    Tìm kiếm câu hỏi của người dùng trong data.json và trả về câu trả lời phù hợp.
    :param user_message: Câu hỏi của người dùng
    :param threshold: Ngưỡng điểm tương đồng (0-100)
    :return: Dữ liệu trả về hoặc None nếu không tìm thấy
    """
    # Đọc dữ liệu từ file data.json
    try:
        with open('data.json', 'r', encoding='utf-8') as f:
            data = json.load(f)
    except Exception as e:
        logging.error(f"❌ Lỗi khi đọc file data.json: {e}")
        return None, 0  # Trả về độ tự tin là 0 trong trường hợp lỗi

    # Danh sách các câu hỏi và câu trả lời
    questions_and_answers = data.get("questions", [])

    best_match = None
    best_similarity = 0

    # Duyệt qua các câu hỏi trong data.json để tìm sự tương đồng cao nhất
    for item in questions_and_answers:
        question = item.get('question', '').strip()
        text = item.get('text', '').strip()

        # Tính toán độ tương đồng giữa câu hỏi người dùng và câu hỏi trong dữ liệu
        similarity = get_similarity(user_message, question)
        logging.debug(f"Tính tương đồng: '{user_message}' và '{question}' = {similarity:.2f}")

        # Nếu độ tương đồng lớn hơn ngưỡng, cập nhật câu trả lời tốt nhất
        if similarity > best_similarity:
            best_similarity = similarity
            best_match = text

    # Nếu độ tương đồng đủ cao (lớn hơn threshold), trả về kết quả
    if best_similarity == 1.0:  # Nếu tương đồng hoàn toàn, trả về độ tự tin 100%
        logging.debug(f"✅ Tìm thấy câu trả lời phù hợp với độ tương đồng 1.00.")
        return best_match, 100
    if best_similarity >= 0.5:  # Nếu tương đồng hoàn toàn, trả về độ tự tin 100%
        logging.debug(f"✅ Tìm thấy câu trả lời gần đúng với độ tương đồng trên 50%.")
        return best_match, 105
    if best_similarity >= 0.3:  # Nếu tương đồng hoàn toàn, trả về độ tự tin 100%
        logging.debug(f"✅ Tìm thấy câu trả lời có vẻ đúng với độ tương đồng trên 50%.")
        return best_match, 110

    if best_similarity * 100 >= threshold:
        logging.debug(f"✅ Tìm thấy câu trả lời phù hợp với độ tương đồng {best_similarity:.2f}.")
        return best_match, best_similarity * 100

    # Nếu không tìm thấy kết quả phù hợp, trả về None và độ tự tin tối thiểu
    logging.warning(f"⚠️ Không tìm thấy câu trả lời phù hợp cho câu hỏi: '{user_message}'")
    return None, 0.1  # Đảm bảo độ tự tin không phải là 0
