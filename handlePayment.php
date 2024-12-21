<?php
// handlePayment.php
// This file handles payment processing for hiring freelancers and updating their balances.

include('server.php');

// Debugging output
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Form submitted!<br>";
    echo "f_hire: " . htmlspecialchars($_POST['f_hire']) . "<br>";
    echo "f_price: " . htmlspecialchars($_POST['f_price']) . "<br>";
    echo "job_id: " . htmlspecialchars($_POST['job_id']) . "<br>";
    // Continue with the rest of your code...
}

// Check if the user is logged in
if (isset($_SESSION["Username"])) {
	$username = $_SESSION["Username"];
} else {
	$username = "";
}

// Process hiring a freelancer
if (isset($_POST["f_hire"])) {
	$payment = true;
	$f_hire = $_POST["f_hire"];
	$f_price = $_POST["f_price"];
	$job_id = $_POST["job_id"];

	// Get employer's balance
	$employer_balance_query = "SELECT balance FROM employer WHERE username = '$username'";
	$employer_balance_result = $conn->query($employer_balance_query);
	$employer_balance_row = $employer_balance_result->fetch_assoc();
	$employer_balance = $employer_balance_row['balance'];

	// Check if the employer has sufficient balance
	if ($employer_balance >= $f_price) {
		// Deduct balance
		$deduct_balance_query = "UPDATE employer SET balance = balance - $f_price WHERE username = '$username'";
		$deduct_balance_result = $conn->query($deduct_balance_query);

		if ($deduct_balance_result === true) {
			// Insert selected freelancer
			$insert_selected_query = "INSERT INTO selected (f_username, job_id, e_username, price, valid, deposit) VALUES ('$f_hire', '$job_id', '$username', '$f_price', 1, 1)";
			$insert_selected_result = $conn->query($insert_selected_query);

			if ($insert_selected_result === true) {
				// Delete application
				$delete_apply_query = "DELETE FROM apply WHERE job_id='$job_id'";
				$delete_apply_result = $conn->query($delete_apply_query);

				if ($delete_apply_result === true) {
					// Update job offer status
					$update_job_offer_query = "UPDATE job_offer SET valid=0 WHERE job_id='$job_id'";
					$update_job_offer_result = $conn->query($update_job_offer_query);

					if ($update_job_offer_result === true) {
						sendmess($username, $f_hire, "You are selected for job id: $job_id", $conn);
						// Display success message
						echo "<script>alert('Freelancer hired! Bid amount deducted from your balance & will be added to reserve!.'); location.replace('./jobDetails.php')</script>";
					}
				}
			}
		} else {
			echo "<script>alert('Error updating balance');</script>";
		}
	} else {
		echo "<script>alert('Insufficient balance');</script>";
	}
}

// Process payment to freelancer
if (isset($_POST["f_done"])) {
	$f_price = $_POST["f_price"];
	$job_id = $_POST["job_id"];
	$f_name = $_POST["f_done"];

	// Update freelancer's balance
	$sql = "UPDATE freelancer SET balance = (balance + $f_price) WHERE username = '$f_name'";
	if ($conn->query($sql) == true) {
		// Mark the selected job as invalid
		$sql = "UPDATE `selected` SET `valid` = 0 WHERE job_id = '$job_id'";
		if ($conn->query($sql) == true) {
			sendmess($username, $f_name, "You have received payment $f_price tk for job id: $job_id", $conn);
			header("location: jobDetails.php");
		}
	}
}
?>
