// Mobile Navigation
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');

    if (hamburger) {
        hamburger.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }

    // Query Form Handler
    const queryForm = document.getElementById('queryForm');
    if (queryForm) {
        queryForm.addEventListener('submit', handleQuerySubmit);
    }

    // Quick Questions Handler
    const quickQuestions = document.querySelectorAll('.quick-question-card');
    quickQuestions.forEach(card => {
        card.addEventListener('click', function() {
            const type = this.dataset.type;
            const question = this.dataset.question;
            
            document.getElementById('queryType').value = type;
            document.getElementById('question').value = question;
            
            submitQuery(type, question);
        });
    });

    // Contact Form Handler
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', handleContactSubmit);
    }

    // Clear Chat Handler
    const clearChatBtn = document.getElementById('clearChat');
    if (clearChatBtn) {
        clearChatBtn.addEventListener('click', clearChat);
    }
});

// Handle Query Form Submission
function handleQuerySubmit(e) {
    e.preventDefault();
    
    const queryType = document.getElementById('queryType').value;
    const question = document.getElementById('question').value;
    
    if (!queryType || !question.trim()) {
        alert('Please select a query type and enter your question.');
        return;
    }
    
    submitQuery(queryType, question);
}

// Submit Query via AJAX
function submitQuery(queryType, question) {
    // Add user message to chat
    addMessageToChat('user', question);
    
    // Show loading indicator
    showLoading(true);
    
    // AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/get_response.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            showLoading(false);
            
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        addMessageToChat('bot', response.answer);
                    } else {
                        addMessageToChat('bot', 'Sorry, I couldn\'t find an answer to your question. Please try rephrasing or contact our support team.');
                    }
                } catch (error) {
                    addMessageToChat('bot', 'Sorry, there was an error processing your request. Please try again.');
                }
            } else {
                addMessageToChat('bot', 'Sorry, there was a connection error. Please check your internet connection and try again.');
            }
        }
    };
    
    const params = `query_type=${encodeURIComponent(queryType)}&question=${encodeURIComponent(question)}`;
    xhr.send(params);
    
    // Clear form
    document.getElementById('question').value = '';
}

// Add Message to Chat Window
function addMessageToChat(sender, message) {
    const chatWindow = document.getElementById('chatWindow');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'chat-message';
    
    const messageClass = sender === 'user' ? 'user-message' : 'bot-message';
    const avatarIcon = sender === 'user' ? 'fas fa-user' : 'fas fa-robot';
    
    messageDiv.innerHTML = `
        <div class="${messageClass}">
            <div class="message-avatar">
                <i class="${avatarIcon}"></i>
            </div>
            <div class="message-content">
                <p>${message}</p>
            </div>
        </div>
    `;
    
    chatWindow.appendChild(messageDiv);
    chatWindow.scrollTop = chatWindow.scrollHeight;
}

// Show/Hide Loading Indicator
function showLoading(show) {
    const loadingIndicator = document.getElementById('loadingIndicator');
    if (loadingIndicator) {
        loadingIndicator.style.display = show ? 'flex' : 'none';
    }
}

// Clear Chat Window
function clearChat() {
    const chatWindow = document.getElementById('chatWindow');
    if (chatWindow) {
        chatWindow.innerHTML = `
            <div class="welcome-message">
                <div class="bot-message">
                    <div class="message-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="message-content">
                        <p>Welcome to Krishi Assistant! Please select a query type and ask your farming question.</p>
                    </div>
                </div>
            </div>
        `;
    }
}

// Handle Contact Form Submission
function handleContactSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const xhr = new XMLHttpRequest();
    
    xhr.open('POST', 'php/contact.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const messageDiv = document.getElementById('contactMessage');
            try {
                const response = JSON.parse(xhr.responseText);
                messageDiv.style.display = 'block';
                messageDiv.className = `contact-message ${response.success ? 'success' : 'error'}`;
                messageDiv.textContent = response.message;
                
                if (response.success) {
                    e.target.reset();
                }
            } catch (error) {
                messageDiv.style.display = 'block';
                messageDiv.className = 'contact-message error';
                messageDiv.textContent = 'Error sending message. Please try again.';
            }
        }
    };
    
    xhr.send(formData);
}
