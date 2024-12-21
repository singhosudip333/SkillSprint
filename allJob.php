<?php 
/**
 * allJob.php
 * 
 * This file displays all job offers available in the system. 
 * It allows users to search for jobs by title or type, 
 * view job details, and navigate to their profiles based on user type.
 * 
 */

include('server.php'); // Include server connection

$result = null; // Initialize result variable

// Check if user is logged in
if (isset($_SESSION["Username"])) {
    $username = $_SESSION["Username"];
    // Set links based on user type
    if ($_SESSION["Usertype"] == 1) {
        $linkPro = "freelancerProfile.php";
        $linkEditPro = "editFreelancer.php";
        $linkBtn = "applyJob.php";
        $textBtn = "Apply for this job";
    } else {
        $linkPro = "employerProfile.php";
        $linkEditPro = "editEmployer.php";
        $linkBtn = "editJob.php";
        $textBtn = "Edit the job offer";
    }
} else {
    $username = ""; // No username if not logged in
    // header("location: index.php"); // Uncomment to redirect to index if not logged in
}

// Initialize unread messages count
$unreadMessagesCount = 0;
if (isset($_SESSION["Username"])) {
    // Query to count unread messages
    $username = $_SESSION["Username"];
    $msgCountQuery = "SELECT COUNT(*) as count FROM message WHERE receiver='$username' AND read_status='0'";
    $msgResult = $conn->query($msgCountQuery);
    if ($msgResult) {
        $row = $msgResult->fetch_assoc();
        $unreadMessagesCount = $row['count'];
    }
}

// Redirect to job details if job ID is set
if (isset($_POST["jid"])) {
    $_SESSION["job_id"] = $_POST["jid"];
    header("location: jobDetails.php");
}

// Default query to show all jobs
$sql = "SELECT * FROM job_offer WHERE valid = 1";
$result = $conn->query($sql);

// Improved search handling
function performSearch($conn, $searchType, $searchValue) {
    $validSearchTypes = ['title', 'type', 'e_username', 'job_id'];
    
    if (!in_array($searchType, $validSearchTypes)) {
        return false; // Invalid search type
    }
    
    $sql = "SELECT * FROM job_offer WHERE $searchType LIKE ? AND valid = 1";
    $searchValue = "%$searchValue%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchValue);
    $stmt->execute();
    return $stmt->get_result(); // Return search results
}

// Handle search form submissions
if (isset($_POST['s_title']) && !empty($_POST['s_title'])) {
    $result = performSearch($conn, 'title', $_POST['s_title']);
} elseif (isset($_POST['s_type']) && !empty($_POST['s_type'])) {
    $result = performSearch($conn, 'type', $_POST['s_type']);
} elseif (isset($_POST['recentJob'])) {
    $sql = "SELECT * FROM job_offer WHERE valid = 1 ORDER BY timestamp DESC";
    $result = $conn->query($sql);
} elseif (isset($_POST['oldJob'])) {
    $sql = "SELECT * FROM job_offer WHERE valid = 1 ORDER BY timestamp ASC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Job Offers</title>
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
                    <li class="active"><a href="allJob.php">Browse all jobs</a></li>
                    <li><a href="allFreelancer.php">Browse Freelancers</a></li>
                    <li><a href="allEmployer.php">Browse Employers</a></li>
                    <li class="dropdown" style="padding:0 20px 0 20px;">
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

    <!-- Main body -->
    <div style="padding:1% 3% 1% 3%;">
        <div class="row">
            <!-- Column 1 -->
            <div class="col-lg-9">
                <!-- Job Listings Table -->    
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Available Jobs</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr class="bg-primary">
                                        <th>Job ID</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Budget</th>
                                        <th>Employer</th>
                                        <th>Posted on</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $e_username = '';
                                    // Updated SQL query to join with employer table
                                    $sql = "SELECT job_offer.*, employer.name as employer_name 
                                            FROM job_offer 
                                            JOIN employer ON job_offer.e_username = employer.username 
                                            WHERE job_offer.valid = 1";
                                    $result = $conn->query($sql);
                                    
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $job_id = $row["job_id"];
                                            $title = $row["title"];
                                            $type = $row["type"];
                                            $budget = $row["budget"];
                                            $e_username = $row["employer_name"]; // Use employer's name
                                            $timestamp = $row["timestamp"];

                                            echo '
                                            <tr>
                                                <form action="allJob.php" method="post">
                                                    <input type="hidden" name="jid" value="'.$job_id.'">
                                                    <td>'.$job_id.'</td>
                                                    <td>
                                                        <button type="submit" class="btn btn-link job-title">
                                                            '.$title.' 
                                                        </button>
                                                    </td>
                                                    <td><span class="label label-info">'.$type.'</span></td>
                                                    <td>$'.$budget.'</td>
                                                    <td>'.$e_username.'</td> <!-- Display employer name -->
                                                    <td>'.date('M d, Y', strtotime($timestamp)).'</td>
                                                </form>
                                            </tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="6" class="text-center">No jobs available</td></tr>';
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
                        <h3 class="panel-title">Search Jobs</h3>
                    </div>
                    <div class="panel-body">
                        <!-- Search by Title -->
                        <form action="allJob.php" method="post" class="search-form">
                            <div class="form-group">
                                <label for="s_title">Search by Title</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="s_title" name="s_title" placeholder="Enter job title...">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </form>

                        <!-- Search by Type -->
                        <form action="allJob.php" method="post" class="search-form">
                            <div class="form-group">
                                <label for="s_type">Search by Type</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="s_type" name="s_type" placeholder="Enter job type...">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </form>

                        <!-- Filter Buttons -->
                        <div class="filter-buttons">
                            <form action="allJob.php" method="post">
                                <button type="submit" name="recentJob" class="btn btn-block btn-warning">
                                    <i class="fas fa-clock"></i> Recent Jobs First
                                </button>
                            </form>
                            <form action="allJob.php" method="post">
                                <button type="submit" name="oldJob" class="btn btn-block btn-default">
                                    <i class="fas fa-history"></i> Older Jobs First
                                </button>
                            </form>
                            <form action="allJob.php" method="post">
                                <button type="submit" class="btn btn-block btn-info">
                                    <i class="fas fa-sync"></i> Show All Jobs
                                </button>
                            </form>
                        </div>
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

    <?php 
    // Hide apply button if user is not the employer
    if ($e_username != $username && $_SESSION["Usertype"] != 1) {
        echo "<script>
            $('#applybtn').hide();
        </script>";
    } 
    ?>
</body>
</html>