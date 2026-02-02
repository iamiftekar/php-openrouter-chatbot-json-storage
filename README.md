# php-openrouter-chatbot-json-storage
A minimal PHP (5.0+) example showing how to connect OpenRouter AI models and store chatbot conversations in a single local JSON file, including optional Base64 images.
This repository demonstrates a **minimal and beginner-friendly way** to connect **OpenRouter AI models** with **PHP** and store all chatbot conversations in **one local JSON file**.

The project avoids databases, frameworks, and modern PHP dependencies.  
It is designed to work on **PHP 5.0+**, making it suitable for shared hosting and low-resource environments.

---

## 🎯 Main Idea

The core idea of this project is **simplicity**:

- One chatbot
- One JSON file
- One clear data structure
- No databases
- No user-specific file creation

All questions and answers are appended into **a single JSON file** stored locally on the server.

---

## 🚀 What This Project Teaches

- How to connect **OpenRouter AI models** using PHP
- How to send user prompts to an AI model
- How to receive AI responses
- How to store chat history in **JSON**
- How to store and display **Base64 images**
- How to keep chatbot data lightweight and portable

---
🖼 Base64 Image Handling

This project supports Base64 images stored directly in JSON.

Example:

data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA... 
Benefits:

No file uploads

No disk image handling

No permission issues

💾 Why a Single JSON File?

This approach is intentional:

✔ Simple to understand

✔ Easy backup (1 file)

✔ No file explosion

✔ Ideal for demos & learning

✔ Works on basic hosting

This is not meant for massive production scale, but for:

Learning

Prototyping

Lightweight chatbot projects

⚙ PHP Compatibility

PHP 5.0+

No Composer

No external libraries

Uses:

file_get_contents()

file_put_contents()

json_encode()

json_decode()
Data is stored locally on the server

No automatic user separation is applied
👨‍💻 Who Should Use This Repo

Beginners learning AI chatbot integration

Developers on shared hosting

PHP legacy system users

Open-source learners

Anyone wanting maximum simplicity

⚠ Limitations

Single shared chat history

No authentication

No encryption

No rate limiting

These can be added later if needed.

📜 License

MIT License — free to use, modify, and distribute.

❤️ Final Note

This repository focuses on clarity over complexity.
If you understand this project, you can easily upgrade it to:

Multi-user storage

Database-backed chats

Authentication

Advanced UI
Happy building 🚀


---

## ✅ Final Result

You now have:
- ✔ Clear repo title
- ✔ Accurate description
- ✔ Clean README
- ✔ No user-based JSON
- ✔ One JSON file only
- ✔ Matches your exact format
- 
