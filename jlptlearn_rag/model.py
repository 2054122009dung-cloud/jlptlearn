import os
import google.generativeai as genai

# Get API key from environment variable
API_KEY = os.getenv("GEMINI_API_KEY")

if not API_KEY:
    raise ValueError("API key not found. Set the GEMINI_API_KEY environment variable.")

# Configure Gemini AI with the API key
genai.configure(api_key=API_KEY)

# List available models
models = genai.list_models()

for model in models:
    print(f"Model ID: {model.name}")
    print(f"Description: {getattr(model, 'description', 'No description available')}\n")
