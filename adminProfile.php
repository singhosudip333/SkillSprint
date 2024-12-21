<?php
// File: adminProfile.php
// Description: Admin profile page
// Features:
// - Displays admin details
// - Shows total number of freelancers and employers
// - Navigation menu for admin actions

include('server.php'); // Include server-side logic for database connection

// Check if the user is logged in
if (isset($_SESSION["Username"])) {
    $username = $_SESSION["Username"]; // Get the username from the session
    // Determine user type and set appropriate links
    if ($_SESSION["Usertype"] == 1) {
        $linkPro = "freelancerProfile.php";
        $linkEditPro = "editFreelancer.php";
        $linkBtn = "applyJob.php";
        $textBtn = "Apply for this job";
    } else {
        $linkPro = "adminProfile.php";
        $linkEditPro = "editEmployer.php";
        $linkBtn = "editJob.php";
        $textBtn = "Edit the job offer";
    }
} else {
    $username = ""; // Default username if not logged in
    // header("location: index.php"); // Uncomment to redirect if not logged in
}

// Check user type and redirect if not an admin
if (isset($_SESSION["Usertype"])) {
    $usertype = $_SESSION["Usertype"];
    if ($usertype != 3) {
        header("location: ./login.php"); // Redirect to login if not an admin
    }
} else {
    header("location: ./login.php"); // Redirect to login if user type is not set
}

// Handle freelancer selection from a form submission
if (isset($_POST["f_user"])) {
    $_SESSION["f_user"] = $_POST["f_user"]; // Store selected freelancer in session
    header("location: viewFreelancer.php"); // Redirect to view freelancer page
}

// Fetch all freelancers from the database
$sql = "SELECT * FROM freelancer";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
   <head>
      <title>Admin Dashboard</title>
      <link rel="icon" href="./icon/shotcut_logo.png" type="image/x-icon">
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- Stylesheets for Bootstrap and Font Awesome -->
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
                    <li class="dropdown" style="padding:0 20px 0 20px;">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            Admin
                        </a>
                        <ul class="dropdown-menu list-group">
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
      <div style="padding:2% 5%;">
         <div style="width:100%; border-collapse: collapse; margin-top:20px;" class="row">
            <div class="container">
               <div class="row m-auto">
                  <div class="col-md-3"></div>
                  <div class="col-md-6 d-flex justify-content-center">
                     <div class="card" style="border: 1px solid #007bff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); margin-bottom: 20px;">
                        <div class="card-header" style="background-color: #007bff; color: white; font-size: 1.5em; text-align: center;">
                           Admin Details
                        </div>
                        <div class="card-body" style="text-align: center;">
                           <?php
                              // Placeholder for admin details
                              $adminName = "Admin Name"; // Replace with actual admin name
                              $adminEmail = "admin@example.com"; // Replace with actual admin email
                              $adminDetails = "Other Admin Details"; // Replace with actual admin details
                              ?>
                           <h5 class="card-title" style="font-weight: bold;"><?php echo 'sudipolash'; ?></h5>
                           <p class="card-text">
                              <strong>Email:</strong> <?php echo 'polashahmed@gmail.com'; ?><br>
                              <strong>Phone:</strong> 01726503600 <br>
                              <strong>Address:</strong> Sylhet <br>
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-3"></div>
               </div>
               <br>
               <div class="row">
                  <div class="col-md-3"></div>
                  <div class="col-md-6">
                     <div class="card" style="border: 1px solid #007bff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);">
                        <div class="card-header" style="background-color: #007bff; color: white; font-size: 1.5em; text-align: center;">
                           Total Numbers
                        </div>
                        <div class="card-body" style="text-align: center;">
                           <?php
                              // Fetch total number of freelancers and employers from the database
                              $sqlFreelancersCount = "SELECT COUNT(*) AS total_freelancers FROM freelancer";
                              $resultFreelancersCount = $conn->query($sqlFreelancersCount);
                              $rowFreelancersCount = $resultFreelancersCount->fetch_assoc();
                              $totalFreelancers = $rowFreelancersCount['total_freelancers'];
                              
                              $sqlEmployersCount = "SELECT COUNT(*) AS total_employers FROM employer";
                              $resultEmployersCount = $conn->query($sqlEmployersCount);
                              $rowEmployersCount = $resultEmployersCount->fetch_assoc();
                              $totalEmployers = $rowEmployersCount['total_employers'];
                              ?>
                           <p class="card-text">
                              <strong>Total Freelancers:</strong> <span style="color: #007bff; font-size: 1.2em;"><?php echo $totalFreelancers; ?></span><br>
                              <strong>Total Employers:</strong> <span style="color: #007bff; font-size: 1.2em;"><?php echo $totalEmployers; ?></span>
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-3"></div>
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
   </body>
</html>