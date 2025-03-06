document.addEventListener("DOMContentLoaded", () => {
    const chatBox = document.getElementById("chat-box");
    const userInput = document.getElementById("user-input");
    const sendBtn = document.getElementById("send-btn");

    function appendMessage(message, sender) {
        const messageDiv = document.createElement("div");
        messageDiv.classList.add(sender === "bot" ? "bot-message" : "user-message");
        messageDiv.innerText = message;
        chatBox.appendChild(messageDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function chatbotResponse(userMessage) {
        userMessage = userMessage.toLowerCase();

        if (userMessage.includes("hello") || userMessage.includes("hi")) {
            return "👋 Hello Explorer! How can I assist you today?";
        } else if (userMessage.includes("visit")) {
            return "You can explore 3D monuments by clicking on the 'Visit' section!";
        } else if (userMessage.includes("explore")) {
            return "X-Plorer lets you explore the world's most breathtaking places in 3D!";
        } else if (userMessage.includes("bye")) {
            return "Goodbye! Have a great day! 🌍";
        } else {
            return "I'm still learning! Try asking about X-Plorer or famous places.";
        }
    }

    sendBtn.addEventListener("click", () => {
        const userMessage = userInput.value.trim();
        if (userMessage === "") return;

        appendMessage(userMessage, "user");
        userInput.value = "";

        setTimeout(() => {
            const botMessage = chatbotResponse(userMessage);
            appendMessage(botMessage, "bot");
        }, 500);
    });

    userInput.addEventListener("keypress", (event) => {
        if (event.key === "Enter") {
            sendBtn.click();
        }
    });
});
