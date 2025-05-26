# 🌍 X-Plorer

**Explore iconic landmarks in immersive 3D.**  
**X-Plorer** is a full-stack web application that helps users discover the world's wonders with integrated 3D models, smart chatbot interaction, and detailed place descriptions — all powered by **PHP**, **MySQL**, **JavaScript**, and open-source AI models.

---

## 🚀 Project Highlights

- 🏛️ **User Interface** for browsing monuments with:
  - Interactive 3D models (`.glb`)
  - Historical & architectural descriptions
  - Tourist ratings
  - Google Maps embedded views

- 🤖 **AI-powered Chatbot**
  - Built with integrated open LLMs
  - Helps users explore places via conversation
  - Lightweight, efficient and dynamic

- 🧑‍💼 **Agent Dashboard (Admin)**
  - Add new places, images, models
  - Dynamically generate HTML files
  - Inputs: name, facts, categories, GLB files, images, and Google Map link

- 🗃️ **Backend Database (MySQL via phpMyAdmin)**
  - Stores user info, place data, and chatbot interactions

- 💻 **Completely Self-Hosted**
  - No Firebase, No Vercel, No third-party cloud
  - Local PHP + MySQL environment (e.g., XAMPP/LAMP)

---

## 🧱 Tech Stack

| Layer        | Technology             |
|--------------|-------------------------|
| 💡 Frontend  | HTML5, CSS3, JavaScript |
| 🧠 Backend    | PHP                     |
| 🗃 Database   | MySQL (phpMyAdmin)      |
| 🧠 Chatbot    | Open-source LLM API     |
| 🧊 3D Viewer  | `<model-viewer>` (.glb) |
| 📍 Maps       | Embedded Google Maps    |

---

## 📂 Folder Structure
xplorer/
├── index.html # Landing page with 3D intro
├── assets/
│ ├── css/ # style.css, authenticate.css, etc.
│ ├── js/ # script.js, chatbot.js, etc.
│ ├── [places folders]/ # folders with image/model assets
├── places/ # Auto-generated place pages
├── visit/
│ └── visit.html # Gallery of places
├── authenticate/
│ ├── signin.html
│ ├── signup.html
├── chatbot/
│ └── chatbot.html # LLM-integrated chatbot UI
├── agent/
│ ├── agent.html # Admin dashboard
│ └── upload.php # PHP handler for upload
└── database/
└── xplorer.sql # MySQL schema (optional)


---

## ✨ How It Works

### 👥 Normal Users
- Visit homepage, explore 3D monuments
- Click on cards to open detailed place pages
- Each page includes:
  - High-res image
  - GLB 3D model
  - Historical and cultural insights
  - Google Maps iframe
  - ⭐ Ratings and fun facts

### 🤖 AI Chatbot
- Ask about places, travel suggestions, or details
- Uses open-source LLMs (like llama.cpp, OpenRouter, etc.)
- Displayed in an interactive chat window

### 🛠️ Admin Panel (Agent)
- Upload form for:
  - Place Name, Descriptions, Fun Facts
  - Image file (.jpg/.png)
  - 3D GLB model file
  - Google Maps link
- PHP script dynamically generates:
  - New `.html` pages in `/places/`
  - New card entries in `/visit/visit.html`

---

## 🧑‍💻 Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone https://github.com/RahulR-007/xplorer
   cd xplorer

