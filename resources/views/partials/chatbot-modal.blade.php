<div class="modal fade" id="chatbotModal" tabindex="-1" aria-labelledby="chatbotModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chatbotModalLabel">AI Assistant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="chatMessages">
                <!-- Messages will appear here -->
            </div>
            <div class="modal-footer">
                <input type="text" id="userMessage" class="form-control" placeholder="Type your message...">
                <button onclick="sendMessage()" class="btn btn-primary">Send</button>
            </div>
        </div>
    </div>
</div>

<script>
function sendMessage() {
    const messageInput = document.getElementById('userMessage');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    // Display user message
    displayMessage(message, 'user');
    messageInput.value = '';
    
    // Show loading indicator
    const loadingMessage = displayMessage('Thinking...', 'bot loading');
    
    fetch('/chatbot/message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            message: message,
            type: 'text'
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // Remove loading message
        if (loadingMessage) {
            loadingMessage.remove();
        }
        
        if (data.status === 'success') {
            displayMessage(data.message, 'bot');
        } else {
            throw new Error(data.message || 'Unknown error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (loadingMessage) {
            loadingMessage.remove();
        }
        displayMessage('Sorry, there was an error processing your request. Please try again.', 'bot error');
    });
}

function displayMessage(message, type) {
    const chatMessages = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;
    messageDiv.textContent = message;
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
    return messageDiv;
}

// Add event listener for Enter key
document.getElementById('userMessage').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});
</script>

<style>
.message {
    margin: 10px;
    padding: 10px;
    border-radius: 10px;
    max-width: 80%;
}

.user {
    background-color: #007bff;
    color: white;
    margin-left: auto;
}

.bot {
    background-color: #f8f9fa;
    color: black;
    margin-right: auto;
}

.error {
    background-color: #dc3545;
    color: white;
}

.loading {
    background-color: #e9ecef;
    color: #6c757d;
}

#chatMessages {
    height: 400px;
    overflow-y: auto;
    padding: 10px;
}
</style>