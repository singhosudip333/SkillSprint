<?php
/**
 * editJob.php
 * 
 * This file allows users to edit job offers. It retrieves job details from the database,
 * displays them in a form, and updates the job offer upon submission.
 */

// Include server configuration
include('server.php');

// Check if the user is logged in
if (isset($_SESSION["Username"])) {
	$username = $_SESSION["Username"];
} else {
	$username = "";
	// Uncomment the line below to redirect if not logged in
	// header("location: index.php");
}

// Check if job ID is set
if (isset($_SESSION["job_id"])) {
	$job_id = $_SESSION["job_id"];
} else {
	$job_id = "";
	// Uncomment the line below to redirect if job ID is not set
	// header("location: index.php");
}

// Fetch job offer details from the database
$sql = "SELECT * FROM job_offer WHERE job_id='$job_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	// Output data of each row
	while ($row = $result->fetch_assoc()) {
		$title = $row["title"];
		$type = $row["type"];
		$description = $row["description"];
		$budget = $row["budget"];
		$skills = $row["skills"];
		$special_skill = $row["special_skill"];
	}
} else {
	echo "0 results";
}

// Handle form submission for editing job
if (isset($_POST["editJob"])) {
	$title = test_input($_POST["title"]);
	$type = test_input($_POST["type"]);
	$description = test_input($_POST["description"]);
	$budget = test_input($_POST["budget"]);
	$skills = test_input($_POST["skills"]);
	$special_skill = test_input($_POST["special_skill"]);

	// Update job offer in the database
	$sql = "UPDATE job_offer SET title='$title', type='$type', description='$description', budget='$budget', skills='$skills', special_skill='$special_skill', e_username='$username', valid=1 WHERE job_id='$job_id'";
	
	$result = $conn->query($sql);
	if ($result == true) {
		header("location: jobDetails.php");
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit Job Offer</title>
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

<style>
	body{padding-top: 3%;margin: 0;font-family: 'Josefin Sans', sans-serif;}
	.card{box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); background:#fff}
</style>

</head>
<body>

<!--Navbar menu-->
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
			        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
			            <span class="glyphicon glyphicon-user"></span> <?php echo htmlspecialchars($username); ?>
			        </a>
			        <ul class="dropdown-menu list-group">
			        	<a href="employerProfile.php" class="list-group-item"><span class="glyphicon glyphicon-home"></span>  View profile</a>
			          	<a href="editEmployer.php" class="list-group-item"><span class="glyphicon glyphicon-inbox"></span>  Edit Profile</a>
					  	<a href="message.php" class="list-group-item"><span class="glyphicon glyphicon-envelope"></span>  Messages</a> 
					  	<a href="logout.php" class="list-group-item"><span class="glyphicon glyphicon-ok"></span>  Logout</a>
			        </ul>
			    </li>
			</ul>
		</div>		
	</div>	
</nav>
<!--End Navbar menu-->


<div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="edit-job-container">
                    <div class="page-header">
                        <h2 class="text-center">Edit Job Offer</h2>
                        <hr class="divider">
                    </div>

                    <form id="registrationForm" method="post" class="form-horizontal">
                        <!-- Job Information Section -->
                        <div class="section-group">
                            <h4 class="section-title">Job Information</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Job Title</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="title" value="<?php echo $title; ?>" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Job Type</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="type" value="<?php echo $type; ?>" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Job Description</label>
                                <div class="col-sm-5">
                                    <textarea class="form-control" name="description" rows="4" required><?php echo $description; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Budget</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="budget" value="<?php echo $budget; ?>" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Required Skills</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="skills" value="<?php echo $skills; ?>" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Special Requirement</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="special_skill" value="<?php echo $special_skill; ?>" />
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" name="editJob" class="btn btn-info btn-lg">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


<!--Footer-->
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
            title: {
                validators: {
                    notEmpty: {
                        message: 'The title is required and cannot be empty'
                    }
                }
            },
            type: {
                validators: {
                    notEmpty: {
                        message: 'The type is required and cannot be empty'
                    }
                }
            },
            description: {
                validators: {
                    notEmpty: {
                        message: 'The description is required and cannot be empty'
                    }
                }
            },
            budget: {
                validators: {
                    notEmpty: {
                        message: 'The budget is required and cannot be empty'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'The budget must be a valid number'
                    }
                }
            },
            skills: {
                validators: {
                    notEmpty: {
                        message: 'The skills are required and cannot be empty'
                    }
                }
            }
        }
    });
});
</script>

</body>
</html>