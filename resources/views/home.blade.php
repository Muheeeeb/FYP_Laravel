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
                            @error('email')
                                <div class="alert alert-danger">
                                    {{ $message }}
                        </div>
                            @enderror
                        </form>
                    </div>
                </div>
            </div>
        </footer>

                <!-- Copyright -->
                <div class="border-top mt-4 pt-4 text-center">
                    <p class="text-muted mb-0 small">© 2023 HireSmart. All rights reserved.</p>
        </div>

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
                    <!-- Welcome message will be added by JavaScript -->
                </div>
                <div class="chatbot-input-container">
                    <input type="text" id="chatbot-input" class="chatbot-input" placeholder="Type your message...">
                    <button id="chatbot-send" class="chatbot-send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>

        <style>
            .chatbot-container {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 10000;
            }
            
            #chatbot-toggle {
                width: 60px;
                height: 60px;
                background-color: #4a6cf7;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                transition: all 0.3s ease;
                z-index: 10000;
                position: relative;
            }
            
            #chatbot-toggle:hover {
                transform: scale(1.1);
            }
            
            .chatbot-box {
                position: fixed;
                right: 20px;
                bottom: 90px !important;
                width: 320px;
                height: 450px;
                background-color: white;
                border-radius: 15px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
                display: flex;
                flex-direction: column;
                transform: translateY(20px);
                opacity: 0;
                transition: all 0.3s ease;
                z-index: 10001;
                max-height: 80vh; /* Ensure it doesn't exceed 80% of viewport height */
                visibility: hidden;
            }
            
            .chatbot-box.active {
                transform: translateY(0);
                opacity: 1;
                visibility: visible;
                bottom: 90px !important;
            }
            
            .chatbot-header {
                display: flex;
                align-items: center;
                padding: 15px;
                background-color: #4a6cf7;
                color: white;
                border-top-left-radius: 15px;
                border-top-right-radius: 15px;
            }
            
            .chatbot-title {
                flex-grow: 1;
                font-weight: 600;
            }
            
            #chatbot-close {
                background: none;
                border: none;
                color: white;
                font-size: 20px;
                cursor: pointer;
            }
            
            .chatbot-messages {
                flex-grow: 1;
                padding: 15px;
                overflow-y: auto;
                display: flex;
                flex-direction: column;
            }
            
            .message {
                max-width: 80%;
                padding: 10px 15px;
                margin-bottom: 10px;
                border-radius: 15px;
                word-wrap: break-word;
            }
            
            .bot-message {
                background-color: #f1f1f1;
                align-self: flex-start;
                border-bottom-left-radius: 5px;
            }
            
            .user-message {
                background-color: #4a6cf7;
                color: white;
                align-self: flex-end;
                border-bottom-right-radius: 5px;
            }
            
            .chatbot-input-container {
                display: flex;
                align-items: center;
                padding: 10px 15px;
                border-top: 1px solid #e0e0e0;
            }
            
            #chatbot-input {
                flex-grow: 1;
                padding: 10px;
                border: 1px solid #e0e0e0;
                border-radius: 20px;
                outline: none;
            }
            
            #chatbot-send {
                background-color: #4a6cf7;
                color: white;
                border: none;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                margin-left: 10px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }
            
            #chatbot-send:hover {
                background-color: #3a57e8;
            }
            
            .typing-indicator {
                display: flex;
                align-items: center;
                background-color: #f1f1f1;
                padding: 10px 15px;
                border-radius: 15px;
                border-bottom-left-radius: 5px;
                align-self: flex-start;
                margin-bottom: 10px;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .typing-indicator.active {
                opacity: 1;
            }
            
            .typing-indicator span {
                width: 8px;
                height: 8px;
                background-color: #666;
                border-radius: 50%;
                margin: 0 2px;
                display: inline-block;
                animation: typing 1s infinite ease-in-out;
            }
            
            .typing-indicator span:nth-child(2) {
                animation-delay: 0.2s;
            }
            
            .typing-indicator span:nth-child(3) {
                animation-delay: 0.4s;
            }
            
            @keyframes typing {
                0% { transform: translateY(0); }
                50% { transform: translateY(-5px); }
                100% { transform: translateY(0); }
            }
            
            @media (max-width: 576px) {
                .chatbot-box {
                    width: 90%;
                    right: 5%;
                    max-height: 70vh;
                }
            }
        </style>

        <script>
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
                        // Disable input and button
                        messageInput.value = '';
                        messageInput.disabled = true;
                        sendButton.disabled = true;

                        // Show user message
                        appendMessage(message, 'user');

                        // Show typing indicator
                        const typingIndicator = document.createElement('div');
                        typingIndicator.className = 'message bot-message typing';
                        typingIndicator.textContent = 'Typing...';
                        chatbotMessages.appendChild(typingIndicator);

                        // Make API call
                        const response = await fetch('/chat', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ message: message })
                        });

                        // Remove typing indicator
                        const typingElements = document.getElementsByClassName('typing');
                        while (typingElements.length > 0) {
                            typingElements[0].remove();
                        }

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();
                        if (data.error) {
                            throw new Error(data.message || 'Unknown error occurred');
                        }

                        appendMessage(data.message, 'bot');

                    } catch (error) {
                        console.error('Error:', error);
                        appendMessage('Sorry, there was an error processing your request. Please try again.', 'bot');

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
    </div>

    <!-- Add animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" defer>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('loader-wrapper');
            const mainContent = document.querySelector('.main-page-wrapper');
            
            // Show loader and ensure main content is visible but transparent
            if (loader && mainContent) {
                loader.style.display = 'flex';
                mainContent.style.opacity = '0';
                mainContent.style.display = 'block';
            }
        });

        window.addEventListener('load', function() {
            const loader = document.getElementById('loader-wrapper');
            const mainContent = document.querySelector('.main-page-wrapper');
            
            if (loader && mainContent) {
                // Hide loader and show content
                setTimeout(function() {
                    loader.style.opacity = '0';
                    mainContent.style.opacity = '1';
                    
                    setTimeout(function() {
                        loader.style.display = 'none';
                    }, 300);
                }, 500);
            }
        });
    </script>
    
    <!-- Defer non-critical scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="{{ asset('js/jquery.appear.js') }}"></script>
    <script src="{{ asset('js/jquery.countTo.js') }}"></script>
    <script src="{{ asset('js/wow.min.js') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const subscribeForm = document.querySelector('.subscribe-form');
        
        if (subscribeForm) {
            const createAlert = (message, type) => {
                const alert = document.createElement('div');
                alert.className = `alert alert-${type}`;
                alert.textContent = message;
                return alert;
            };

            const removeExistingAlerts = () => {
                subscribeForm.querySelectorAll('.alert').forEach(alert => alert.remove());
            };

            subscribeForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const emailInput = this.querySelector('input[name="email"]');
                const submitButton = this.querySelector('button[type="submit"]');
                const csrfToken = this.querySelector('input[name="_token"]').value;
                
                // Remove any existing alerts
                removeExistingAlerts();
                
                // Show loading state
                submitButton.disabled = true;
                submitButton.textContent = 'Subscribing...';
                
                // Show loading message
                const loadingAlert = createAlert('Processing your subscription...', 'info');
                this.appendChild(loadingAlert);
                
                try {
                    const formData = new FormData();
                    formData.append('email', emailInput.value);
                    formData.append('_token', csrfToken);
                    
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    // Remove loading message
                    loadingAlert.remove();
                    
                    if (response.redirected) {
                        window.location.href = response.url;
                        return;
                    }
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        // Show success message
                        const successAlert = createAlert(data.message || 'Successfully subscribed!', 'success');
                        this.appendChild(successAlert);
                        
                        // Clear the input
                        emailInput.value = '';
                    } else {
                        throw new Error(data.message || 'Subscription failed');
                    }
                } catch (error) {
                    // Show error message
                    const errorAlert = createAlert(error.message || 'Something went wrong. Please try again.', 'danger');
                    this.appendChild(errorAlert);
                } finally {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Subscribe';
                    
                    // Auto-hide alerts after 3 seconds
                    const alerts = this.querySelectorAll('.alert');
                    alerts.forEach(function(alert) {
                        setTimeout(function() {
                            alert.style.transition = 'opacity 0.5s ease';
                            alert.style.opacity = '0';
                            setTimeout(function() {
                                alert.remove();
                            }, 500);
                        }, 3000);
                    });
                }
            });
        }
    });
    </script>
</body>
</html>