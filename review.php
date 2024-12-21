<?php
// review.php
// This file handles the display of the employer profile and allows users to leave reviews for freelancers.
// It includes session management, database queries, and a review submission form.

include('server.php');

// Check if the user is logged in
if (isset($_SESSION["Username"])) {
	$username = $_SESSION["Username"];
} else {
	$username = "";
	header("location: index.php");
}

// Fetch job details if job_id is set
if (isset($_GET['job_id'])) {
	$job_id = $_GET['job_id'];
}

$sql = "SELECT price, freelancer.Name as fname, employer.Name as ename, f_username, e_username, job_id, title 
		FROM freelancer, employer, 
		(SELECT price, f_username, e_username, job_id, title 
		 FROM selected NATURAL JOIN job_offer) as T 
		WHERE freelancer.username = T.f_username 
		AND T.e_username = employer.username 
		AND T.job_id = $job_id;";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Assign fetched data to variables
$fname = $row['fname'];
$ename = $row['ename'];
$fuser = $row['f_username'];
$euser = $row['e_username'];
$title = $row['title'];
$pri = $row['price'];
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
	<link rel="stylesheet" href="fprofile.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">

	<style>
		body {
			padding-top: 3%;
			margin: 0;
			font-family: 'Josefin Sans', sans-serif;
		}
		.card {
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			background: #fff;
			margin-top: 20px;
		}
		.form-container {
			border: 1px solid #ccc;
			padding: 15px;
			border-radius: 10px;
		}
		.stars {
			cursor: pointer;
		}
		.star {
			font-size: 24px;
			margin: 0 5px;
		}
		.star.filled {
			color: gold;
		}
	</style>
</head>
<body>

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

<div class="container">
	<div class="alert alert-warning text-center mt-3" role="alert">
		<h4>"Please Feel Free To Share Your Thoughts. Your Speech Is Secure With Us. Please use the comment section to share any thoughts you may have about the freelancer. <br>We're going to do something about it."</h4>
	</div>

	<!-- Form Section -->
	<div class="row">
		<div class="col-lg-3"></div>
		<div class="col-lg-6 form-container">
			<h2 class="text-center">Say What's In Your Mind!</h2><br>
			<div id="success-message" class="alert alert-success text-center mt-3" role="alert" style="display: none;">
				<h4>Review submitted successfully!</h4>
			</div>
			<form action="deposit.php" method="POST" id="gandu" class="form-container">
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label for="ename">Employer Name:</label>
							<input type="text" class="form-control" id="ename" value="<?= $ename ?>" disabled>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label for="eusername">Employer Username:</label>
							<input type="text" class="form-control" id="eusername" value="<?= $euser ?>" disabled>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label for="fname">Freelancer Name:</label>
							<input type="text" class="form-control" id="fname" value="<?= $fname ?>" disabled>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label for="fusername">Freelancer Username:</label>
							<input type="text" class="form-control" id="fusername" value="<?= $fuser ?>" disabled>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label for="jobname">Job Title:</label>
							<input type="text" class="form-control" id="jobname" value="<?= $title ?>" disabled>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label for="bamount">Bid Amount:</label>
							<input type="number" class="form-control" id="bamount" value="<?= $pri ?>" disabled>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<div class="form-group">
							<label for="bamount">Leave a review:</label>
							<div class="stars" onclick="rate(event)">
								<span class="star" data-value="5">★</span>
								<span class="star" data-value="4">★</span>
								<span class="star" data-value="3">★</span>
								<span class="star" data-value="2">★</span>
								<span class="star" data-value="1">★</span>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<div class="form-group">
							<label for="comment">Comment (If You want to report the freelancer):</label>
							<textarea class="form-control" rows="5" id="comment" placeholder="Share your thoughts here..."></textarea>
						</div>
					</div>
				</div>

				<div class="text-center">
					<button type="submit" class="btn btn-primary btn-lg">Submit Review</button>
				</div>
			</form>
		</div>
		<div class="col-lg-3"></div>
	</div>
</div>

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

<script type="text/javascript" src="jquery/jquery-3.2.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
	// Function to handle star rating selection
	var rating = 0;
	function rate(event) {
		if (event.target.classList.contains('star')) {
			const selectedValue = event.target.getAttribute('data-value');
			const stars = document.querySelectorAll('.star');
			stars.forEach(star => star.classList.remove('filled'));
			rating = 6 - selectedValue;
			for (let i = 5; i >= selectedValue; i--) {
				const star = document.querySelector(`.star[data-value="${i}"]`);
				star.classList.add('filled');
			}
		}
	}

	// Handle review submission
	$('.btn-primary').click(function (e) {
		e.preventDefault();
		var data = {
			job_id: '<?= $job_id ?>',
			rating: rating,
			review: $('#comment').val()
		};
		$.ajax({
			type: "POST",
			url: "./review1.php",
			data: data,
			success: function (response) {
				var data = JSON.parse(response);
				if (data == true) {
					$('#success-message').show();
					$('#gandu').hide();
					setTimeout(function () {
						location.replace('./employerProfile.php');
					}, 5000);
				}
			}
		});
	});
</script>

</body>
</html>