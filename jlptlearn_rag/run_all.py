import subprocess
import sys
import os

print("🔄 Khởi động hệ thống AI Chatbot...\n")

# Xác định đường dẫn Python trong .venv
venv_python = os.path.join(os.getcwd(), ".venv", "Scripts", "python.exe")

# Danh sách các file cần chạy
scripts = ["data.py", "retriever.py", "app.py"]

for script in scripts:
    print(f"🚀 Đang chạy {script}...\n")
    try:
        result = subprocess.run([venv_python, script], capture_output=True, text=True, check=True)
        print(result.stdout)  # In kết quả đầu ra
    except subprocess.CalledProcessError as e:
        print(f"❌ Lỗi khi chạy {script}:\n", e.stderr)

print("🔥 Hệ thống đã sẵn sàng!")
