<?php 
// freelancerProfile.php
// This file displays the freelancer's profile, including personal information, job details, and wallet balance.
// It also handles session management and database queries to fetch user data.

include('server.php');

// Check if the connection is established
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (isset($_SESSION["Username"])) {
	$username = $_SESSION["Username"];
} else {
	$username = "";
	// Uncomment the line below to redirect to the index page if not logged in
	// header("location: index.php");
}

// Handle job ID submission
if (isset($_POST["jid"])) {
	$_SESSION["job_id"] = $_POST["jid"];
	header("location: jobDetails.php");
}

// Handle employer user submission
if (isset($_POST["e_user"])) {
	$_SESSION["e_user"] = $_POST["e_user"];
	header("location: viewEmployer.php");
}

// Fetch freelancer details from the database
$sql = "SELECT * FROM freelancer WHERE username='$username'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
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

// Fetch unread messages count
$sqlUnreadMessages = "SELECT COUNT(*) AS unread_count FROM `message` WHERE `receiver`='$username' AND `status`=0";
$resultUnreadMessages = $conn->query($sqlUnreadMessages);

if ($resultUnreadMessages->num_rows > 0) {
	$rowUnreadMessages = $resultUnreadMessages->fetch_assoc();
	$unreadMessagesCount = $rowUnreadMessages['unread_count'];
} else {
	$unreadMessagesCount = 0;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Freelancer Profile</title>
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

		<!-- Profile Content Section -->
		<div style="padding:1% 3% 1% 3%;">
			<div class="row">
				<div class="col-lg-3">
					<!-- User Information Card -->
					<div class="card" style="padding:20px 20px 5px 20px;margin-top:20px">
						<h2><?php echo $name; ?></h2>
						<p><span class="glyphicon glyphicon-user"></span> <?php echo $username; ?></p>
						<ul class="list-group">
							<a href="editFreelancer.php" class="list-group-item list-group-item-info">Edit Profile</a>
							<a href="message.php" class="list-group-item list-group-item-info">
								<span class="glyphicon glyphicon-envelope"></span> Messages
								<?php if ($unreadMessagesCount > 0): ?>
									<span class="badge"><?php echo $unreadMessagesCount; ?></span>
								<?php endif; ?>
							</a>
							<a href="logout.php" class="list-group-item list-group-item-info">Logout</a>
						</ul>
					</div>
					
					<!-- Contact Information Card -->
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
				</div>
				
				<div class="col-lg-7">
					<!-- Profile Details Card -->
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
							<div class="panel-heading">Profile Summary</div>
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

						<div class="panel panel-primary">
							<div class="panel-heading">Current Jobs</div>
							<div class="panel-body" style="max-height: 150px; overflow-y: auto;">
								<h4>
									<table style="width:100%">
										<tr>
											<td>Job Id</td>
											<td>Title</td>
											<td>Employer</td>
										</tr>
										<?php 
										// Fetch current jobs for the freelancer
										$sql = "SELECT * FROM job_offer,selected WHERE job_offer.job_id=selected.job_id AND selected.f_username='$username' AND selected.valid=1 ORDER BY job_offer.timestamp DESC";
										$result = $conn->query($sql);
										if ($result->num_rows > 0) {
											while($row = $result->fetch_assoc()) {
												$job_id = $row["job_id"];
												$title = $row["title"];
												$e_username = $row["e_username"];
												$timestamp = $row["timestamp"];
												echo '
												<form action="employerProfile.php" method="post">
													<input type="hidden" name="jid" value="'.$job_id.'">
													<tr>
														<td>'.$job_id.'</td>
														<td><input type="submit" class="btn btn-link btn-lg" value="'.$title.'"></td>
												</form>
												<form action="viewEmployer.php" method="post">
													<input type="hidden" name="e_user" value="'.$e_username.'">
													<td><input type="submit" class="btn btn-link btn-lg" value="'.$e_username.'"></td>
													<td>'.$timestamp.'</td>
													</tr>
												</form>';
											}
										} else {
											echo "<tr><td>Nothing to show</td></tr>";
										}
										?>
									</table>
								</h4>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-2">
					<?php
					// Fetch freelancer's balance
					$sql_balance = "SELECT balance FROM freelancer WHERE username='$username'";
					$result_balance = $conn->query($sql_balance);

					if ($result_balance->num_rows > 0) {
						$row_balance = $result_balance->fetch_assoc();
						$balance = $row_balance["balance"];
					} else {
						$balance = 0.0;
					}
					?>
				 
					<!-- Wallet Card -->
					<div class="card" style="padding:20px 20px 5px 20px;margin-top:20px">
						<div class="panel panel-info">
							<div class="panel-heading">
								<h3>My Wallet</h3>
							</div>
						</div>
						<ul class="list-group">
							<li class="list-group-item">Balance: <?php echo $balance; ?> tk</li>
							<li class="list-group-item">Hourly Rate: 3.0</li>
						</ul>
					</div>

					<!-- Social Network Profiles Card -->
					<div class="card" style="padding:20px 20px 5px 20px;margin-top:20px">
						<div class="panel panel-info">
							<div class="panel-heading">
								<h3>Social Network Profiles</h3>
							</div>
						</div>
						<ul class="list-group">
							<li class="list-group-item" style="font-size:20px;color:#3B579D;">
								<i class="fab fa-facebook-square"> Facebook</i>
							</li>
							<li class="list-group-item" style="font-size:20px;color:#D34438;">
								<i class="fab fa-google-plus-square"> Google</i>
							</li>
							<li class="list-group-item" style="font-size:20px;color:#0274B3;">
								<i class="fab fa-linkedin"> Linkedin</i>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

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

		<script type="text/javascript" src="jquery/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>