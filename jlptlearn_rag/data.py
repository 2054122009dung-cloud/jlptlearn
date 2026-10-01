from sentence_transformers import SentenceTransformer
import numpy as np
import faiss
import json

# Load mô hình tạo embedding
model = SentenceTransformer("paraphrase-MiniLM-L6-v2")

# Danh sách dữ liệu cần indexing
data = [
    {"id": 1, "text": "JLPT N1 là cấp độ khó nhất của kỳ thi tiếng Nhật."},
    {"id": 2, "text": "Cấu trúc ngữ pháp N2 thường xuất hiện trong giao tiếp hàng ngày."},
    {"id": 3, "text": "JLPT N3 là cấp độ trung cấp, phù hợp với người học có nền tảng vững."}
]

# Chuyển dữ liệu thành vector embeddings
texts = [item["text"] for item in data]
embeddings = model.encode(texts)  # Tạo embedding

# Tạo FAISS index để tìm kiếm nhanh
dimension = embeddings.shape[1]
index = faiss.IndexFlatL2(dimension)
index.add(np.array(embeddings))

# Lưu dữ liệu index vào file
faiss.write_index(index, "data.index")

# Lưu dữ liệu gốc để tra cứu
with open("data.json", "w", encoding="utf-8") as f:
    json.dump(data, f, ensure_ascii=False, indent=4)

print("✅ Dữ liệu đã được index thành công!")
