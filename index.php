<?php 
// index.php
// Description: Main page for SkillSprit - a freelancing platform connecting freelancers with businesses.

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include server configuration
if (!@include('server.php')) {
    die('Error loading required files.');
}

// Get the username from session
$username = isset($_SESSION["Username"]) ? $_SESSION["Username"] : "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SkillSprit - Your trusted freelancing platform connecting talented freelancers with businesses worldwide">
    <title>SkillSprit</title>
    <link rel="icon" href="./icon/shotcut_logo.png" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <!-- Navbar Section -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php"><img src="logo/logo.png" alt="Logo" width="50"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-user"></i> Home</a></li>
                <?php 
                // User-specific navigation
                if(isset($_SESSION["Username"])){
                    if ($_SESSION["Usertype"]==1) {
                        echo '<li class="nav-item"><a class="nav-link" href="freelancerProfile.php"><span class="glyphicon glyphicon-user"></span> ';
                        echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
                        echo '</a></li>';
                    } else {
                        echo '<li class="nav-item"><a class="nav-link" href="employerProfile.php"><span class="glyphicon glyphicon-user"></span> ';
                        echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
                        echo '</a></li>';
                    }
                } else {
                    echo '<li class="nav-item"><a class="nav-link" href="registration.php"><i class="fas fa-store"></i> Become a Seller</a></li>';
                }
                ?>
                <li class="nav-item"><a class="nav-link" href="#about"><i class="fas fa-info-circle"></i> About</a></li>
                <li class="nav-item"><a class="nav-link" href="#faq"><i class="fas fa-question-circle"></i> Help</a></li>
            </ul>
        </div>
    </nav>

    <!-- Slider Section -->
    <div>
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
            </ol>
            <!-- Slides -->
            <div class="carousel-inner">
                <div class="carousel-item active"><img src="slider/2.jpg" alt="Image 1" class="d-block w-100"></div>
                <div class="carousel-item"><img src="slider/3.jpg" alt="Image 2" class="d-block w-100"></div>
                <div class="carousel-item"><img src="slider/4.jpeg" alt="Image 3" class="d-block w-100"></div>
            </div>
            <!-- Controls -->
            <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <br><br>

    <!-- Feature Section -->
    <section class="feature-section py-5">
        <div class="container">
            <div class="row">
                <!-- Freelancer Feature -->
                <div class="col-lg-6 mb-4">
                    <div class="feature-card freelancer-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h2>Want to start earning money?</h2>
                        <p class="feature-text">Join our platform as a freelancer and start your journey towards financial freedom.</p>
                        <button onclick="window.location.href = 'loginReg.php'" class="btn btn-primary feature-btn">
                            Start Earning <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                <!-- Client Feature -->
                <div class="col-lg-6 mb-4">
                    <div class="feature-card client-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h2>Need work done?</h2>
                        <p class="feature-text">Connect with our talented freelancers to bring your projects to life.</p>
                        <button onclick="window.location.href = 'loginReg.php'" class="btn btn-primary feature-btn">
                            Hire Now <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Story Section -->
    <section class="mt-5 bg-white nobel py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 nobel_img">
                    <img src="slider/1.jpg" alt="Top Freelancer" class="img-fluid">
                </div>
                <div class="col-md-6 details">
                    <h1>Success Story: Sarah Chen</h1>
                    <p>From a part-time graphic designer to a six-figure freelancer, Sarah's journey inspires thousands. She started on SkillSprit in 2021 and has completed over 500 projects for clients worldwide.</p>
                    <p>Her dedication to quality and client satisfaction has earned her our "Excellence in Freelancing" award for 2023. Sarah specializes in brand identity design and UI/UX for tech startups.</p>
                    <a href="#" class="btn btn-primary">Read Her Story</a>
                </div>
            </div>
        </div>
    </section>
    <br>

    <!-- Companies Section -->
    <section class="companies-section container mt-5">
        <div class="section-header text-center mb-5">
            <h1 class="display-4 font-weight-bold">Trusted by Leading Companies</h1>
            <p class="lead text-muted">Empowering businesses worldwide through our platform</p>
            <div class="divider mx-auto"></div>
        </div>

        <div class="row justify-content-center">
            <!-- Company Logos -->
            <div class="col-md-3 col-sm-6 company-item">
                <div class="company-card">
                    <a href="https://www.company1.com" target="_blank" class="company-link">
                        <img src="company/1.webp" alt="Company 1" class="img-fluid company-logo">
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 company-item">
                <div class="company-card">
                    <a href="https://www.company2.com" target="_blank" class="company-link">
                        <img src="company/2.png" alt="Company 2" class="img-fluid company-logo">
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 company-item">
                <div class="company-card">
                    <a href="https://www.company3.com" target="_blank" class="company-link">
                        <img src="company/3.webp" alt="Company 3" class="img-fluid company-logo">
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 company-item">
                <div class="company-card">
                    <a href="https://www.company4.com" target="_blank" class="company-link">
                        <img src="company/4.webp" alt="Company 4" class="img-fluid company-logo">
                    </a>
                </div>
            </div>
        </div>
    </section>
    <br><br><br>

    <!-- Testimonial Section -->
    <section class="testimonial-section py-5" style="background-color: #f8f9fa; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <div class="container">
            <!-- Section Header -->
            <div class="text-center mb-5">
                <h2 class="display-4 font-weight-bold">What Our Clients Say</h2>
                <div class="section-divider"></div>
            </div>

            <!-- Testimonial Card -->
            <div class="testimonial-card" style="transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <div class="testimonial-content">
                    <div class="quote-icon">
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <p class="testimonial-text">
                        "Working with SkillSprit has been an amazing experience. The platform is intuitive, 
                        the freelancers are professional, and the results exceeded my expectations. 
                        I highly recommend this platform to anyone looking for quality work."
                    </p>
                    <div class="testimonial-author text-center">
                        <div class="author-image">
                            <img src="user/polash.jpg" alt="Polash Ahmed" class="rounded-circle testimonial-author-img" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #007bff;">
                        </div>
                        <div class="author-info mt-2">
                            <h4>Polash Ahmed</h4>
                            <p class="author-title">Satisfied Client</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br><br>

    <!-- About Section -->
    <section id="about" class="bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 about-content">
                    <div class="about-text-wrapper">
                        <span class="section-subtitle">Who We Are</span>
                        <h2 class="display-4 font-weight-bold">About Our Freelancing Platform</h2>
                        <div class="about-description">
                            <p class="lead mb-4">
                                Welcome to our freelancing platform! We connect talented freelancers with businesses 
                                and individuals seeking top-notch services.
                            </p>
                            <div class="feature-list">
                                <div class="feature-item">
                                    <i class="fas fa-code feature-icon"></i>
                                    <span>Web Development</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-paint-brush feature-icon"></i>
                                    <span>Graphic Design</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-pen-fancy feature-icon"></i>
                                    <span>Content Writing</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-chart-line feature-icon"></i>
                                    <span>Digital Marketing</span>
                                </div>
                            </div>
                            <p class="mt-4">
                                Whether you're a freelancer looking for exciting projects or a client in need of 
                                specialized skills, our platform provides a seamless and secure environment for collaboration.
                            </p>
                            <button class="btn btn-primary mt-4">Learn More</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-image-wrapper">
                        <img src="https://drrrhyhe9lfip.cloudfront.net/ScriptMedia/54/about_us_thumb01png-514.png" 
                             alt="About Image" 
                             class="img-fluid rounded shadow-lg">
                        <div class="experience-badge">
                            <span class="years">5+</span>
                            <span class="text">Years of Excellence</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> 
    
    <!-- FAQ Section -->
    <section id="faq" class="py-5 faq-section">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="display-4 font-weight-bold">Frequently Asked Questions</h2>
                <div class="divider mx-auto"></div>
            </div>
            
            <div class="accordion custom-accordion" id="faqAccordion">
                <!-- Question 1 -->
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link w-100 text-left d-flex justify-content-between align-items-center" 
                                    type="button" 
                                    data-toggle="collapse" 
                                    data-target="#collapseOne" 
                                    aria-expanded="true" 
                                    aria-controls="collapseOne">
                                How do you create an account?
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#faqAccordion">
                        <div class="card-body">
                            Using the login and registration page. Simply click on the "Register" button in the navigation 
                            menu and follow the easy steps to create your account.
                        </div>
                    </div>
                </div>

                <!-- Question 2 -->
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link w-100 text-left d-flex justify-content-between align-items-center" 
                                    type="button" 
                                    data-toggle="collapse" 
                                    data-target="#collapseTwo" 
                                    aria-expanded="false" 
                                    aria-controls="collapseTwo">
                                How to contact with the employer or freelancer?
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#faqAccordion">
                        <div class="card-body">
                            There is a built-in messaging system for both parties. Once you're connected with an employer 
                            or freelancer, you can easily communicate through our secure messaging platform.
                        </div>
                    </div>
                </div>

                <!-- Question 3 -->
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link w-100 text-left d-flex justify-content-between align-items-center" 
                                    type="button" 
                                    data-toggle="collapse" 
                                    data-target="#collapseThree" 
                                    aria-expanded="false" 
                                    aria-controls="collapseThree">
                                Do I have to pay to register?
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#faqAccordion">
                        <div class="card-body">
                            No. Our freelance marketplace is absolutely free to register and explore the posted job offers, 
                            freelancers, and employers. We believe in providing value first!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer Section -->
    <footer class="footer-section text-center">
        <div class="footer-content">
            <div class="container">
                <div class="row d-flex justify-content-between">
                    <!-- Quick Links Column -->
                    <div class="col-lg-4 col-md-6 footer-column text-center">
                        <h3 class="footer-heading">Quick Links</h3>
                        <ul class="footer-links">
                        <li><a href="index.php""><i class="fas fa-home"></i> Home</a></li>
                            <li><a href="#about""><i class="fas fa-info-circle"></i> How it works</a></li>
                            <li><a href="#faq"><i class="fas fa-question-circle"></i> FAQ</a></li>
                            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                            <li><a href="loginReg.php"><i class="fas fa-user-plus"></i> Register</a></li>
                        </ul>
                    </div>

                    <!-- About Us Column -->
                    <div class="col-lg-4 col-md-6 footer-column">
                        <h3 class="footer-heading">About Us</h3>
                        <div class="team-member">
                            <div class="member-info">
                                <h4>Sudip Singho</h4>
                                <span class="student-id">NEUB ID-210103020001</span>
                            </div>
                            <div class="member-info mt-3">
                                <h4>Polash Ahmed</h4>
                                <span class="student-id">NEUB ID-200103020004</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Column -->
                    <div class="col-lg-4 col-md-6 footer-column">
                        <h3 class="footer-heading">Contact Us</h3>
                        <div class="contact-info">
                            <p><i class="fas fa-university"></i> North East University Bangladesh</p>
                            <p><i class="fas fa-map-marker-alt"></i> Sylhet, Bangladesh</p>
                            <div class="social-links mt-3">
                                <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Copyright Bar -->
                <div class="footer-bottom">
                    <div class="row">
                        <div class="col-12 text-center">
                            <p class="copyright-text">
                                © 2023 NEUB. All rights reserved.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->
    
    <!-- Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 
    <!-- Custom JavaScript -->
    <script>
        $(document).ready(function() {
            $('.custom-accordion .btn-link').click(function() {
                $(this).find('.fa-chevron-down').toggleClass('rotate');
            });
        });
    </script>
</body>

</html>