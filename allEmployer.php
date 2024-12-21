<?php
// File: allEmployer.php
// Description: Page to display all employers
// Features:
// - Displays a list of employers
// - Allows searching by username, name, and email
// - Redirects to view employer details upon selection

include('server.php'); // Include server-side logic for database connection

if (isset($_SESSION["Username"])) { // Check if the user is logged in
    $username = $_SESSION["Username"]; // Get the username from the session
    if ($_SESSION["Usertype"] == 1) { // Determine user type and set appropriate links for freelancers
        $linkPro = "freelancerProfile.php";
        $linkEditPro = "editFreelancer.php";
        $linkBtn = "applyJob.php";
        $textBtn = "Apply for this job";
    } else { // Set appropriate links for employers
        $linkPro = "employerProfile.php";
        $linkEditPro = "editEmployer.php";
        $linkBtn = "editJob.php";
        $textBtn = "Edit the job offer";
    }
} else {
    $username = ""; // Default username if not logged in
    // header("location: index.php"); // Uncomment to redirect if not logged in
}

if (isset($_POST["e_user"])) { // Handle form submission for employer selection
    $_SESSION["e_user"] = $_POST["e_user"]; // Store selected employer in session
    header("location: viewEmployer.php"); // Redirect to view employer page
}

// Initialize the base query to fetch employers
$sql = "SELECT * FROM employer";
$params = array(); // Array to hold query parameters
$types = ""; // String to hold parameter types

// Handle search queries for employers
if (isset($_POST["s_username"]) && !empty($_POST["s_username"])) { // Search by username
    $sql = "SELECT * FROM employer WHERE username LIKE ?";
    $searchTerm = "%" . $_POST["s_username"] . "%"; // Prepare search term
    $params[] = $searchTerm; // Add search term to parameters
    $types .= "s"; // Append type for string
}

if (isset($_POST["s_name"]) && !empty($_POST["s_name"])) { // Search by name
    $sql = "SELECT * FROM employer WHERE Name LIKE ?";
    $searchTerm = "%" . $_POST["s_name"] . "%"; // Prepare search term
    $params[] = $searchTerm; // Add search term to parameters
    $types .= "s"; // Append type for string
}

if (isset($_POST["s_email"]) && !empty($_POST["s_email"])) { // Search by email
    $sql = "SELECT * FROM employer WHERE email LIKE ?";
    $searchTerm = "%" . $_POST["s_email"] . "%"; // Prepare search term
    $params[] = $searchTerm; // Add search term to parameters
    $types .= "s"; // Append type for string
}

// Ensure the connection is established
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the query
$stmt = $conn->prepare($sql); // Prepare the SQL statement
if (!$stmt) {
    die("Prepare failed: " . $conn->error); // Check for errors in preparation
}

if (!empty($params)) { // Bind parameters if any
    $stmt->bind_param($types, ...$params);
}
$stmt->execute(); // Execute the prepared statement
$result = $stmt->get_result(); // Get the result set

?>
<!DOCTYPE html>
<html>

<head>
    <title>All Employer</title>
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
    <style>
        /* Add any additional styles here */
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
                    <li class="active"><a href="allEmployer.php">Browse Employers</a></li>
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

    <!-- Main body -->
    <div style="padding:1% 3% 1% 3%;">
        <div class="row">
            <!-- Column 1 -->
            <div class="col-lg-9">
                <!-- Employer Listings Table -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Available Employers</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr class="bg-primary">
                                        <th>Username</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Company</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) { // Check if there are results
                                        while ($row = $result->fetch_assoc()) { // Fetch each employer
                                            echo '
                                    <tr>
                                        <form action="allEmployer.php" method="post">
                                            <input type="hidden" name="e_user" value="' . htmlspecialchars($row["username"]) . '">
                                            <td>
                                                <button type="submit" class="btn btn-link">
                                                    ' . htmlspecialchars($row["username"]) . '
                                                </button>
                                            </td>
                                            <td>' . htmlspecialchars($row["Name"]) . '</td>
                                            <td>' . htmlspecialchars($row["email"]) . '</td>
                                            <td><span class="label label-info">' . htmlspecialchars($row["company"]) . '</span></td>
                                        </form>
                                    </tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="4" class="text-center">No employers found</td></tr>'; // No results found
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column 2 -->
            <div class="col-lg-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Search Employers</h3>
                    </div>
                    <div class="panel-body">
                        <!-- Search by Username -->
                        <form action="allEmployer.php" method="post" class="search-form">
                            <div class="form-group">
                                <label for="s_username">Search by Username</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="s_username" name="s_username" placeholder="Enter username...">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </form>

                        <!-- Search by Name -->
                        <form action="allEmployer.php" method="post" class="search-form">
                            <div class="form-group">
                                <label for="s_name">Search by Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="s_name" name="s_name" placeholder="Enter name...">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </form>

                        <!-- Search by Email -->
                        <form action="allEmployer.php" method="post" class="search-form">
                            <div class="form-group">
                                <label for="s_email">Search by Email</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="s_email" name="s_email" placeholder="Enter email...">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </form>

                        <!-- Show All Button -->
                        <form action="allEmployer.php" method="post">
                            <button type="submit" class="btn btn-block btn-info">
                                <i class="fas fa-sync"></i> Show All Employers
                            </button>
                        </form>
                    </div>
                </div>
            </div>
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