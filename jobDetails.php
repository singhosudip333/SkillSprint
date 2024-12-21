<?php
/**
 * jobDetails.php
 * 
 * This file displays the details of a job offer, including the job description,
 * budget, required skills, and applicants for the job. It also handles payment
 * confirmation and user session management.
 */

include('server.php');

// Check if the user is logged in
if (isset($_SESSION["Username"])) {
    $username = $_SESSION["Username"];
    $_SESSION["user"] = $username;

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
    $username = "";
}

// Get job ID from session
$job_id = isset($_SESSION["job_id"]) ? $_SESSION["job_id"] : "";

// Handle form submissions
if (isset($_POST["f_user"])) {
    $_SESSION["f_user"] = $_POST["f_user"];
    header("location: viewFreelancer.php");
}

if (isset($_POST["c_letter"])) {
    $_SESSION["c_letter"] = $_POST["c_letter"];
    header("location: coverLetter.php");
}

if (isset($_POST["f_done"])) {
    header("location: transection.php?username=$f_username&");
    exit();
}

// Fetch job offer details
$sql = "SELECT * FROM job_offer WHERE job_id='$job_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $e_username = $row["e_username"];
    
    // Fetch employer's name and contact information using e_username
    $nameQuery = "SELECT name, email, contact_no, address FROM employer WHERE username='$e_username'";
    $nameResult = $conn->query($nameQuery);
    if ($nameResult && $nameRow = $nameResult->fetch_assoc()) {
        $e_Name = $nameRow['name']; // Get employer's name
        $email = $nameRow['email']; // Get employer's email
        $contact_no = $nameRow['contact_no']; // Get employer's contact number
        $address = $nameRow['address']; // Get employer's address
    } else {
        $e_Name = "Unknown Employer";
        $email = "Not available";
        $contact_no = "Not available";
        $address = "Not available";
    }

    $title = $row["title"];
    $type = $row["type"];
    $description = $row["description"];
    $budget = $row["budget"];
    $skills = $row["skills"];
    $special_skill = $row["special_skill"];
    $timestamp = $row["timestamp"];
    $jv = $row["valid"];
} else {
    echo "0 results";
}

$_SESSION["msgRcv"] = $e_username;

// Handle payment confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_payment"])) {
    $query = "SELECT balance FROM employer WHERE username = '$username'";
    $jobAmount = $_POST['job_amount'];
    $freelancer_name = $_POST['freelancer_name'];
    
    // Get employer balance
    $result = $conn->query($query);
    if ($result && $row = $result->fetch_assoc()) {
        $employerBalance = $row['balance'];
    } else {
        echo "Error fetching employer balance.";
        exit();
    }

    // Get freelancer balance
    $query = "SELECT balance FROM freelancer WHERE username = '$freelancer_name'";
    $result = $conn->query($query);
    if ($result && $row = $result->fetch_assoc()) {
        $freelancerBalance = $row['balance'];
    } else {
        echo "Error fetching freelancer balance.";
        exit();
    }

    // Process payment if sufficient balance
    if ($employerBalance >= $jobAmount) {
        $newBalance = $employerBalance - $jobAmount;
        $updateQuery = "UPDATE employer SET balance = $newBalance WHERE username = '$username'";
        $conn->query($updateQuery);

        $newFreelancerBalance = $freelancerBalance + $jobAmount;
        $updateFreelancerQuery = "UPDATE freelancer SET balance = $newFreelancerBalance WHERE username = '$freelancer_name'";
        $conn->query($updateFreelancerQuery);

        $job_idtmp = $_POST['jobid'];
        $sql = "UPDATE selected SET valid=0 WHERE job_id='$job_idtmp'";
        $conn->query($sql);
    } else {
        echo "Insufficient balance to make the payment.";
    }
}

// Fetch applicants for the job
$applicantsQuery = "SELECT f.username AS f_username, f.name AS applicant_name, a.bid, a.cover_letter 
                    FROM apply a 
                    JOIN freelancer f ON a.f_username = f.username 
                    WHERE a.job_id='$job_id' 
                    ORDER BY a.bid";
$applicantsResult = $conn->query($applicantsQuery);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Job Details</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="awesome/css/fontawesome-all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fprofile.css">

    <style>
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            background: #fff;
            margin-top: 20px;
        }
    </style>
    <script>
        function confirmHire() {
            alert("Form is being submitted!"); // Debugging alert
            return true; // Allow form submission
        }
    </script>
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
                            <a href="<?php echo $linkPro; ?>" class="list-group-item"><span class="glyphicon glyphicon-home"></span> View profile</a>
                            <a href="<?php echo $linkEditPro; ?>" class="list-group-item"><span class="glyphicon glyphicon-inbox"></span> Edit Profile</a>
                            <a href="message.php" class="list-group-item"><span class="glyphicon glyphicon-envelope"></span> Messages</a>
                            <a href="logout.php" class="list-group-item"><span class="glyphicon glyphicon-ok"></span> Logout</a>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar menu -->

    <!-- Main body -->
    <div style="padding:1% 3%;">
        <div class="row">
            <!-- Column 1: Job Offer Details -->
            <div class="col-lg-7">
                <div class="card" style="padding:20px;">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Job Offer Details</h3>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">Job Title</div>
                        <div class="panel-body">
                            <h4><?php echo $title; ?></h4>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">Job Type</div>
                        <div class="panel-body">
                            <h4><?php echo $type; ?></h4>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">Job Description</div>
                        <div class="panel-body">
                            <h4><?php echo $description; ?></h4>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">Budget</div>
                        <div class="panel-body">
                            <h4><?php echo $budget; ?></h4>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">Required Skills</div>
                        <div class="panel-body">
                            <h4><?php echo $skills; ?></h4>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">Special Requirement</div>
                        <div class="panel-body">
                            <h4><?php echo $special_skill; ?></h4>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">Timestamp</div>
                        <div class="panel-body">
                            <h4><?php echo $timestamp; ?></h4>
                        </div>
                    </div>
                    <a href="<?php echo $linkBtn; ?>" id="applybtn" type="button" class="btn btn-warning btn-lg"><?php echo $textBtn; ?></a>
                </div>
                <!-- End Job Offer Details -->

                <!-- Applicants for this job -->
                <div id="applicant" class="card" style="padding:20px;">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Applicants for this job</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Applicant's Name</th>
                                        <th>Bid</th>
                                        <th>Cover Letter</th>
                                        <?php if ($_SESSION["Usertype"] == 2) { // Only show actions for employers ?>
                                            <th>Actions</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if ($applicantsResult->num_rows > 0) {
                                        while ($row = $applicantsResult->fetch_assoc()) {
                                            $f_username = $row["f_username"];
                                            $applicantName = $row["applicant_name"];
                                            $bid = $row["bid"];
                                            $cover_letter = $row["cover_letter"];

                                            echo '
                                            <tr>
                                                <td>'.$applicantName.'</td>
                                                <td>'.$bid.'</td>
                                                <td>
                                                    <form action="jobDetails.php" method="post" style="display:inline;">
                                                        <input type="hidden" name="c_letter" value="'.$cover_letter.'">
                                                        <input type="submit" class="btn btn-link" value="View Cover Letter">
                                                    </form>
                                                </td>';
                                            if ($_SESSION["Usertype"] == 2) { // Only show actions for employers
                                                echo '<td>
                                                    <form action="handlePayment.php" method="post" style="display:inline;">
                                                        <input type="hidden" name="f_hire" value="'.$f_username.'">
                                                        <input type="hidden" name="f_price" value="'.$bid.'">
                                                        <input type="hidden" name="job_id" value="'.$job_id.'">
                                                        <input type="submit" class="btn btn-link" value="Hire">
                                                    </form>
                                                </td>';
                                            }
                                            echo '</tr>';
                                        }
                                    } else {
                                        $sql = "SELECT * FROM selected WHERE job_id='$job_id'";
                                      $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                              // output data of each row
                                              while($row = $result->fetch_assoc()) {
                                                  $f_username=$row["f_username"];
                                                  $bid=$row["price"];
                                                  $v=$row["valid"];
                                      
                                                  if ($v==0) {
                                                     $tc='<a href="./review.php?job_id='.$job_id.'">Review</a>';
                                                      $tv="";
                                                  }else{
                                                      $tc='<input type="submit" class="btn btn-link btn-lg" value="End job">';
                                                      $tv="f_done";
                                                  }
                                      
                                                  echo '
                                                  <form action="jobDetails.php" method="post">
                                                  <input type="hidden" name="f_user" value="'.$f_username.'">
                                                      <tr>
                                                      <td><input type="submit" class="btn btn-link btn-lg" value="'.$f_username.'"></td>
                                                      <td>'.$bid.'</td>
                                                      </form>
                                                      <form action="handlePayment.php" method="post">
                                                      <input type="hidden" name="'.$tv.'" value="'.$f_username.'">
                                                      <input type="hidden" name="f_price" value="'.$bid.'">
                                                      
                                                      <input type="hidden" name="job_id" value="'.$job_id.'">
                                                      <td>
                                                                  
                                                       '.$tc.'
                                                                  </td>
                                                      </tr>
                                                  </form>
                                      
                                                                               
                                                  ';
                                      
                                                  }
                                          } else {
                                              echo "<tr></tr><tr><td></td><td>Nothing to show</td></tr>";
                                          }
                                          }
                                      
                                         ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End Applicants for this job -->
            </div>
            <!-- End Column 1 -->

            <!-- Column 2: Employer Profile Details -->
            <div class="col-lg-3">
                <div class="card" style="padding:25px;">
                    <h2><?php echo $e_Name; ?></h2>
                    <p><span class="glyphicon glyphicon-user"></span> <?php echo $e_username; ?></p>
                    <center><a href="sendMessage.php" class="btn btn-info"><span class="glyphicon glyphicon-envelope"></span> Send Message</a></center>
                </div>
                <!-- End Employer Profile Details -->

                <!-- Contact Information -->
                <div class="card" style="padding:15px; margin-top:20px;">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4>Contact Information</h4>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading">Email</div>
                        <div class="panel-body"><?php echo $email; ?></div>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading">Mobile</div>
                        <div class="panel-body"><?php echo $contact_no; ?></div>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading">Address</div>
                        <div class="panel-body"><?php echo $address; ?></div>
                    </div>
                </div>
                <!-- End Contact Information -->
            </div>
            <!-- End Column 2 -->

            <!-- Column 3: Related jobs -->
            <div class="col-lg-2">
                <div class="card" style="padding:20px;">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3>Related job offers</h3>
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item">Related job 1</li>
                        <li class="list-group-item">Related job 2</li>
                        <li class="list-group-item">Related job 3</li>
                        <li class="list-group-item">Related job 4</li>
                    </ul>
                </div>
                <!-- End Related jobs -->
            </div>
            <!-- End Column 3 -->
        </div>
    </div>
    <!-- End main body -->

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
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <div class="row">
                        <div class="col-12 text-center">
                            <p class="copyright-text">© 2023 NEUB. All rights reserved.</p>
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
    // Hide buttons based on user type and job validity
    if ($e_username != $username && $_SESSION["Usertype"] != 1) {
        echo "<script>$('#applybtn').hide();</script>";
    } 
    if ($_SESSION["Usertype"] == 1 && $jv == 0) {
        echo "<script>$('#applybtn').hide();</script>";
    } 
    if ($e_username != $username) {
        echo "<script>$('#applicant').hide();</script>";
    }
    ?>
    
</body>
</html>