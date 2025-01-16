<footer class="theme-footer-one">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="footer-content">
                    <div class="footer-logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="Logo" class="footer-logo">
                        </a>
                    </div>
                    <p>&copy; {{ date('Y') }} HireSmart. All rights reserved.</p>
                    <div class="footer-links">
                        <a href="{{ url('/') }}">Home</a>
                        <a href="{{ route('jobs.listings') }}">Jobs</a>
                        <a href="#">About Us</a>
                        <a href="#">Contact</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.theme-footer-one {
    background: #2c2c2c;
    padding: 50px 0;
    color: white;
}

.footer-content {
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
    margin: 0 15px;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-links a:hover {
    color: #006dd7;
}
</style>