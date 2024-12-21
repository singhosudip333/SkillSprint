<?php
// postJob.php
// This file handles the job posting functionality, including form submission and database insertion.

include('server.php');

// Check if the user is logged in
if (isset($_SESSION["Username"])) {
    $username = $_SESSION["Username"];
} else {
    $username = "";
}

// Handle job posting form submission
if (isset($_POST["postJob"])) {
    $title = test_input($_POST["title"]);
    $type = test_input($_POST["type"]);
    $description = test_input($_POST["description"]);
    $budget = test_input($_POST["budget"]);
    $skills = test_input($_POST["skills"]);
    $special_skill = test_input($_POST["special_skill"]);
    $deadline = test_input($_POST["deadline"]);

    // Insert job offer into the database
    $sql = "INSERT INTO job_offer (title, type, description, budget, skills, special_skill, e_username, valid, timestamp) VALUES ('$title', '$type', '$description','$budget','$skills','$special_skill','$username',1, '$deadline')";
    
    $result = $conn->query($sql);
    if ($result == true) {
        $_SESSION["job_id"] = $conn->insert_id;
        header("location: jobDetails.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Post a Job</title>
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
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background: #fff;
            border-radius: 8px;
            padding: 20px;
        }
        .page-header {
            margin: 30px 0px;
        }
        .btn-info {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-info:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .footer-section {
            background: #222;
            color: #fff;
            padding: 20px 0;
        }
    </style>
</head>
<body>

<!-- Navbar menu -->
<nav class="navbar navbar-inverse navbar-fixed-top" id="my-navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">
                <img src="logo/logo.png" alt="Logo" width="50">
            </a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="allJob.php">Browse all jobs</a></li>
                <li><a href="allFreelancer.php">Browse Freelancers</a></li>
                <li><a href="allEmployer.php">Browse Employers</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> <?php echo htmlspecialchars($username); ?></a>
                    <ul class="dropdown-menu list-group">
                        <a href="employerProfile.php" class="list-group-item"><span class="glyphicon glyphicon-home"></span> View profile</a>
                        <a href="editEmployer.php" class="list-group-item"><span class="glyphicon glyphicon-inbox"></span> Edit Profile</a>
                        <a href="message.php" class="list-group-item"><span class="glyphicon glyphicon-envelope"></span> Messages</a>
                        <a href="logout.php" class="list-group-item"><span class="glyphicon glyphicon-ok"></span> Logout</a>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav><br><br>
<!-- End Navbar menu -->

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="card mt-3">
                <div class="page-header text-center">
                    <h2>Post A Job Offer</h2>
                </div>

                <form id="registrationForm" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Job Title</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="title" value="" required />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Job Type</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="type" value="" required />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Job Description</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" name="description" rows="4" required></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Budget</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="budget" value="" required />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Required Skills</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="skills" value="" required />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Special Requirement</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="special_skill" value="" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Deadline</label>
                        <div class="col-sm-5">
                            <input type="date" class="form-control" name="deadline" required />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="text-center">
                            <button type="submit" name="postJob" class="btn btn-info btn-lg">Post</button>
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
                <div class="col-lg-4 col-md-6 footer-column">
                    <h3 class="footer-heading">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="allJob.php"><i class="fas fa-briefcase"></i> Browse all jobs</a></li>
                        <li><a href="allFreelancer.php"><i class="fas fa-users"></i> Browse Freelancers</a></li>
                        <li><a href="allEmployer.php"><i class="fas fa-building"></i> Browse Employers</a></li>
                    </ul>
                </div>

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
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            title: {
                validators: {
                    notEmpty: {
                        message: 'The title is required and cannot be empty'
                    }
                }
            },
            type: {
                validators: {
                    notEmpty: {
                        message: 'The type is required and cannot be empty'
                    }
                }
            },
            description: {
                validators: {
                    notEmpty: {
                        message: 'The description is required and cannot be empty'
                    }
                }
            },
            deadline: {
                validators: {
                    notEmpty: {
                        message: 'The deadline is required'
                    },
                    date: {
                        format: 'YYYY-MM-DD',
                        message: 'The deadline is not valid'
                    }
                }
            },
            budget: {
                validators: {
                    notEmpty: {
                        message: 'The budget is required and cannot be empty'
                    },
                    stringLength: {
                        max: 11,
                        message: 'The number is too big'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'The number is not valid'
                    }
                }
            }
        }
    });
});
</script>

</body>
</html>