<footer class="theme-footer-one">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="Logo">
                    </div>
                    <p>Street 9, Plot 67, Sector H-8/4<br>Islamabad, Pakistan</p>
                    <p>careers@szabist-isb.edu.pk<br>+92-51-4863363-65</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <div class="footer-links">
                        <a href="{{ url('/') }}">Home</a>
                        <a href="{{ route('jobs.listings') }}">Jobs</a>
                        <a href="#">About Us</a>
                        <a href="#">Contact</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="footer-section">
                    <h4>Subscribe to Job Alerts</h4>
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
                        @if($errors->has('email'))
                            <div class="alert alert-danger">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} HireSmart. All rights reserved.</p>
        </div>
    </div>
</footer>

<style>
.theme-footer-one {
    background: #2c2c2c;
    padding: 50px 0;
    color: white;
}

.footer-section {
    margin-bottom: 30px;
}

.footer-logo {
    margin-bottom: 20px;
}

.footer-logo img {
    max-width: 150px;
}

.footer-links {
    margin-top: 20px;
}

.footer-links a {
    color: white;
    display: block;
    margin-bottom: 10px;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-links a:hover {
    color: #006dd7;
}

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

.footer-bottom {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    text-align: center;
}
</style>

<script>
    // Auto-hide alerts after 3 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 3000);
        });
    });
</script>

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