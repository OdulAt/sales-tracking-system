<?php
include 'connectDb.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | SalesTrack - Powerful Sales Analytics</title>
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
        
        .hero-about {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 120px 0 100px;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero-about::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgZmlsbD0idXJsKCNwYXR0ZXJuKSIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIvPjwvc3ZnPg==');
        }
        
        .about-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            background: white;
            padding: 30px;
        }
        
        .about-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .mission-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .team-member {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .team-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .team-img:hover {
            transform: scale(1.05);
        }
        
        .values-list {
            list-style: none;
            padding-left: 0;
        }
        
        .values-list li {
            padding: 15px;
            margin-bottom: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .values-list li:hover {
            transform: translateX(5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .values-list li i {
            color: var(--primary-color);
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .timeline {
            position: relative;
            padding-left: 50px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--primary-color);
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -43px;
            top: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--primary-color);
            border: 4px solid white;
        }
        
        .timeline-date {
            font-weight: 600;
            color: var(--primary-color);
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="about_us.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact_us.php">Contact</a></li>
                    <li class="nav-item ms-lg-3 my-2 my-lg-0"><a class="btn btn-outline-light" href="sign_up.php">Sign Up</a></li>
                    <li class="nav-item ms-lg-2"><a class="btn btn-primary" href="login.php">Log In</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center animate__animated animate__fadeIn">
                    <h1 class="display-4 fw-bold mb-4">Our Story & Mission</h1>
                    <p class="lead mb-4">Empowering businesses with data-driven insights to transform their sales performance and drive sustainable growth.</p>
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

    <!-- About Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4 animate__animated animate__fadeInLeft">
                    <div class="about-card h-100">
                        <div class="mission-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3 class="mb-4">Who We Are</h3>
                        <p>SalesTrack was founded in <b><i style="color:darkblue">2024</i></b> with a simple vision: to revolutionize how businesses track and analyze their sales performance. What started as a small team of data enthusiasts has grown into a leading sales analytics platform trusted by thousands of businesses worldwide.</p>
                        <p>Our team combines decades of experience in business intelligence, software development, and sales optimization to deliver a platform that's both powerful and easy to use.</p>
                    </div>
                </div>
                <div class="col-lg-6 mb-4 animate__animated animate__fadeInRight">
                    <div class="about-card h-100">
                        <div class="mission-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="mb-4">Our Mission</h3>
                        <p>We exist to democratize sales analytics, making advanced tracking and insights accessible to businesses of all sizes. We believe that data-driven decisions should be available to everyone, not just enterprises with big budgets.</p>
                        <p>Our platform is designed to help you understand your sales patterns, predict trends, and make informed decisions that drive real growth.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5 animate__animated animate__fadeIn">
                <h2 class="fw-bold">Our Core Values</h2>
                <p class="lead text-muted">The principles that guide everything we do</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <ul class="values-list animate__animated animate__fadeInUp">
                        <li>
                            <i class="fas fa-chart-line"></i>
                            <strong>Data-Driven Decisions:</strong> We believe in the power of analytics to transform businesses and drive smarter decisions.
                        </li>
                        <li>
                            <i class="fas fa-users"></i>
                            <strong>Customer Success:</strong> Your growth is our success. We're committed to helping you achieve your business goals.
                        </li>
                        <li>
                            <i class="fas fa-lightbulb"></i>
                            <strong>Innovation:</strong> We constantly evolve our platform to incorporate the latest technologies and methodologies.
                        </li>
                        <li>
                            <i class="fas fa-shield-alt"></i>
                            <strong>Integrity:</strong> We maintain the highest standards of security and ethical business practices.
                        </li>
                        <li>
                            <i class="fas fa-handshake"></i>
                            <strong>Partnership:</strong> We work collaboratively with our clients to understand their unique needs and challenges.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <!--section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5 animate__animated animate__fadeIn">
                <h2 class="fw-bold">Our Journey</h2>
                <p class="lead text-muted">Key milestones in our company history</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="timeline animate__animated animate__fadeIn">
                        <div class="timeline-item">
                            <div class="timeline-date">2020</div>
                            <h4>Company Founded</h4>
                            <p>SalesTrack was launched with a mission to simplify sales analytics for small businesses.</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-date">2021</div>
                            <h4>First Major Release</h4>
                            <p>Launched version 1.0 with real-time analytics and basic reporting features.</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-date">2022</div>
                            <h4>Mobile Apps Released</h4>
                            <p>Expanded our platform with iOS and Android applications for on-the-go access.</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-date">2023</div>
                            <h4>AI Predictions Added</h4>
                            <p>Integrated machine learning to provide sales forecasts and inventory recommendations.</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-date">2024</div>
                            <h4>10,000+ Customers</h4>
                            <p>Celebrated serving over 10,000 businesses across 30+ countries.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section-->

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-4 animate__animated animate__fadeIn">Ready to Transform Your Sales Process?</h2>
            <p class="lead mb-4 animate__animated animate__fadeIn">Join thousands of businesses using SalesTrack to power their sales operations.</p>
            <a href="sign_up.php" class="btn btn-light btn-lg px-4 animate__animated animate__fadeInUp">Start Your Free Trial</a>
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
                    <p class="small mb-0">&copy; <?php echo date('Y'); ?> SalesTrack. All rights reserved.</p>
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
        
        // Initialize animations when page loads
        window.addEventListener('load', function() {
            animateOnScroll();
        });
        
        // Run animations on scroll
        window.addEventListener('scroll', animateOnScroll);
    </script>
</body>
</html>