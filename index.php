<?php
include 'connectDb.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Tracking System | Real-Time Analytics & Vendor Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }
        
        .navbar {
            padding: 15px 0;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar.scrolled {
            padding: 10px 0;
            background-color: rgba(33, 37, 41, 0.95) !important;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 100px 0 150px;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgZmlsbD0idXJsKCNwYXR0ZXJuKSIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIvPjwvc3ZnPg==');
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .feature-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            background: white;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .stats-section {
            background-color: var(--light-color);
            padding: 80px 0;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .cta-section {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
            padding: 100px 0;
            clip-path: polygon(0 15%, 100% 0, 100% 100%, 0 100%);
            margin-top: -50px;
        }
        
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 50px 0 20px;
        }
        
        .social-icons a {
            color: white;
            font-size: 1.2rem;
            margin: 0 10px;
            transition: all 0.3s ease;
        }
        
        .social-icons a:hover {
            color: var(--accent-color);
            transform: translateY(-3px);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        
        .wave-shape {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }
        
        .wave-shape svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 100px;
        }
        
        .wave-shape .shape-fill {
            fill: #FFFFFF;
        }
        
        /* Custom View Products Button Style */
        .btn-view-products {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
            transition: all 0.3s ease;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        .btn-view-products:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-chart-line me-2"></i>SalesTrack
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about_us.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact_us.php">Contact</a></li>
                    <li class="nav-item ms-lg-3 my-2 my-lg-0"><a class="btn btn-outline-light" href="sign_up.php">Sign Up</a></li>
                    <li class="nav-item ms-lg-2"><a class="btn btn-primary" href="login.php">Log In</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 animate__animated animate__fadeInLeft">
                    <h1 class="display-4 fw-bold mb-4">Powerful Sales Tracking for Your Business</h1>
                    <p class="lead mb-4">Gain real-time insights, boost sales performance, and make data-driven decisions with our intuitive platform.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="sign_up.php" class="btn btn-light btn-lg px-4">Get Started Free</a>
                        <a href="#features" class="btn btn-outline-light btn-lg px-4">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6 animate__animated animate__fadeInRight">
                    <img src="./public/graphs.png" alt="" class="img-fluid floating" style="max-height: 400px;">
                </div>
            </div>
        </div>
        <div class="wave-shape">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number animate__animated" data-count="200">0</div>
                    <p>Happy Clients</p>
                </div>
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number animate__animated" data-count="98">0</div>
                    <p>Uptime %</p>
                </div>
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number animate__animated" data-count="45">0</div>
                    <p>Integrations</p>
                </div>
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number animate__animated" data-count="24">0</div>
                    <p>Support Hours</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Powerful Features to Grow Your Business</h2>
                <p class="lead text-muted">Everything you need to track, analyze and optimize your sales performance</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="feature-card p-4 h-100 animate__animated animate__fadeInUp" data-wow-delay="0.1s">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h4>Real-Time Analytics</h4>
                        <p class="text-muted">Get instant insights with dynamic dashboards and customizable reports that update in real-time as sales happen.</p>
                        <a href="#" class="btn btn-link ps-0">Learn more <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card p-4 h-100 animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                        <div class="feature-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <h4>Vendor Management</h4>
                        <p class="text-muted">Easily manage products, inventory, and vendor relationships from one centralized platform.</p>
                        <a href="#" class="btn btn-link ps-0">Learn more <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card p-4 h-100 animate__animated animate__fadeInUp" data-wow-delay="0.3s">
                        <div class="feature-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h4>Secure Payments</h4>
                        <p class="text-muted">Integrated payment processing with bank-grade security to protect your transactions and customer data.</p>
                        <a href="#" class="btn btn-link ps-0">Learn more <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card p-4 h-100 animate__animated animate__fadeInUp" data-wow-delay="0.4s">
                        <div class="feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h4>Smart Alerts</h4>
                        <p class="text-muted">Get notified about important sales trends, inventory levels, and performance milestones.</p>
                        <a href="#" class="btn btn-link ps-0">Learn more <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card p-4 h-100 animate__animated animate__fadeInUp" data-wow-delay="0.5s">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4>Mobile Ready</h4>
                        <p class="text-muted">Access your sales data anywhere with our fully responsive web app or native mobile applications.</p>
                        <a href="#" class="btn btn-link ps-0">Learn more <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card p-4 h-100 animate__animated animate__fadeInUp" data-wow-delay="0.6s">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>Team Collaboration</h4>
                        <p class="text-muted">Share reports, assign tasks, and communicate with your team without leaving the platform.</p>
                        <a href="#" class="btn btn-link ps-0">Learn more <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <!-- View All Products Button -->
                <a href="openmarket.php" class="btn btn-view-products animate__animated animate__fadeIn">
                    <i class="fas fa-box-open me-2"></i> View All Products
                </a>
                
                <h2 class="fw-bold">Trusted by Businesses Worldwide</h2>
                <p class="lead text-muted">Don't just take our word for it - hear from our customers</p>
            </div>
            
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-quote-left text-primary"></i>
                            </div>
                            <p class="mb-4">"Since implementing SalesTrack, we've seen a 30% increase in sales efficiency and much better visibility into our performance."</p>
                            <div class="d-flex align-items-center">
                                <img src="/public/vendor.jpg" class="rounded-circle me-3" width="50" height="50" alt="Odull">
                                <div>
                                    <h6 class="mb-0"></h6>
                                    <small class="text-muted">CEO, Retail Solutions</small>
                                </div>
                            </div>
                        </div>
                    </div>
    </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="display-5 fw-bold mb-4">Ready to Transform Your Sales Process?</h2>
                    <p class="lead mb-5">Join thousands of businesses already using SalesTrack to power their sales operations and drive growth.</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="sign_up.php" class="btn btn-light btn-lg px-4">Start Free Trial</a>
                        <a href="contact_us.php" class="btn btn-outline-light btn-lg px-4">Schedule Demo</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-4"><i class="fas fa-chart-line me-2"></i>SalesTrack</h5>
                    <p>Empowering businesses with real-time sales insights and powerful analytics to drive growth and maximize performance.</p>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="fw-bold mb-4">Product</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="features.php" class="text-light">Features</a></li>
                        <li class="mb-2"><a href="pricing.php" class="text-light">Pricing</a></li>
                        <li class="mb-2"><a href="#" class="text-light">Integrations</a></li>
                        <li class="mb-2"><a href="#" class="text-light">Updates</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="fw-bold mb-4">Company</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="about_us.php" class="text-light">About Us</a></li>
                        <li class="mb-2"><a href="#" class="text-light">Careers</a></li>
                        <li class="mb-2"><a href="#" class="text-light">Blog</a></li>
                        <li class="mb-2"><a href="#" class="text-light">Press</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="fw-bold mb-4">Resources</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-light">Help Center</a></li>
                        <li class="mb-2"><a href="#" class="text-light">Tutorials</a></li>
                        <li class="mb-2"><a href="#" class="text-light">API Docs</a></li>
                        <li class="mb-2"><a href="#" class="text-light">Community</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="fw-bold mb-4">Legal</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-light">Privacy</a></li>
                        <li class="mb-2"><a href="#" class="text-light">Terms</a></li>
                        <li class="mb-2"><a href="#" class="text-light">Security</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-secondary">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small mb-0">&copy; 2025 SalesTrack. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="small mb-0">
                        <a href="mailto:support@salestrack.com" class="text-light">support@salestrack.com</a> | 
                        <a href="tel:+1234567890" class="text-light">+1 (234) 567-890</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Animate elements when they come into view
        const animateOnScroll = function() {
            const elements = document.querySelectorAll('.animate__animated');
            
            elements.forEach(element => {
                const elementPosition = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementPosition < windowHeight - 100) {
                    const animationClass = element.classList.item(1);
                    element.classList.add(animationClass);
                }
            });
        };
        
        // Count up animation for stats
        const animateCounters = function() {
            const counters = document.querySelectorAll('.stat-number');
            const speed = 200;
            
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-count');
                const count = +counter.innerText;
                const increment = target / speed;
                
                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(animateCounters, 1);
                } else {
                    counter.innerText = target;
                }
            });
        };
        
        // Initialize animations when page loads
        window.addEventListener('load', function() {
            animateOnScroll();
            animateCounters();
        });
        
        // Run animations on scroll
        window.addEventListener('scroll', animateOnScroll);
    </script>
</body>
</html>