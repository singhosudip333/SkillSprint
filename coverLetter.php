<?php
/**
 * coverLetter.php
 * 
 * This file displays a cover letter along with job details for a freelancer.
 * It retrieves user and job information from the session and database.
 * 
 * PHP version 7.4
 */

// Include server configuration
include('server.php');

// Initialize username from session
if (isset($_SESSION["Username"])) {
    $username = $_SESSION["Username"];
} else {
    $username = "";
    // Uncomment the line below to redirect if not logged in
    // header("location: index.php");
}

// Initialize cover letter from session
if (isset($_SESSION["c_letter"])) {
    $c_letter = $_SESSION["c_letter"];
}

// Check if the job ID is set in the session
if (isset($_SESSION["job_id"])) {
    $job_id = $_SESSION["job_id"];
    
    // Query to get job details from the job_offer table
    $sql = "SELECT * FROM job_offer WHERE job_id='$job_id'";
    $result = $conn->query($sql);
    
    // Check if the query was successful
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }

    // Fetch job details if available
    if ($result->num_rows > 0) {
        $job = $result->fetch_assoc();
    } else {
        $job = null; // No job found
    }
} else {
    $job = null; // No job ID in session
}

// Check if the freelancer's username is set in the session
if (isset($_SESSION["f_user"])) {
    $f_username = $_SESSION["f_user"];
    
    // Query to get the freelancer's name
    $freelancerQuery = "SELECT name FROM freelancer WHERE username='$f_username'";
    $freelancerResult = $conn->query($freelancerQuery);
    
    // Fetch freelancer's name
    if ($freelancerResult && $freelancerRow = $freelancerResult->fetch_assoc()) {
        $freelancerName = $freelancerRow['name'];
    } else {
        $freelancerName = "Unknown Freelancer"; // Default value if not found
        echo "Error fetching freelancer name: " . $conn->error; // Debugging output
    }
} else {
    $freelancerName = "Unknown Freelancer"; // Default value if not set
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cover Letter</title>
    <link rel="icon" href="./icon/shotcut_logo.png" type="image/x-icon">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="awesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="dist/css/bootstrapValidator.css">
    <link rel="stylesheet" href="fprofile.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            padding-top: 3%;
            margin: 0;
            font-family: 'Josefin Sans', sans-serif; /* Ensure the font is applied */
            background-color: #f8f9fa; /* Light background for better contrast */
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 6px 20px rgba(0, 0, 0, 0.19);
            background: #fff;
            border-radius: 8px; /* Rounded corners for a softer look */
            padding: 20px; /* Added padding for better spacing */
        }
        .navbar {
            background-color: #343a40; /* Darker navbar for better contrast */
        }
        .navbar-brand, .navbar-nav li a {
            color: #ffffff !important; /* White text for navbar items */
        }
        .footer-section {
            background-color: #222; /* Dark footer */
            color: #fff; /* White text for footer */
            padding: 20px 0; /* Added padding for footer */
        }
        .footer-links a {
            color: #ffffff; /* White text for footer links */
        }
        .footer-links a:hover {
            text-decoration: underline; /* Underline on hover for better UX */
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
</nav>
<!-- End Navbar menu -->

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="page-header text-center mb-4">
                <h2>Cover Letter</h2>
            </div>
            <div class="card mb-4">
                <div class="page-header">
                    <h4><?php echo nl2br($c_letter); ?></h4>
                </div>
            </div><br><br>

            <!-- Job Details Section -->
            <?php if ($job): ?>
                <div class="card mt-4">
                    <h3 class="text-center">Job Details</h3>
                    <div class="card-body">
                        <p><strong>Job Title:</strong> <span class="text-primary"><?php echo htmlspecialchars($job['title']); ?></span></p>
                        <p><strong>Type:</strong> <span class="text-secondary"><?php echo htmlspecialchars($job['type']); ?></span></p>
                        <p><strong>Description:</strong> <span class="text-muted"><?php echo nl2br(htmlspecialchars($job['description'])); ?></span></p>
                        <p><strong>Budget:</strong> <span class="text-success"><?php echo htmlspecialchars($job['budget']); ?></span></p>
                        <p><strong>Skills Required:</strong> <span class="text-info"><?php echo htmlspecialchars($job['skills']); ?></span></p>
                        <p><strong>Special Skill:</strong> <span class="text-warning"><?php echo htmlspecialchars($job['special_skill']); ?></span></p>
                        <p><strong>Employer Username:</strong> <span class="text-danger"><?php echo htmlspecialchars($job['e_username']); ?></span></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">No job details found.</div>
            <?php endif; ?>
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

</body>
</html>