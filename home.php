<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sehat Guardian</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        /* Hero Section */
        .hero {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slideshow-container {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .slide.active {
            opacity: 1;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 96, 100, 0.8), rgba(0, 131, 143, 0.8));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 0 20px;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: 1.5rem;
            max-width: 600px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        /* Portal Section */
        .portal-section {
            padding: 80px 20px;
            background: linear-gradient(135deg, #f8fdfd, #e0f2f1);
            text-align: center;
        }

        .portal-section h2 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #006064;
        }

        .portal-section p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 60px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .portal-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            padding: 0 20px;
        }

        .portal-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 96, 100, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .portal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #006064, #00838f);
        }

        .portal-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 96, 100, 0.2);
        }

        .portal-card i {
            font-size: 4rem;
            color: #006064;
            margin-bottom: 20px;
        }

        .portal-card h3 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #006064;
        }

        .portal-card p {
            color: #666;
            margin-bottom: 30px;
            font-size: 1rem;
        }

        .portal-btn {
            display: inline-block;
            background: linear-gradient(135deg, #006064, #00838f);
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 96, 100, 0.3);
        }

        .portal-btn:hover {
            background: linear-gradient(135deg, #00838f, #006064);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 96, 100, 0.4);
        }

        /* Navigation dots for slideshow */
        .slide-nav {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .slide-nav button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .slide-nav button.active {
            background: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.2rem;
            }
            
            .portal-section h2 {
                font-size: 2.5rem;
            }
            
            .portal-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .portal-section h2 {
                font-size: 2rem;
            }
            
            .portal-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Slideshow Section -->
    <section class="hero">
        <div class="slideshow-container">
            <div class="slide active">
                <img src="angel.jpg" alt="Healthcare Angel">
            </div>
            <div class="slide">
                <img src="old people.jpg" alt="Elder Care">
            </div>
            <div class="slide">
                <img src="hands.jpg" alt="Caring Hands">
            </div>
        </div>
        
        <div class="hero-overlay">
            <h1>Stay Connected</h1>
            <p>Bringing smiles and joy to our elders through care and connection.</p>
        </div>
        
        <div class="slide-nav">
            <button class="active" onclick="currentSlide(1)"></button>
            <button onclick="currentSlide(2)"></button>
            <button onclick="currentSlide(3)"></button>
        </div>
    </section>

    <!-- Portal Selection Section -->
    <section class="portal-section">
        <h2>Choose Your Portal</h2>
        <p>Select your role to access your personalized dashboard and start your healthcare journey with us.</p>
        
        <div class="portal-grid">
            <!-- Patient Portal Card -->
            <div class="portal-card">
                <i class="ri-user-heart-line"></i>
                <h3>Patient</h3>
                <p>Access your medical records, book appointments, and manage your health information securely.</p>
                <a href="users/patient_portal.php" class="portal-btn">Patient Login / Register</a>
            </div>
            
            <!-- Doctor Portal Card -->
            <div class="portal-card">
                <i class="ri-stethoscope-line"></i>
                <h3>Doctor</h3>
                <p>Manage patient appointments, access medical records, and provide quality healthcare services.</p>
                <a href="doctor/doctor_login.php" class="portal-btn">Login as Doctor</a>
            </div>
            
            <!-- Admin Portal Card -->
            <div class="portal-card">
                <i class="ri-admin-line"></i>
                <h3>Admin</h3>
                <p>Oversee system operations, manage users, and maintain the healthcare management platform.</p>
                <a href="admin/login.php" class="portal-btn">Login as Admin</a>
            </div>
        </div>
    </section>

    <script>
        // Slideshow functionality
        let slideIndex = 1;
        let slideInterval;

        function showSlides(n) {
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.slide-nav button');
            
            if (n > slides.length) { slideIndex = 1 }
            if (n < 1) { slideIndex = slides.length }
            
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            slides[slideIndex - 1].classList.add('active');
            dots[slideIndex - 1].classList.add('active');
        }

        function currentSlide(n) {
            clearInterval(slideInterval);
            slideIndex = n;
            showSlides(slideIndex);
            startSlideshow();
        }

        function nextSlide() {
            slideIndex++;
            showSlides(slideIndex);
        }

        function startSlideshow() {
            slideInterval = setInterval(nextSlide, 4000); // Change slide every 4 seconds
        }

        // Initialize slideshow
        document.addEventListener('DOMContentLoaded', function() {
            showSlides(slideIndex);
            startSlideshow();
        });

        // Add smooth scrolling for any anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>