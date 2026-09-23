<?php
include 'connectDb.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | SalesTrack - Powerful Sales Analytics</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        
        .contact-hero {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 120px 0 80px;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
            position: relative;
            overflow: hidden;
        }
        
        .contact-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgZmlsbD0idXJsKCNwYXR0ZXJuKSIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIvPjwvc3ZnPg==');
        }
        
        .contact-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            background: white;
            padding: 30px;
            height: 100%;
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .contact-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .contact-btn {
            transition: all 0.3s ease;
            padding: 12px 20px;
            font-weight: 500;
            border-radius: 8px;
        }
        
        .contact-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        .map-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
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
                    <li class="nav-item"><a class="nav-link" href="about_us.php">About</a></li>
                    <li class="nav-item"><a class="nav-link active" href="contact_us.php">Contact</a></li>
                    <li class="nav-item ms-lg-3 my-2 my-lg-0"><a class="btn btn-outline-light" href="sign_up.php">Sign Up</a></li>
                    <li class="nav-item ms-lg-2"><a class="btn btn-primary" href="login.php">Log In</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center animate__animated animate__fadeIn">
                    <h1 class="display-4 fw-bold mb-4">We'd Love to Hear From You</h1>
                    <p class="lead mb-4">Have questions about our sales tracking platform? Get in touch with our team today.</p>
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

    <!-- Contact Methods Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5 animate__animated animate__fadeIn">
                <h2 class="fw-bold">Connect With Us</h2>
                <p class="lead text-muted">Choose your preferred method of contact</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4 animate__animated animate__fadeInUp" data-wow-delay="0.1s">
                    <div class="contact-card text-center">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h4>Email Us</h4>
                        <p class="mb-4">For general inquiries and support</p>
                        <a href="mailto:support@salestrack.com" class="btn btn-primary contact-btn w-100">
                            support@salestrack.com
                        </a>
                    </div>
                </div>
                <div class="col-md-4 animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                    <div class="contact-card text-center">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h4>Call Us</h4>
                        <p class="mb-4">Speak directly with our support team</p>
                        <button onclick="showContact('Phone', '+254 769 615 268')" class="btn btn-success contact-btn w-100">
                            +254 769 615 268
                        </button>
                    </div>
                </div>
                <div class="col-md-4 animate__animated animate__fadeInUp" data-wow-delay="0.3s">
                    <div class="contact-card text-center">
                        <div class="contact-icon">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                        <h4>Live Chat</h4>
                        <p class="mb-4">Get instant answers to your questions</p>
                        <button onclick="showContact('Live Chat', 'Available Mon-Fri, 9AM-5PM')" class="btn btn-info contact-btn w-100">
                            Start Live Chat
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Additional Contact Buttons -->
            <div class="row mt-4 g-3">
                <div class="col-md-3 col-6 animate__animated animate__fadeInUp" data-wow-delay="0.4s">
                    <button class="btn btn-success contact-btn w-100" onclick="showContact('WhatsApp', '+254 769 615 268')">
                        <i class="fab fa-whatsapp me-2"></i> WhatsApp
                    </button>
                </div>
                <div class="col-md-3 col-6 animate__animated animate__fadeInUp" data-wow-delay="0.5s">
                    <button class="btn btn-primary contact-btn w-100" onclick="showContact('Facebook', 'facebook.com/salestrack')">
                        <i class="fab fa-facebook-f me-2"></i> Facebook
                    </button>
                </div>
                <div class="col-md-3 col-6 animate__animated animate__fadeInUp" data-wow-delay="0.6s">
                    <button class="btn btn-info contact-btn w-100" onclick="showContact('Twitter', 'twitter.com/salestrack')">
                        <i class="fab fa-twitter me-2"></i> Twitter
                    </button>
                </div>
                <div class="col-md-3 col-6 animate__animated animate__fadeInUp" data-wow-delay="0.7s">
                    <button class="btn btn-dark contact-btn w-100" onclick="showContact('LinkedIn', 'linkedin.com/company/salestrack')">
                        <i class="fab fa-linkedin-in me-2"></i> LinkedIn
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 animate__animated animate__fadeInLeft">
                    <h2 class="fw-bold mb-4">Send Us a Message</h2>
                    <p class="mb-4">Have specific questions or need detailed information? Fill out the form and our team will get back to you within 24 hours.</p>
                    <div class="d-flex mb-4">
                        <div class="me-4">
                            <h5 class="fw-bold">Nairobi Office</h5>
                            <p>SalesTrack Headquarters<br>Westlands, Nairobi<br>Kenya</p>
                        </div>
                        <div>
                            <h5 class="fw-bold">Working Hours</h5>
                            <p>Monday - Friday: 8:00 - 17:00<br>Saturday: 9:00 - 14:00<br>Sunday: Closed</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 animate__animated animate__fadeInRight">
                    <div class="contact-card">
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter your name">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter your email">
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" placeholder="What's this about?">
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" rows="4" placeholder="Your message here..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5 animate__animated animate__fadeIn">
                <h2 class="fw-bold">Our Location</h2>
                <p class="lead text-muted">Visit our headquarters in Nairobi</p>
            </div>
            
            <div class="map-container animate__animated animate__fadeIn">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.808567257089!2d36.82115931475391!3d-1.286899835979919!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f10d4f2e5a7a3%3A0x4f2e5a7a3f2e5a7a!2sNairobi%2C%20Kenya!5e0!3m2!1sen!2ske!4v1622549400000!5m2!1sen!2ske" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-4 animate__animated animate__fadeIn">Ready to Get Started?</h2>
            <p class="lead mb-4 animate__animated animate__fadeIn">Join thousands of businesses using SalesTrack to power their sales operations.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3 animate__animated animate__fadeInUp">
                <a href="sign_up.php" class="btn btn-light btn-lg px-4">Start Free Trial</a>
                <a href="pricing.php" class="btn btn-outline-light btn-lg px-4">View Pricing</a>
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
                    <p class="small mb-0">&copy; <?php echo date('Y'); ?> SalesTrack. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="small mb-0">
                        <a href="mailto:support@salestrack.com" class="text-light">support@salestrack.com</a> | 
                        <a href="tel:+254769615268" class="text-light">+254 769 615 268</a>
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
        
        // Contact modal function
        function showContact(platform, contact) {
            Swal.fire({
                icon: 'info',
                title: `${platform} Contact`,
                html: `<p>Reach us at: <strong>${contact}</strong></p>
                      <p class="mt-3">We typically respond within 1 business day.</p>`,
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'animate__animated animate__zoomIn'
                }
            });
        }
        
        // Initialize animations when page loads
        window.addEventListener('load', function() {
            animateOnScroll();
        });
        
        // Run animations on scroll
        window.addEventListener('scroll', animateOnScroll);
    </script>
</body>
</html>