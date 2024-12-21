<?php
/**
 * editEmployer.php
 * 
 * This file allows employers to edit their profile information.
 * It retrieves the current profile data from the database and 
 * updates it based on user input. 
 */

include('server.php');

// Check if the user is logged in
if (isset($_SESSION["Username"])) {
    $username = $_SESSION["Username"];
} else {
    $username = "";
    // header("location: index.php"); // Uncomment to redirect if not logged in
}

// Fetch employer data from the database
$sql = "SELECT * FROM employer WHERE username='$username'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $name = $row["Name"];
        $email = $row["email"];
        $contactNo = $row["contact_no"];
        $gender = $row["gender"];
        $birthdate = $row["birthdate"];
        $address = $row["address"];
        $profile_sum = $row["profile_sum"];
        $company = $row["company"];
    }
} else {
    echo "0 results";
}

// Handle form submission for editing employer profile
if (isset($_POST["editEmployer"])) {
    $name = test_input($_POST["name"]);
    $email = test_input($_POST["email"]);
    $contactNo = test_input($_POST["contactNo"]);
    $gender = test_input($_POST["gender"]);
    $birthdate = test_input($_POST["birthdate"]);
    $address = test_input($_POST["address"]);
    $profile_sum = test_input($_POST["profile_sum"]);
    $company = test_input($_POST["company"]);

    // Update employer data in the database
    $sql = "UPDATE employer SET Name='$name', email='$email', contact_no='$contactNo', 
            address='$address', gender='$gender', profile_sum='$profile_sum', 
            birthdate='$birthdate', company='$company' WHERE username='$username'";

    $result = $conn->query($sql);
    if ($result == true) {
        header("location: employerProfile.php");
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Employer Profile</title>
    <link rel="icon" href="./icon/shotcut_logo.png" type="image/x-icon">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="awesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="dist/css/bootstrapValidator.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fprofile.css">

    <style>
        body {
            padding-top: 3%;
            margin: 0;
            font-family: 'Josefin Sans', sans-serif;
        }
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            background: #fff;
        }
    </style>
</head>
<body>

<!-- Navbar menu -->
<nav class="navbar navbar-inverse navbar-fixed-top" id="my-navbar">
    <div class="container-fluid" style="padding-left: 0; padding-right: 0;">
        <!-- Navbar Header - Logo (Far Left) -->
        <div class="navbar-header" style="margin-left: 0;">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php" style="padding-left: 15px;">
                <img src="logo/logo.png" alt="Logo" width="50">
            </a>
        </div>

        <!-- Navbar Collapse - Options (Far Right) -->
        <div class="collapse navbar-collapse" id="navbar-collapse" style="margin-right: 0;">
            <ul class="nav navbar-nav navbar-right" style="margin-right: 0;">
                <li><a href="allJob.php">Browse all jobs</a></li>
                <li><a href="allFreelancer.php">Browse Freelancers</a></li>
                <li><a href="allEmployer.php">Browse Employers</a></li>
                <li class="dropdown" style="background:#000;padding:0 20px 0 20px;">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="glyphicon glyphicon-user"></span> 
                        <?php echo htmlspecialchars($username); ?>
                    </a>
                    <ul class="dropdown-menu list-group">
                        <a href="<?php echo $linkPro; ?>" class="list-group-item">
                            <span class="glyphicon glyphicon-home"></span> View profile
                        </a>
                        <a href="<?php echo $linkEditPro; ?>" class="list-group-item">
                            <span class="glyphicon glyphicon-inbox"></span> Edit Profile
                        </a>
                        <a href="message.php" class="list-group-item">
                            <span class="glyphicon glyphicon-envelope"></span> Messages
                            <?php if ($unreadMessagesCount > 0): ?>
                                <span class="badge"><?php echo $unreadMessagesCount; ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="logout.php" class="list-group-item">
                            <span class="glyphicon glyphicon-ok"></span> Logout
                        </a>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar menu -->

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="edit-profile-container">
                <div class="page-header">
                    <h2 class="text-center">Edit Profile</h2>
                    <hr class="divider">
                </div>

                <form id="registrationForm" method="post" class="form-horizontal">
                    <!-- Personal Information Section -->
                    <div class="section-group">
                        <h4 class="section-title">Personal Information</h4>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Name</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Email address</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="email" value="<?php echo $email; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Contact no.</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="contactNo" value="<?php echo $contactNo; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Gender</label>
                            <div class="col-sm-5">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="gender" 
                                        <?php if (isset($gender) && $gender == "male") echo "checked"; ?>
                                        value="male" /> Male
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="gender" 
                                        <?php if (isset($gender) && $gender == "female") echo "checked"; ?>
                                        value="female" /> Female
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="gender" 
                                        <?php if (isset($gender) && $gender == "other") echo "checked"; ?>
                                        value="other" /> Other
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Date of birth</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="birthdate" placeholder="YYYY/MM/DD" value="<?php echo $birthdate; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Address</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="address" value="<?php echo $address; ?>" />
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information Section -->
                    <div class="section-group">
                        <h4 class="section-title">Professional Information</h4>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Company Name</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="company" value="<?php echo $company; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Profile Summary</label>
                            <div class="col-sm-5">
                                <textarea class="form-control" name="profile_sum" rows="4"><?php echo $profile_sum; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">
                        <div class="text-center">
                            <button type="submit" name="editEmployer" class="btn btn-info btn-lg">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="footer-section">
    <div class="footer-content">
        <div class="container">
            <div class="row d-flex justify-content-between">
                <!-- Quick Links Column -->
                <div class="col-lg-4 col-md-6 footer-column">
                    <h3 class="footer-heading">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="allJob.php"><i class="fas fa-briefcase"></i> Browse all jobs</a></li>
                        <li><a href="allFreelancer.php"><i class="fas fa-users"></i> Browse Freelancers</a></li>
                        <li><a href="allEmployer.php"><i class="fas fa-building"></i> Browse Employers</a></li>
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
</div>
<!-- End Footer -->

<script type="text/javascript" src="jquery/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="dist/js/bootstrapValidator.js"></script>

<script>
$(document).ready(function() {
    $('#registrationForm').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
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
            company: {
                validators: {
                    notEmpty: {
                        message: 'The company name is required'
                    }
                }
            },
            profile_sum: {
                validators: {
                    notEmpty: {
                        message: 'The profile summary is required'
                    }
                }
            }
        }
    });
});
</script>

</body>
</html>