<?php
// login.php
// This file handles the login page for SkillSprint, including the login form and footer information.

include('server.php'); // Include server-side logic for handling login
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillSprint - Login</title>
    <link rel="icon" href="./icon/shotcut_logo.png" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">
            <img src="logo/logo.png" alt="Logo" width="50">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <a href="index.php" class="btn btn-primary mr-2">Home</a>
            <a href="registration.php" class="btn btn-primary">Registration</a>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="container login-container">
        <div class="row justify-content-center w-100">
            <div class="col-md-6">
                <div class="card login-card">
                    <div class="card-header">
                        <h2 class="text-center m-0">Log In</h2>
                    </div>
                    <div class="card-body">
                        <form id="loginForm" method="post" class="form-horizontal">
                            <div class="form-group">
                                <label>Username</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                    </div>
                                    <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>User Type</label>
                                <div class="d-flex justify-content-around user-type-container">
                                    <button type="button" class="btn btn-outline-primary usertype-btn" data-value="freelancer">
                                        <i class="fas fa-user-tie"></i> Freelancer
                                    </button>
                                    <button type="button" class="btn btn-outline-primary usertype-btn" data-value="employer">
                                        <i class="fas fa-building"></i> Employer
                                    </button>
                                    <button type="button" class="btn btn-outline-primary usertype-btn" data-value="admin">
                                        <i class="fas fa-user-shield"></i> Admin
                                    </button>
                                    <input type="hidden" name="usertype" id="selectedUserType">
                                </div>
                            </div>

                            <div class="form-group text-center mt-4">
                                <button type="submit" name="login" class="btn btn-primary btn-lg login-btn">
                                    <i class="fas fa-sign-in-alt"></i> Log In
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-section">
        <div class="footer-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-3 col-md-6 footer-column">
                        <h3 class="footer-heading">Quick Links</h3>
                        <ul class="footer-links">
                            <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                            <li><a href="#about"><i class="fas fa-info-circle"></i> How it works</a></li>
                            <li><a href="#faq"><i class="fas fa-question-circle"></i> FAQ</a></li>
                            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                            <li><a href="loginReg.php"><i class="fas fa-user-plus"></i> Register</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-column">
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

                    <div class="col-lg-3 col-md-6 footer-column">
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="dist/js/bootstrapValidator.js"></script>

    <!-- Form Validation Script -->
    <script>
        $(document).ready(function() {
            $('#loginForm').bootstrapValidator({
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    username: {
                        message: 'The username is not valid',
                        validators: {
                            notEmpty: {
                                message: 'The username is required and cannot be empty'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'The password is required and cannot be empty'
                            }
                        }
                    },
                    usertype: {
                        validators: {
                            notEmpty: {
                                message: 'Please select a user type'
                            }
                        }
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Handle button clicks for user type selection
            $('.usertype-btn').click(function() {
                // Remove active class from all buttons
                $('.usertype-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
                // Add active class to clicked button
                $(this).removeClass('btn-outline-primary').addClass('active btn-primary');
                // Set the hidden input value
                $('#selectedUserType').val($(this).data('value'));
            });
        });
    </script>
</body>

</html>