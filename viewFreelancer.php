<?php
/**
 * viewFreelancer.php
 * 
 * This file displays the profile of a freelancer, including their personal information,
 * contact details, reputation, and recent work. It also provides navigation links for
 * browsing jobs and managing user profiles.
 */

include('server.php');
if (isset($_SESSION["Username"])) {
	$username = $_SESSION["Username"];
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
	//header("location: index.php");
}

if (isset($_SESSION["f_user"])) {
	$f_user = $_SESSION["f_user"];
	$_SESSION["msgRcv"] = $f_user;
}

$sql = "SELECT * FROM freelancer WHERE username='$f_user'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	// output data of each row
	while ($row = $result->fetch_assoc()) {
		$name = $row["Name"];
		$email = $row["email"];
		$contactNo = $row["contact_no"];
		$gender = $row["gender"];
		$birthdate = $row["birthdate"];
		$address = $row["address"];
		$prof_title = $row["prof_title"];
		$skills = $row["skills"];
		$profile_sum = $row["profile_sum"];
		$education = $row["education"];
		$experience = $row["experience"];
	}
} else {
	echo "0 results";
}


?>


<!DOCTYPE html>
<html>

<head>
	<title>Freelancer profile</title>
	<link rel="icon" href="./icon/shotcut_logo.png" type="image/x-icon">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="awesome/css/fontawesome-all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="fprofile.css">
</head>

<body>
	<!-- Navbar Section -->
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
	<!--End Navbar menu-->


	<!--main body-->
	<div style="padding:1% 3% 1% 3%;">
		<div class="row">

			<!--Column 1-->
			<div class="col-lg-3">

				<!--Main profile card-->
				<div class="card" style="padding:20px 20px 5px 20px;margin-top:20px">


					<h2><?php echo $name; ?></h2>
					<p><span class="glyphicon glyphicon-user"></span> <?php echo $f_user; ?></p>
					<center><a href="sendMessage.php" class="btn btn-info"><span class="glyphicon glyphicon-envelope"></span> Send Message</a></center>
					<p></p>
				</div>
				<!--End Main profile card-->

				<!--Contact Information-->
				<div class="card" style="padding:20px 20px 5px 20px;margin-top:20px">
					<div class="panel panel-success">
						<div class="panel-heading">
							<h4>Contact Information</h4>
						</div>
					</div>
					<div class="panel panel-success">
						<div class="panel-heading">Email</div>
						<div class="panel-body"><?php echo $email; ?></div>
					</div>
					<div class="panel panel-success">
						<div class="panel-heading">Mobile</div>
						<div class="panel-body"><?php echo $contactNo; ?></div>
					</div>
					<div class="panel panel-success">
						<div class="panel-heading">Address</div>
						<div class="panel-body"><?php echo $address; ?></div>
					</div>
				</div>
				<!--End Contact Information-->

				<!--Reputation-->
				<div class="card" style="padding:20px; margin-top:20px;">
					<div class="panel panel-warning">
						<div class="panel-heading">
							<h4 class="text-center">Reputation</h4>
						</div>
					</div>
					<div class="panel panel-warning">
						<div class="panel-heading">Recent Review</div>
						<div class="panel-body text-center">
						<?php
						// Fetch the most recent review from the database
						$recentReviewQuery = "SELECT `review` FROM `selected` WHERE `f_username`='$f_user'
				and review != '' and rating > 0 ORDER BY `job_id` DESC LIMIT 1";
						$recentReviewResult = $conn->query($recentReviewQuery);

						if ($recentReviewResult->num_rows > 0) {
							$recentReviewRow = $recentReviewResult->fetch_assoc();
							$recentReview = $recentReviewRow['review'];

							echo "<blockquote class='blockquote'><p class='mb-0'>$recentReview</p></blockquote>";
						} else {
							echo "<p>No reviews available.</p>";
						}
						?>
						</div>
					</div>
					<div class="panel panel-warning">
						<div class="panel-heading">Average Rating</div>
						<div class="panel-body text-center">
							<?php
							// Fetch average rating from the database
							$averageRatingQuery = "SELECT AVG(`rating`) as avg_rating FROM `selected` WHERE `f_username`='$f_user' and review != '' and rating > 0";
							$averageRatingResult = $conn->query($averageRatingQuery);

							if ($averageRatingResult->num_rows > 0) {
								$averageRatingRow = $averageRatingResult->fetch_assoc();
								$averageRating = round($averageRatingRow['avg_rating'], 1); // Round to 1 decimal place
								echo "<p class='h4'>$averageRating <span class='text-warning'>★</span></p>";
							} else {
								echo "<p>No ratings available.</p>";
							}
							?>
						</div>
					</div>
				</div>

				<!--End Reputation-->

			</div>
			<!--End Column 1-->

			<!--Column 2-->
			<div class="col-lg-7">

				<!--Freelancer Profile Details-->
				<div class="card" style="padding:20px 20px 5px 20px;margin-top:20px">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3>Freelancer Profile Details</h3>
						</div>
					</div>
					<div class="panel panel-primary">
						<div class="panel-heading">Professional Title</div>
						<div class="panel-body">
							<h4><?php echo $prof_title; ?></h4>
						</div>
					</div>
					<div class="panel panel-primary">
						<div class="panel-heading">Skills</div>
						<div class="panel-body">
							<h4><?php echo $skills; ?></h4>
						</div>
					</div>
					<div class="panel panel-primary">
						<div class="panel-heading">Profile Summery</div>
						<div class="panel-body">
							<h4><?php echo $profile_sum; ?></h4>
						</div>
					</div>
					<div class="panel panel-primary">
						<div class="panel-heading">Education</div>
						<div class="panel-body">
							<h4><?php echo $education; ?></h4>
						</div>
					</div>
					<div class="panel panel-primary">
						<div class="panel-heading">Experience</div>
						<div class="panel-body">
							<h4><?php echo $experience; ?></h4>
						</div>
					</div>
					
					<!-- Recent Work Section -->
					<div class="panel panel-primary">
						<div class="panel-heading">Recent Work</div>
						<div class="panel-body">
							<?php
							// Fetch the recent 5 works with job titles from the selected table
							$recentWorkQuery = "SELECT s.job_id, j.title FROM selected s JOIN job_offer j ON s.job_id = j.job_id WHERE s.f_username='$f_user' ORDER BY s.job_id DESC LIMIT 5";
							$recentWorkResult = $conn->query($recentWorkQuery);

							if ($recentWorkResult === false) {
								// Output error message if the query fails
								echo "<p class='text-danger'>Error fetching recent work: " . $conn->error . "</p>";
							} else if ($recentWorkResult->num_rows > 0) {
								echo "<ul class='list-group'>";
								while ($workRow = $recentWorkResult->fetch_assoc()) {
									echo "<li class='list-group-item'><a href='jobDetails.php?job_id=" . htmlspecialchars($workRow['job_id']) . "' class='text-primary'>" . htmlspecialchars($workRow['title']) . "</a></li>";
								}
								echo "</ul>";
							} else {
								echo "<p>No recent work available.</p>";
							}
							?>
						</div>
					</div>
					<!-- End Recent Work Section -->

				</div>
				<!--End Freelancer Profile Details-->

			</div>
			<!--End Column 2-->


			<!--Column 3-->
			<div class="col-lg-2">

				<!--Social Network Profiles-->
				<div class="card" style="padding:20px 20px 5px 20px;margin-top:20px">
					<div class="panel panel-info">
						<div class="panel-heading">
							<h3>Social Network Profiles</h3>
						</div>
					</div>
					<ul class="list-group">
						<li class="list-group-item" style="font-size:20px;color:#3B579D;"><i class="fab fa-facebook-square"> Facebook</i></li>
						<li class="list-group-item" style="font-size:20px;color:#D34438;"><i class="fab fa-google-plus-square"> Google</i></li>
						<li class="list-group-item" style="font-size:20px;color:#2CAAE1;"><i class="fab fa-twitter-square"> Twitter</i></li>
						<li class="list-group-item" style="font-size:20px;color:#0274B3;"><i class="fab fa-linkedin"> Linkedin</i></li>
					</ul>
				</div>
				<!--End Social Network Profiles-->

			</div>
			<!--End Column 3-->

		</div>
	</div>
	<!--End main body-->


	<!-- Footer Section -->
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
	<!--End Footer-->



	<script type="text/javascript" src="jquery/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</body>

</html>