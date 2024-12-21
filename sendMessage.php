<?php 
// sendMessage.php
// This file handles the sending of messages between users. It checks user authentication, validates the receiver, and processes the message sending.

include('server.php');

// Check if the user is logged in
if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION["Username"];
$msgRcv = isset($_SESSION["msgRcv"]) ? $_SESSION["msgRcv"] : "";

// Set profile links based on user type
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

// Handle message sending
if (isset($_POST['send'])) {
    $sender = $conn->real_escape_string($_SESSION['Username']);
    $receiver = $conn->real_escape_string($_POST['msgTo']);
    $message = $conn->real_escape_string($_POST['msgBody']);
    
    // Validate that the receiver exists (in either freelancer or employer table)
    $checkReceiver = "SELECT username FROM freelancer WHERE username='$receiver' 
                      UNION 
                      SELECT username FROM employer WHERE username='$receiver'";
    $receiverExists = $conn->query($checkReceiver);
    
    if ($receiverExists->num_rows > 0) {
        $sql = "INSERT INTO message (sender, receiver, msg, timestamp, status) 
                VALUES ('$sender', '$receiver', '$message', NOW(), 0)";
        
        if ($conn->query($sql)) {
            $_SESSION['success_msg'] = "Message sent successfully!";
            header("Location: message.php?chat_with=" . urlencode($receiver));
            exit();
        } else {
            $_SESSION['message_error'] = "Error sending message: " . $conn->error;
            header("Location: message.php?chat_with=" . urlencode($receiver));
            exit();
        }
    } else {
        $_SESSION['message_error'] = "Receiver does not exist";
        header("Location: message.php");
        exit();
    }
}

// Continue with HTML output
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Message</title>
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

        <div class="collapse navbar-collapse" id="navbar-collapse" style="margin-right: 0;">
            <ul class="nav navbar-nav navbar-right" style="margin-right: 0;">
                <li><a href="allJob.php">Browse all jobs</a></li>
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

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php if (isset($_SESSION['message_error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                        echo $_SESSION['message_error']; 
                        unset($_SESSION['message_error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success_msg'])): ?>
                <div class="alert alert-success">
                    <?php 
                        echo $_SESSION['success_msg']; 
                        unset($_SESSION['success_msg']);
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="message-card">
                <div class="page-header">
                    <h2 class="text-center">Write Message</h2>
                    <hr class="message-divider">
                </div>

                <form id="registrationForm" method="post" class="message-form">
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">To:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="msgTo" 
                                   value="<?php echo htmlspecialchars($msgRcv); ?>" 
                                   readonly />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Message:</label>
                        <div class="col-sm-10">
                            <textarea class="form-control message-textarea" 
                                      name="msgBody" 
                                      placeholder="Type your message here..."></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12 text-center">
                            <button type="submit" name="send" class="btn btn-primary btn-send">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
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
            msgTo: {
                validators: {
                    notEmpty: {
                        message: 'This is required and cannot be empty'
                    }
                }
            },
            msgBody: {
                validators: {
                    notEmpty: {
                        message: 'This is required and cannot be empty'
                    }
                }
            }
        }
    });
});
</script>

</body>
</html>