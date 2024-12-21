<?php
/**
 * employerProfile.php
 * 
 * This file displays the employer's profile, including their personal information,
 * job offerings, and wallet balance. It handles form submissions for job details
 * and freelancer views, and updates the employer's balance.
 */

// Include server connection
include('server.php');

// Check if the user is logged in
if (isset($_SESSION["Username"])) {
	$username = $_SESSION["Username"];
} else {
	$username = "";
	// header("location: index.php"); // Uncomment to redirect if not logged in
}

// Handle job detail redirection
if (isset($_POST["jid"])) {
	$_SESSION["job_id"] = $_POST["jid"];
	header("location: jobDetails.php");
}

// Handle freelancer view redirection
if (isset($_POST["f_user"])) {
	$_SESSION["f_user"] = $_POST["f_user"];
	header("location: viewFreelancer.php");
}

// Check if the form is submitted for balance update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["balance"])) {
	$depositAmount = $_POST['balance'];

	// Fetch current balance
	$sqlFetchBalance = "SELECT `balance` FROM `employer` WHERE `username`='$username'";
	$result = $conn->query($sqlFetchBalance);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$currentBalance = floatval($row['balance']); // Convert to float

		$updatedBalance = $currentBalance + floatval($depositAmount); // Update balance

		// Update the 'balance' field in the 'employer' table
		$sqlUpdateBalance = "UPDATE `employer` SET `balance`='$updatedBalance' WHERE `username`='$username'";

		if ($conn->query($sqlUpdateBalance) === TRUE) {
			// Redirect to the same page to prevent resubmission
			header("Location: employerProfile.php");
			exit(); // Ensure no further code is executed
		} else {
			echo "Error updating balance: " . $conn->error;
		}
	} else {
		echo "No results found for this employer.";
	}
}

// Fetch employer data
$sql = "SELECT * FROM employer WHERE username='$username'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	// Output data of each row
	while ($row = $result->fetch_assoc()) {
		$name = $row["Name"];
		$email = $row["email"];
		$contactNo = $row["contact_no"];
		$gender = $row["gender"];
		$birthdate = $row["birthdate"];
		$address = $row["address"];
		$profile_sum = $row["profile_sum"];
		$company = $row["company"];
		$balance = $row["balance"];
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

// Fetch job offers
$sql = "SELECT * FROM job_offer WHERE e_username='$username' and valid=1 ORDER BY timestamp DESC";
$result = $conn->query($sql);
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
	<link rel="stylesheet" type="text/css" href="awesome/css/fontawesome-all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="fprofile.css">

	<style>
		body {
			padding-top: 3%;
			margin: 0;
			font-family: 'Josefin Sans', sans-serif;
		}

		.card {
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			background: #fff
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
					<li class="dropdown active">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<span class="glyphicon glyphicon-user"></span> <?php echo htmlspecialchars($username); ?>
						</a>
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

	<!-- Fetch freelancer data from the database -->
	<?php
	$sql = "SELECT * FROM freelancer";
	$result = $conn->query($sql);
	?>

	<!-- Main body -->
	<div style="padding:1% 3%;">
		<div class="row">

			<!-- Column 1 -->
			<div class="col-lg-3">

				<!-- Main profile card -->
				<div class="card" style="padding:20px; margin-top:20px;">
					<h2><?php echo $name; ?></h2>
					<p><span class="glyphicon glyphicon-user"></span> <?php echo $username; ?></p>
					<ul class="list-group">
						<a href="editEmployer.php" class="list-group-item list-group-item-info">
							<span class="glyphicon glyphicon-edit"></span> Edit Profile
						</a>
						<a href="postJob.php" class="list-group-item list-group-item-info">
							<span class="glyphicon glyphicon-plus"></span> Post a Job
						</a>
						<a href="message.php" class="list-group-item list-group-item-info">
							<span class="glyphicon glyphicon-envelope"></span> Messages
							<?php if ($unreadMessagesCount > 0): ?>
								<span class="badge"><?php echo $unreadMessagesCount; ?></span>
							<?php endif; ?>
						</a>
						<a href="logout.php" class="list-group-item list-group-item-info">
							<span class="glyphicon glyphicon-log-out"></span> Logout
						</a>
					</ul>
				</div>
				<!-- End Main profile card -->

				<!-- Contact Information -->
				<div class="card" style="padding:20px; margin-top:20px;">
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
				<div class="card" style="padding:20px; margin-top:20px;">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3>Employer Profile Details</h3>
						</div>
					</div>
					<div class="panel panel-primary">
						<div class="panel-heading">Company Name</div>
						<div class="panel-body">
							<h4><?php echo $company; ?></h4>
						</div>
					</div>

					<div class="panel panel-primary">
						<div class="panel-heading">Profile Summary</div>
						<div class="panel-body">
							<h4><?php echo $profile_sum; ?></h4>
						</div>
					</div>

					<div class="panel panel-primary">
						<div class="panel-heading">Current Job Offerings</div>
						<div class="panel-body" style="max-height: 150px; overflow-y:auto;">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Job Id</th>
										<th>Title</th>
										<th>Posted on</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql = "SELECT * FROM job_offer WHERE e_username='$username' and valid=1 ORDER BY timestamp DESC";
									$result = $conn->query($sql);
									if ($result->num_rows > 0) {
										while ($row = $result->fetch_assoc()) {
											$job_id = $row["job_id"];
											$title = $row["title"];
											$timestamp = $row["timestamp"];

											// Format the timestamp to a more readable format
											$formattedTimestamp = date("F j, Y, g:i a", strtotime($timestamp)); // e.g., January 1, 2023, 5:00 pm

											echo '
													<tr>
														<td>' . $job_id . '</td>
														<td>
															<form action="employerProfile.php" method="post" style="display:inline;">
																<input type="hidden" name="jid" value="' . $job_id . '">
																<input type="submit" class="btn btn-link" value="' . htmlspecialchars($title) . '">
															</form>
														</td>
														<td>' . htmlspecialchars($formattedTimestamp) . '</td>
													</tr>
													';
										}
									} else {
										echo "<tr><td colspan='3'>Nothing to show</td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="panel panel-primary">
						<div class="panel-heading">Currently Hired Freelancers</div>
						<div class="panel-body" style="max-height: 200px; overflow-y:auto;">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Job Id</th>
										<th>Title</th>
										<th>Freelancer</th>
										<th>Timestamp</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql = "SELECT * FROM job_offer,selected WHERE job_offer.job_id=selected.job_id AND selected.e_username='$username' AND selected.valid=1 ORDER BY job_offer.timestamp DESC";
									$result = $conn->query($sql);
									if ($result->num_rows > 0) {
										while ($row = $result->fetch_assoc()) {
											$job_id = $row["job_id"];
											$title = $row["title"];
											$f_username = $row["f_username"];
											$timestamp = $row["timestamp"];

											// Format the timestamp to a more readable format
											$formattedTimestamp = date("F j, Y, g:i a", strtotime($timestamp)); // e.g., January 1, 2023, 5:00 pm

											echo '
                                <tr>
                                    <td>' . $job_id . '</td>
                                    <td>
                                        <form action="employerProfile.php" method="post" style="display:inline;">
                                            <input type="hidden" name="jid" value="' . $job_id . '">
                                            <input type="submit" class="btn btn-link" value="' . htmlspecialchars($title) . '">
                                        </form>
                                    </td>
                                    <td>
                                        <form action="employerProfile.php" method="post" style="display:inline;">
                                            <input type="hidden" name="f_user" value="' . $f_username . '">
                                            <input type="submit" class="btn btn-link" value="' . htmlspecialchars($f_username) . '">
                                        </form>
                                    </td>
                                    <td>' . htmlspecialchars($formattedTimestamp) . '</td>
                                </tr>
                                ';
										}
									} else {
										echo "<tr><td colspan='4'>Nothing to show</td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="panel panel-primary">
						<div class="panel-heading">Previously Hired Freelancers</div>
						<div class="panel-body" style="max-height: 200px; overflow-y:auto;">
							<table class="table table-striped">
								<thead>
									<tr class="color-black">
										<th>Job Id</th>
										<th>Title</th>
										<th>Freelancer</th>
										<th>Timestamp</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql = "SELECT * FROM job_offer,selected WHERE job_offer.job_id=selected.job_id AND selected.e_username='$username' AND selected.valid=0 ORDER BY job_offer.timestamp DESC";
									$result = $conn->query($sql);
									if ($result->num_rows > 0) {
										while ($row = $result->fetch_assoc()) {
											$job_id = $row["job_id"];
											$title = $row["title"];
											$f_username = $row["f_username"];
											$timestamp = $row["timestamp"];

											// Format the timestamp to a more readable format
											$formattedTimestamp = date("F j, Y, g:i a", strtotime($timestamp)); // e.g., January 1, 2023, 5:00 pm

											echo '
                                <tr>
                                    <td>' . $job_id . '</td>
                                    <td>
                                        <form action="employerProfile.php" method="post" style="display:inline;">
                                            <input type="hidden" name="jid" value="' . $job_id . '">
                                            <input type="submit" class="btn btn-link" value="' . htmlspecialchars($title) . '">
                                        </form>
                                    </td>
                                    <td>
                                        <form action="employerProfile.php" method="post" style="display:inline;">
                                            <input type="hidden" name="f_user" value="' . $f_username . '">
                                            <input type="submit" class="btn btn-link" value="' . htmlspecialchars($f_username) . '">
                                        </form>
                                    </td>
                                    <td>' . htmlspecialchars($formattedTimestamp) . '</td>
                                </tr>
                                ';
										}
									} else {
										echo "<tr><td colspan='4'>Nothing to show</td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
					</div>

				</div>

			</div>
			<!-- End Employer Profile Details -->

			<!-- End Column 2 -->

			<!-- Column 3 -->
			<div class="col-lg-2">
				<!-- My Wallet -->
				<div class="card" style="padding:20px; margin-top:20px;">
					<div class="panel panel-info">
						<div class="panel-heading">
							<h3>My Wallet</h3>
						</div>
					</div>
					<ul class="list-group">
						<li class="list-group-item">Balance: <?php echo $balance; ?> tk</li>
						<li class="list-group-item">Payment Method: Credit/Debit Card</li>
						<a href="deposit.php">
							<li class="list-group-item list-group-item-success">Deposit</li>
						</a>
					</ul>
				</div>
				<!-- End My Wallet -->

				<!-- Social Network Profiles -->
				<div class="card" style="padding:20px; margin-top:20px;">
					<div class="panel panel-info">
						<div class="panel-heading">
							<h3>Social Network Profiles</h3>
						</div>
					</div>
					<ul class="list-group">
						<li class="list-group-item" style="font-size:20px;color:#3B579D;"><i class="fab fa-facebook-square"> Facebook</i></li>
						<li class="list-group-item" style="font-size:20px;color:#D34438;"><i class="fab fa-google-plus-square"> Google</i></li>
						<li class="list-group-item" style="font-size:20px;color:#0274B3;"><i class="fab fa-linkedin"> Linkedin</i></li>
					</ul>
				</div>
				<!-- End Social Network Profiles -->

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