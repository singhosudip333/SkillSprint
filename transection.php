<?php
// transection.php
// This file handles the payment confirmation process for freelancers and employers.

session_start(); // Start the session to access session variables

// Check if the user is logged in
if (isset($_SESSION["Username"])) {
    $username = $_SESSION["Username"];
} else {
    $username = "";
    // Uncomment the line below to redirect unauthenticated users
    // header("location: index.php");
}

// Process payment confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_payment"])) {
    $jobAmount = $_POST["bid"];
    $freelancerName = $_POST["f_done"];
    $jobid = $_POST['jobid'];

    // Fetch employer balance
    $query = "SELECT balance FROM employer WHERE username = '$employerUsername'";
    
    // Check if employer has sufficient balance
    if ($employerBalance >= $jobAmount) {
        $newBalance = $employerBalance - $jobAmount;
        $updateQuery = "UPDATE employer SET balance = $newBalance WHERE username = '$employerUsername'";
        
        // Update freelancer balance
        $freelancerQuery = "SELECT balance FROM freelancer WHERE username = '$freelancerName'";
        $newFreelancerBalance = $freelancerBalance + $jobAmount;
        $updateFreelancerQuery = "UPDATE freelancer SET balance = $newFreelancerBalance WHERE username = '$freelancerName'";
    } else {
        echo "Insufficient balance to make the payment.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <link rel="icon" href="./icon/shotcut_logo.png" type="image/x-icon">

    <!-- Add Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title text-center">Confirm Payment</h1>
                    <p>Freelancer: <?php echo htmlspecialchars($freelancerName); ?></p>
                    <p>Job Amount: <?php echo htmlspecialchars($jobAmount); ?></p>

                    <!-- Form to confirm payment -->
                    <form method="post" action="jobDetails.php" class="text-center">
                        <input type="hidden" name="freelancer_name" value="<?php echo htmlspecialchars($freelancerName); ?>">
                        <input type="hidden" name="job_amount" value="<?php echo htmlspecialchars($jobAmount); ?>">
                        <input type="hidden" name="jobid" value="<?php echo htmlspecialchars($jobid); ?>">
                        
                        <!-- Submit button to confirm payment -->
                        <button type="submit" class="btn btn-primary" name="confirm_payment">Confirm Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS CDN -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

