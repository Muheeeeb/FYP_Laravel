<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SZABIST Hiring Portal</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> <!-- Laravel asset helper for CSS -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <header>
        <nav class="container">
            <div class="logo-container">
                <img src="{{ asset('images/zablogo.jpeg') }}" alt="SZABIST Logo" id="szabist-logo" class="logo-image">
                <!-- Use asset helper -->
                <span class="logo-text">SZABIST</span>
            </div>
            <ul>
                <li><a href="#" onclick="showHomePage(); return false;">Home</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#contact">Contact</a></li>
                @guest
                <li><a href="{{ route('login') }}" class="btn">Login</a></li>
                @else
                <li><a href="{{ route('logout') }}" class="btn"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                @endguest
                <li><button id="themeToggle">🌓</button></li>
            </ul>

        </nav>
    </header>

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Welcome to SZABIST Hiring Portal</h1>
                <p>Connecting talented professionals with exciting career opportunities at SZABIST.</p>
                <a href="#" class="btn" onclick="showJobOpenings(); return false;">Get Started</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="{{ asset('images/szabist.jpeg') }}" alt="SZABIST Campus">
        </div>
    </section>


    <section id="jobOpenings" style="display: none;">
        <div class="container">
            <h2 class="section-title">Current Job Openings</h2>
            <div id="jobListings"></div>
        </div>
    </section>


    <!-- Application Modal -->
    <div id="applicationModal" class="modal">
        <div class="modal-content">
           <span class="close" onclick="closeApplicationModal()">&times;</span>
            <h2>Apply for <span id="jobTitle"></span></h2>
            <form id="applicationForm" method="POST" action="{{ route('apply') }}" enctype="multipart/form-data">
                @csrf
                <input type="text" id="applicantName" name="name" placeholder="Full Name" required>
                <input type="email" id="applicantEmail" name="email" placeholder="Email" required>
                <input type="tel" id="applicantContact" name="contact" placeholder="Contact Number" required>
                <input type="file" id="applicantCV" name="cv" accept=".pdf,.doc,.docx" required>
                <button type="submit" class="btn">Submit Application</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>