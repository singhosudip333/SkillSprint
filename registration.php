<?php
// registration.php
// This file handles the user registration form for SkillSprint.
// It includes the server-side logic and displays the registration form.

include('server.php'); // Include server-side logic for handling form submission
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillSprint Registration</title>
    <link rel="icon" href="./icon/shotcut_logo.png" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="reg.css"> <!-- Custom CSS -->
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php"><img src="logo/logo.png" alt="Logo" width="50"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <a href="index.php" class="btn btn-primary mr-2">Home</a>
            <a href="login.php" class="btn btn-primary">Log In</a>
        </div>
    </nav>
    <br>
    <!-- End Navbar -->

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Register Yourself!</h2>
                    </div>
                    <div class="card-body">
                        <form id="registrationForm" method="post" class="form-horizontal">
                            <div style="color:red;">
                                <p><?php echo $errorMsg2; ?></p> <!-- Display error message -->
                            </div>
                            <!-- Form Fields -->
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" placeholder="Enter your name" value="<?php echo $name; ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Username</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="username" placeholder="Enter a username" value="<?php echo $username; ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Email address</label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control" name="email" placeholder="Enter your email" value="<?php echo $email; ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Password</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" name="password" placeholder="Enter password" value="<?php echo $password; ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Retype Password</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" name="repassword" placeholder="Retype password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Contact Number</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="contactNo" placeholder="Enter your number" value="<?php echo $contactNo; ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Gender</label>
                                <div class="col-sm-8">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" value="male">
                                        <label class="form-check-label">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" value="female">
                                        <label class="form-check-label">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" value="other">
                                        <label class="form-check-label">Other</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Date of Birth</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" name="birthdate" value="<?php echo $birthdate; ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Address</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="address" placeholder="Enter your address" value="<?php echo $address; ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">User Type</label>
                                <div class="col-sm-8">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="usertype" value="freelancer">
                                        <label class="form-check-label">Freelancer</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="usertype" value="employer">
                                        <label class="form-check-label">Employer</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12 text-center">
                                    <button type="submit" name="register" class="btn btn-info btn-lg">Sign up</button>
                                </div>
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
                    <!-- Quick Links Column -->
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

                    <!-- About Us Column -->
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

                    <!-- Contact Column -->
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
    <!-- End Footer -->

    <!-- Scripts -->
    <script type="text/javascript" src="jquery/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="dist/js/bootstrapValidator.js"></script>
    <script>
        $(document).ready(function() {
            $('#registrationForm').bootstrapValidator({
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'The name is required and cannot be empty'
                            }
                        }
                    },
                    username: {
                        message: 'The username is not valid',
                        validators: {
                            notEmpty: {
                                message: 'The username is required and cannot be empty'
                            },
                            stringLength: {
                                min: 6,
                                max: 30,
                                message: 'The username must be more than 6 and less than 30 characters long'
                            },
                            regexp: {
                                regexp: /^[a-zA-Z0-9]+$/,
                                message: 'The username can only consist of alphabetical and number'
                            },
                            different: {
                                field: 'password',
                                message: 'The username and password cannot be the same as each other'
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'The email address is required and cannot be empty'
                            },
                            emailAddress: {
                                message: 'The email address is not valid'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'The password is required and cannot be empty'
                            },
                            different: {
                                field: 'username',
                                message: 'The password cannot be the same as username'
                            },
                            stringLength: {
                                min: 6,
                                message: 'The password must have at least 6 characters'
                            }
                        }
                    },
                    repassword: {
                        validators: {
                            notEmpty: {
                                message: 'The password confirmation is required and cannot be empty'
                            },
                            identical: {
                                field: 'password',
                                message: 'The password is not matched'
                            }
                        }
                    },
                    contactNo: {
                        validators: {
                            notEmpty: {
                                message: 'The contact number is required'
                            },
                            regexp: {
                                regexp: /^[0-9]+$/,
                                message: 'The number is not valid'
                            }
                        }
                    },
                    gender: {
                        validators: {
                            notEmpty: {
                                message: 'The gender is required'
                            }
                        }
                    },
                    birthdate: {
                        validators: {
                            notEmpty: {
                                message: 'The date of birth is required'
                            },
                            date: {
                                format: 'YYYY-MM-DD',
                                message: 'The date of birth is not valid'
                            }
                        }
                    },
                    address: {
                        validators: {
                            notEmpty: {
                                message: 'The address is required'
                            }
                        }
                    },
                    usertype: {
                        validators: {
                            notEmpty: {
                                message: 'The usertype is required'
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>