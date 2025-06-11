<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HireSmart &amp; AI Based Hiring Platform</title>

    <!-- Preload critical assets -->
    <link rel="preload" href="{{ asset('images/logo/szabist-logo.jpeg') }}" as="image">
    <link rel="preload" href="{{ asset('images/back.png') }}" as="image">
    
    <!-- Critical CSS -->
<style>
        /* Inline critical CSS */
        .loader {
    position: fixed;
            top: 0;
            left: 0;
    width: 100%;
    height: 100%;
            background: #fff;
            z-index: 9999;
    display: flex;
            justify-content: center;
    align-items: center;
}
        .loader.fade-out {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
}

/* Newsletter Subscription Styles */
.subscribe-form {
    margin-top: 20px;
}

.input-group {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.form-control {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 100%;
}

.subscribe-button {
    padding: 10px 20px;
    background: #006dd7;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.subscribe-button:hover {
    background: #0056aa;
}

.alert {
    padding: 10px;
    border-radius: 4px;
    margin-top: 10px;
    font-size: 14px;
}

.alert-success {
    background-color: rgba(220, 252, 231, 0.9);
    color: #166534;
}

.alert-danger {
    background-color: rgba(254, 226, 226, 0.9);
    color: #991b1b;
}

/* Chatbot Styles */
.chatbot-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #006dd7;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    z-index: 1000;
    border: none;
}

.chatbot-container {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 350px;
    height: 500px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    display: none;
    flex-direction: column;
    z-index: 1000;
}

.chatbot-container.active {
    display: flex;
}

.chatbot-header {
    padding: 15px;
    background: #006dd7;
    color: white;
    border-radius: 10px 10px 0 0;
    font-weight: bold;
}

.chatbot-messages {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
}

.message {
    margin-bottom: 10px;
    max-width: 80%;
    padding: 10px;
    border-radius: 10px;
}

.bot-message {
    background: #f0f2f5;
    margin-right: auto;
}

.user-message {
    background: #006dd7;
    color: white;
    margin-left: auto;
}

.chatbot-input {
    padding: 15px;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
}

.chatbot-input input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 20px;
    outline: none;
}

.chatbot-input button {
    padding: 10px 20px;
    background: #006dd7;
    color: white;
    border: none;
    border-radius: 20px;
    cursor: pointer;
}

.chatbot-input button:disabled {
    background: #ccc;
    cursor: not-allowed;
}
</style>

    <!-- Defer non-critical CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" media="print" onload="this.media='all'">
</head>

<body>
        <!-- Loader -->
        <div id="loader-wrapper">
            <div id="loader">
                <ul>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </div>
        </div>

    <div class="main-page-wrapper">
        <!-- Header -->
        <header class="fixed-top shadow-sm bg-white">
            <nav class="navbar navbar-expand-lg py-2 container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="Logo" height="50" width="auto">
                </a>
                <div class="ms-auto">
                    <ul class="navbar-nav flex-row gap-3">
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-medium text-dark" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-medium text-dark" href="{{ route('track.form') }}">Track Application</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary px-4 fw-medium" href="{{ route('login') }}">Login</a>
                        </li>
                    </ul>
                </div>
                </nav>
        </header>

        <!-- Hero Section -->
        <section class="hero-section d-flex align-items-center" style="min-height: 100vh; padding-top: 76px; background: url('{{ asset('images/back.png') }}') center/cover no-repeat fixed;">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8 animate__animated animate__fadeInUp">
                        <h1 class="display-3 fw-bold text-white mb-3">Transform Your Hiring Process</h1>
                        <h2 class="h3 fw-normal text-white mb-4 opacity-90">Intelligent Recruitment Solutions</h2>
                        <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-lg">
                            Explore Opportunities
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- What We Do Section -->
        <section class="py-5 bg-white">
            <div class="container py-5">
                <div class="row justify-content-center text-center mb-5">
                    <div class="col-lg-8 animate__animated animate__fadeInUp">
                        <h2 class="section-title mb-4">Revolutionizing Recruitment with AI</h2>
                        <p class="lead text-muted">Our intelligent platform combines AI-powered matching with streamlined workflows for next-generation hiring.</p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                        <div class="service-card h-100 p-4 text-center">
                            <div class="service-icon mb-4">
                                <i class="fas fa-robot fa-2x"></i>
                            </div>
                            <h4 class="h5 mb-3">AI-Powered Matching</h4>
                            <p class="text-muted mb-0">Smart candidate ranking system that finds your perfect match</p>
                        </div>
                    </div>
                    
                    <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                        <div class="service-card h-100 p-4 text-center">
                            <div class="service-icon mb-4">
                                <i class="fas fa-cogs fa-2x"></i>
                            </div>
                            <h4 class="h5 mb-3">Automated Workflows</h4>
                            <p class="text-muted mb-0">Streamlined processes that save time and reduce complexity</p>
                        </div>
                    </div>
                    
                    <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                        <div class="service-card h-100 p-4 text-center">
                            <div class="service-icon mb-4">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                            <h4 class="h5 mb-3">Analytics & Insights</h4>
                            <p class="text-muted mb-0">Data-driven decisions for better hiring outcomes</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonial Section -->
        <section class="testimonial-section py-5">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center animate__animated animate__fadeInUp">
                        <h2 class="section-title mb-5">What Our Leaders Say</h2>
                        <div class="testimonial-card p-4 p-md-5">
                            <div class="testimonial-image mb-4">
                                <img src="{{ asset('images/101.png') }}" alt="Mr. Khusro Pervaiz" class="rounded-circle" width="120" height="120" style="object-fit: cover; object-position: center top;">
        </div>
                            <p class="lead mb-4">"The innovative use of AI in recruitment exemplifies how technology can streamline processes and improve accuracy."</p>
                            <div class="text-center">
                                <h5 class="mb-1 fw-bold">Mr. Khusro Pervaiz</h5>
                                <span class="text-muted">Head of Campus</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-5 bg-light">
            <div class="container">
                <div class="row">
                    <!-- Address Section -->
                    <div class="col-md-4 mb-4 mb-md-0">
                        <img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="SZABIST" class="img-fluid mb-4" style="height: 50px;">
                        <p class="text-muted mb-2">Street 9, Plot 67, Sector H-8/4</p>
                        <p class="text-muted mb-2">Islamabad, Pakistan</p>
                        <p class="text-muted mb-2">careers@szabist-isb.edu.pk</p>
                        <p class="text-muted mb-0">+92-51-4863363-65</p>
                    </div>

                    <!-- Quick Links Section -->
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h5 class="mb-4">Quick Links</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none text-muted">How it Works</a>
                        </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none text-muted">About Us</a>
                        </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none text-muted">Contact</a>
                        </li>
                    </ul>
        </div>

                    <!-- Newsletter Section -->
                    <div class="col-md-4">
                        <h5 class="mb-4">Subscribe to Newsletter</h5>
                        <form action="{{ route('subscribe') }}" method="POST" class="subscribe-form">
                            @csrf
                            <div class="input-group">
                                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                                <button type="submit" class="subscribe-button">Subscribe</button>
                            </div>
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Chatbot -->
        <div class="chatbot-container">
            <div class="chatbot-toggle" id="chatbot-toggle">
                <i class="fas fa-comments"></i>
            </div>
            <div class="chatbot-box" id="chatbot-box">
                <div class="chatbot-header">
                    <h5 class="chatbot-title">SZABIST Recruitment Assistant</h5>
                    <button id="chatbot-close" aria-label="Close chatbot">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="chatbot-messages" id="chatbot-messages">
                    <!-- Messages will be added here by JavaScript -->
                </div>
                <div class="chatbot-input-container">
                    <input type="text" id="chatbot-input" class="chatbot-input" placeholder="Type your message...">
                    <button id="chatbot-send" class="chatbot-send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Loader
        $(window).on('load', function() {
            $('#loader-wrapper').fadeOut('slow');
        });

        document.addEventListener('DOMContentLoaded', function() {
            const chatbotToggle = document.getElementById('chatbot-toggle');
            const chatbotBox = document.getElementById('chatbot-box');
            const chatbotClose = document.getElementById('chatbot-close');
            const messageInput = document.getElementById('chatbot-input');
            const sendButton = document.getElementById('chatbot-send');
            const chatbotMessages = document.getElementById('chatbot-messages');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            async function sendMessage() {
                const message = messageInput.value.trim();
                if (!message) return;

                try {
                    // Clear input and disable
                    messageInput.value = '';
                    messageInput.disabled = true;
                    sendButton.disabled = true;
                    
                    // Show user message
                    appendMessage(message, 'user');

                    // Show typing indicator
                    const typingIndicator = document.createElement('div');
                    typingIndicator.className = 'typing-indicator';
                    typingIndicator.innerHTML = '<span></span><span></span><span></span>';
                    chatbotMessages.appendChild(typingIndicator);
                    typingIndicator.classList.add('active');

                    // Make API call
                    const response = await fetch('/chatbot', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ message: message })
                    });

                    // Remove typing indicator
                    typingIndicator.remove();

                    const data = await response.json();
                    
                    if (!response.ok) {
                        throw new Error(data.message || `Error: ${response.status}`);
                    }

                    appendMessage(data.message, 'bot');

                } catch (error) {
                    console.error('Error:', error);
                    appendMessage('Sorry, there was an error. Please try again.', 'bot');
                } finally {
                    // Re-enable input and button
                    messageInput.disabled = false;
                    sendButton.disabled = false;
                    messageInput.focus();
                }
            }

            // Event listeners
            sendButton.addEventListener('click', sendMessage);
            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });

            // Toggle chatbot
            chatbotToggle.addEventListener('click', function() {
                chatbotBox.classList.add('active');
                chatbotToggle.style.display = 'none';
            });

            // Close chatbot
            chatbotClose.addEventListener('click', function() {
                chatbotBox.classList.remove('active');
                chatbotToggle.style.display = 'flex';
            });

            function appendMessage(message, sender) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${sender}-message`;
                messageDiv.textContent = message;
                chatbotMessages.appendChild(messageDiv);
                chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
            }

            // Add initial welcome message
            appendMessage("Hello! I'm the SZABIST recruitment assistant. How can I help you with your job application today?", 'bot');
        });
    </script>
</body>
</html>