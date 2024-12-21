<?php
/**
 * applyJob.php
 * 
 * This file handles the job application process for users. It checks if the user is logged in,
 * verifies if they have already applied for the job, and processes the application submission.
 * It also displays a success message upon successful application.
 */

include('server.php');

// Check if the user is logged in
if (isset($_SESSION["Username"])) {
	$username = $_SESSION["Username"];
} else {
	$username = "";
	// Uncomment the line below to redirect to index if not logged in
	// header("location: index.php");
}

if (isset($_SESSION["job_id"])) {
	$job_id = $_SESSION["job_id"];
} else {
	$job_id = "";
	// Uncomment the line below to redirect to index if job ID is not set
	// header("location: index.php");
}

$sql = "SELECT * FROM apply WHERE job_id='$job_id' and f_username='$username'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	$msg = "You have already applied for this job. You cannot apply again.";
} else {
	$msg = "";
}

$sqlUnreadMessages = "SELECT COUNT(*) AS unread_count FROM `message` WHERE `receiver`='$username' AND `status`=0";
$resultUnreadMessages = $conn->query($sqlUnreadMessages);

if ($resultUnreadMessages->num_rows > 0) {
	$rowUnreadMessages = $resultUnreadMessages->fetch_assoc();
	$unreadMessagesCount = $rowUnreadMessages['unread_count'];
} else {
	$unreadMessagesCount = 0;
}

if (isset($_POST["apply"]) && $msg == "") {
	$cover = test_input($_POST["cover"]);
	$bid = test_input($_POST["bid"]);

	$sql = "INSERT INTO apply (f_username, job_id, bid, cover_letter) VALUES ('$username', '$job_id', '$bid','$cover')";

	$result = $conn->query($sql);
	if ($result == true) {
		// Instead of immediate redirect, we'll set a success flag
		echo "<script>var showSuccessPopup = true;</script>";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Apply for Job</title>
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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

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
						<a href="freelancerProfile.php" class="list-group-item">
							<span class="glyphicon glyphicon-home"></span> View profile
						</a>
						<a href="editFreelancer.php" class="list-group-item">
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
			<div class="card" style="padding: 30px; margin: 30px 0px;">
				<div class="page-header text-center" style="border: none; margin: 0 0 30px 0;">
					<h2 style="color: #2c3e50; font-weight: 600;">Apply for Job</h2>
				</div>

				<form id="registrationForm" method="post" class="form-horizontal">
					<?php if ($msg): ?>
						<div class="alert alert-warning" role="alert">
							<?php echo $msg; ?>
						</div>
					<?php endif; ?>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" style="font-weight: 500;">Cover Letter</label>
						<div class="col-sm-8">
							<textarea class="form-control" rows="17" name="cover" 
								placeholder="Describe why you're the perfect fit for this job..."
								style="resize: vertical; border-radius: 4px; border-color: #dce4ec;"></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" style="font-weight: 500;">Your Bid Amount</label>
						<div class="col-sm-5">
							<div class="input-group">
								<span class="input-group-addon">$</span>
								<input type="text" class="form-control" name="bid" 
									placeholder="Enter your bid amount"
									style="border-radius: 0 4px 4px 0; border-color: #dce4ec;">
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-12 text-center">
							<button type="submit" name="apply" class="btn btn-primary btn-lg" 
								style="padding: 12px 40px; border-radius: 30px; background-color: #3498db; border: none; transition: all 0.3s;">
								Submit Application
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
			cover: {
				validators: {
					notEmpty: {
						message: 'The title is required and cannot be empty'
					}
				}
			},
			bid: {
				validators: {
					notEmpty: {
						message: 'The bid is required and cannot be empty'
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

// Show success popup if application is submitted successfully
if (typeof showSuccessPopup !== 'undefined' && showSuccessPopup) {
	Swal.fire({
		title: 'Success!',
		text: 'Your job application has been submitted successfully.',
		icon: 'success',
		confirmButtonText: 'OK',
		confirmButtonColor: '#3498db'
	}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = 'allJob.php';
		}
	});
}

// Validate form before submission
$('#registrationForm').on('submit', function(e) {
	if ($(this).data('bootstrapValidator').isValid()) {
		// Form is valid, let it submit
		return true;
	}
	e.preventDefault();
	return false;
});
</script>

</body>
</html>