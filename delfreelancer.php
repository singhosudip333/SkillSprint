<?php
/**
 * delfreelancer.php
 * 
 * This file handles the display and management of freelancers in the system.
 * It allows the admin to view, search, and delete freelancer profiles.
 * 
 * Includes:
 * - User session management
 * - Database operations for fetching and deleting freelancers
 * - HTML structure for displaying freelancers and search functionality
 */

include('server.php');

// Check if the user is logged in
if (isset($_SESSION["Username"])) {
    $username = $_SESSION["Username"];
    // Set links based on user type
    if ($_SESSION["Usertype"] == 1) {
        $linkPro = "freelancerProfile.php";
        $linkEditPro = "editFreelancer.php";
        $linkBtn = "applyJob.php";
        $textBtn = "Apply for this job";
    } else {
        $linkPro = "adminProfile.php";
        $linkEditPro = "delEmployer.php";
        $linkBtn = "editJob.php";
        $textBtn = "Edit the job offer";
    }
} else {
    $username = "";
    // Uncomment the line below to redirect unauthenticated users
    // header("location: index.php");
}

// Handle delete request
if (isset($_POST["delete_user"])) {
    $delete_user = $_POST["delete_user"];
    $delete_sql = "DELETE FROM freelancer WHERE username='$delete_user'";
    $conn->query($delete_sql);
}

// Handle freelancer selection
if (isset($_POST["f_user"])) {
    $_SESSION["f_user"] = $_POST["f_user"];
    header("location: viewFreelancer.php");
}

// Fetch all freelancers
$sql = "SELECT * FROM freelancer";
$result = $conn->query($sql);

// Search functionality
if (isset($_POST["s_username"])) {
    $t = $_POST["s_username"];
    $sql = "SELECT * FROM freelancer WHERE username='$t'";
    $result = $conn->query($sql);
}

if (isset($_POST["s_name"])) {
    $t = $_POST["s_name"];
    $sql = "SELECT * FROM freelancer WHERE Name='$t'";
    $result = $conn->query($sql);
}

if (isset($_POST["s_email"])) {
    $t = $_POST["s_email"];
    $sql = "SELECT * FROM freelancer WHERE email='$t'";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Freelancers</title>
    <link rel="icon" href="./icon/shotcut_logo.png" type="image/x-icon">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="awesome/css/fontawesome-all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fprofile.css">
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
                <li><a href="delFreelancer.php">Browse Freelancers</a></li>
                <li><a href="delEmployer.php">Browse Employers</a></li>
                <li><a href="adminProfile.php">Admin</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar menu -->

<!-- Main body -->
<div style="padding:1% 3% 1% 3%;">
    <div class="row">
        <!-- Column 1 -->
        <div class="col-lg-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Available Freelancers</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr class="bg-primary">
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Professional Title</th>
                                    <th>Email</th>
                                    <th>Skills</th>
                                    <th>Action</th> <!-- New column for the delete button -->
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $f_username = $row["username"];
                                    echo '
                                    <tr>
                                        <td>' . htmlspecialchars($f_username) . '</td>
                                        <td>' . htmlspecialchars($row["Name"]) . '</td>
                                        <td><span class="label label-info">' . htmlspecialchars($row["prof_title"]) . '</span></td>
                                        <td>' . htmlspecialchars($row["email"]) . '</td>
                                        <td><span class="label label-info">' . htmlspecialchars($row["skills"]) . '</span></td>
                                        <td>
                                            <form action="delfreelancer.php" method="post">
                                                <input type="hidden" name="delete_user" value="' . htmlspecialchars($f_username) . '">
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="6" class="text-center">No freelancers found</td></tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Column 2 -->
        <div class="col-lg-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Search Freelancers</h3>
                </div>
                <div class="panel-body">
                    <!-- Search by Username -->
                    <form action="delfreelancer.php" method="post" class="search-form">
                        <div class="form-group">
                            <label for="s_username">Search by Username</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="s_username" name="s_username" placeholder="Enter username...">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>

                    <!-- Search by Name -->
                    <form action="delfreelancer.php" method="post" class="search-form">
                        <div class="form-group">
                            <label for="s_name">Search by Name</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="s_name" name="s_name" placeholder="Enter name...">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>

                    <!-- Search by Email -->
                    <form action="delfreelancer.php" method="post" class="search-form">
                        <div class="form-group">
                            <label for="s_email">Search by Email</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="s_email" name="s_email" placeholder="Enter email...">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>

                    <!-- Show All Button -->
                    <form action="delfreelancer.php" method="post">
                        <button type="submit" class="btn btn-block btn-info">
                            <i class="fas fa-sync"></i> Show All Freelancers
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End main body -->

<!-- Footer -->
<div class="footer-section">
    <div class="footer-content">
        <div class="container">
            <div class="row">
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