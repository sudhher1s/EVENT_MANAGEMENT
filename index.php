<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Event Management System</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        header {
            background: linear-gradient(135deg, #6e48aa 0%, #9d50bb 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 28px;
            font-weight: 700;
        }
        
        .logo span {
            color: #ffd700;
        }
        
        .auth-buttons a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            padding: 8px 20px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .auth-buttons .login {
            border: 1px solid white;
        }
        
        .auth-buttons .register {
            background-color: #ffd700;
            color: #333;
            font-weight: 600;
        }
        
        .auth-buttons a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* Hero Section */
        .hero {
            background: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center/cover;
            height: 500px;
            display: flex;
            align-items: center;
            position: relative;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            color: white;
            max-width: 600px;
        }
        
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .hero p {
            font-size: 18px;
            margin-bottom: 30px;
        }
        
        /* Events Section */
        .events-section {
            padding: 80px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 36px;
            color: #6e48aa;
            margin-bottom: 15px;
        }
        
        .section-title p {
            color: #666;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }
        
        .event-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .event-card:hover {
            transform: translateY(-10px);
        }
        
        .event-image {
            height: 200px;
            overflow: hidden;
        }
        
        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .event-card:hover .event-image img {
            transform: scale(1.1);
        }
        
        .event-details {
            padding: 25px;
        }
        
        .event-details h3 {
            font-size: 22px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .event-details p {
            color: #666;
            margin-bottom: 15px;
        }
        
        .event-meta {
            display: flex;
            justify-content: space-between;
            color: #6e48aa;
            font-weight: 600;
        }
        
        /* Footer */
        footer {
            background-color: #2c3e50;
            color: white;
            padding: 40px 0;
            text-align: center;
        }
        
        .footer-content p {
            margin-bottom: 20px;
        }
        
        .social-links a {
            color: white;
            margin: 0 10px;
            font-size: 20px;
            transition: color 0.3s ease;
        }
        
        .social-links a:hover {
            color: #ffd700;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-content">
            <div class="logo">Event<span>Hub</span></div>
            <div class="auth-buttons">
                <a href="login.php" class="login">Login</a>
                <a href="register.php" class="register">Register</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content">
            <h1>Join Exciting College Events</h1>
            <p>Participate in hackathons, coding competitions, workshops and more. Connect with like-minded students and showcase your talents.</p>
        </div>
    </section>

    <!-- Events Section -->
    <section class="events-section">
        <div class="container">
            <div class="section-title">
                <h2>Upcoming Events</h2>
                <p>Check out our exciting lineup of events for this semester</p>
            </div>
            
            <div class="events-grid">
                <!-- Event Card 1 -->
                <div class="event-card">
                    <div class="event-image">
                        <img src="https://images.unsplash.com/photo-1551033406-611cf9a28f67?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Hackathon">
                    </div>
                    <div class="event-details">
                        <h3>Annual Hackathon</h3>
                        <p>24-hour coding competition where teams build innovative solutions to real-world problems.</p>
                        <div class="event-meta">
                            <span><i class="far fa-calendar-alt"></i> Oct 15-16</span>
                            <span><i class="far fa-money-bill-alt"></i> ₹500</span>
                        </div>
                    </div>
                </div>
                
                <!-- Event Card 2 -->
                <div class="event-card">
                    <div class="event-image">
                        <img src="https://images.unsplash.com/photo-1517430816045-df4b7de11d1d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Code Refactor">
                    </div>
                    <div class="event-details">
                        <h3>Code Refactor Challenge</h3>
                        <p>Test your skills at optimizing and refactoring messy code to make it clean and efficient.</p>
                        <div class="event-meta">
                            <span><i class="far fa-calendar-alt"></i> Nov 5</span>
                            <span><i class="far fa-money-bill-alt"></i> ₹300</span>
                        </div>
                    </div>
                </div>
                
                <!-- Event Card 3 -->
                <div class="event-card">
                    <div class="event-image">
                        <img src="https://images.unsplash.com/photo-1542626991-cbc4e32524cc?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Reels Studio">
                    </div>
                    <div class="event-details">
                        <h3>Reels Studio Workshop</h3>
                        <p>Learn to create engaging short videos and social media content from industry experts.</p>
                        <div class="event-meta">
                            <span><i class="far fa-calendar-alt"></i> Dec 12-13</span>
                            <span><i class="far fa-money-bill-alt"></i> ₹750</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container footer-content">
            <p>&copy; 2023 College Event Management System. All rights reserved.</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </footer>
</body>
</html>