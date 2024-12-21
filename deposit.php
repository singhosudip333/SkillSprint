<?php
/**
 * deposit.php
 * 
 * This file handles the deposit functionality for the employer profile.
 * It includes session management, payment instructions, and a form for 
 * confirming deposits via bKash or Nagad.
 */

include('server.php');

// ==================== SESSION MANAGEMENT ====================

// Check if the user is logged in and retrieve the username
if (isset($_SESSION["Username"])) {
    $username = $_SESSION["Username"];
} else {
    $username = "";
    // Uncomment the line below to redirect to the index page if not logged in
    // header("location: index.php");
}

// ==================== FORM HANDLING ====================

// Handle job ID submission
if (isset($_POST["jid"])) {
    $_SESSION["job_id"] = $_POST["jid"];
    header("location: jobDetails.php");
}

// Handle freelancer user submission
if (isset($_POST["f_user"])) {
    $_SESSION["f_user"] = $_POST["f_user"];
    header("location: viewFreelancer.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employer Profile</title>
    <link rel="icon" href="./icon/shotcut_logo.png" type="image/x-icon">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="eprofile.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" type="text/css" href="awesome/css/fontawesome-all.min.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fprofile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOM6g5z4z5e5e5e5e5e5e5e5e5e5e5e5e5e5e5e" crossorigin="anonymous">
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
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
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
    <!-- ==================== NAVBAR ==================== -->
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

    <br><br>

    <!-- ==================== PAYMENT INSTRUCTIONS ==================== -->
    <div class="alert alert-info text-center">
        <strong>Payment Instructions:</strong> Please make your payment via bKash or Nagad. After completing the payment, enter the transaction ID below to confirm your deposit.
    </div>

    <!-- ==================== PAYMENT METHODS ==================== -->
    <div class="container text-center">
        <h3>How to Pay</h3>
        <div class="row">
            <div class="col-md-6">
                <h4>bKash</h4>
                <img src="./logo/bkash.png" alt="bKash" style="width: 100px; height: auto;">
                <p>1. Open your bKash app.<br>
                   2. Select "Send Money".<br>
                   3. Enter the merchant number.<br>
                   4. Enter the amount<br>
                   5. Confirm the payment.</p>
            </div>
            <div class="col-md-6">
                <h4>Nagad</h4>
                <img src="./logo/nagad_marchant.png" alt="Nagad" style="width: 100px; height: auto;">
                <p>1. Open your Nagad app.<br>
                   2. Select "Send Money".<br>
                   3. Enter the merchant number.<br>
                   4. Enter the amount<br>
                   5. Confirm the payment.</p>
            </div>
        </div>
    </div>

    <!-- ==================== DEPOSIT FORM ==================== -->
    <div class="container">
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 card">
                <h2 class="text-center page-header">Deposit into Your Account</h2><br>
                <form action="employerProfile.php" method="POST">
                    <div class="form-group">
                        <label for="balanceInput">Balance:</label>
                        <input type="number" name="balance" class="form-control" id="balanceInput" placeholder="Enter amount" required>
                    </div>
                    <div class="form-group">
                        <label for="paymentMethod">Payment Method:</label>
                        <select name="paymentMethod" class="form-control" id="paymentMethod" required>
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="transactionIdInput">Transaction ID:</label>
                        <input type="text" class="form-control" id="transactionIdInput" placeholder="Enter transaction ID" required>
                    </div><br>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Confirm Deposit</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>

    <!-- ==================== FOOTER ==================== -->
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

    <!-- ==================== SCRIPTS ==================== -->
    <script type="text/javascript" src="jquery/jquery-3.2.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>