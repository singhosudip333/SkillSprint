<?php
// hire_process.php
// This file handles the hiring process, including updating employer balance, 
// inserting into the selected table, and managing job applications.

include('server.php');

// Check if the request method is POST and required parameters are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["f_hire"], $_POST["f_price"], $_POST["username"], $_POST["job_id"])) {
    $f_hire = $_POST["f_hire"];
    $f_price = $_POST["f_price"];
    $username = $_POST["username"];
    $job_id = $_POST["job_id"];

    // Fetch the current employer balance
    $getBalanceQuery = "SELECT `balance` FROM `employer` WHERE `username`='$username'";
    $balanceResult = $conn->query($getBalanceQuery);

    if ($balanceResult->num_rows > 0) {
        $balanceRow = $balanceResult->fetch_assoc();
        $balance = floatval($balanceRow['balance']); // Convert to float if necessary
    } else {
        echo "Error fetching employer balance";
        exit();
    }

    // Update employer balance
    $updateBalanceQuery = "UPDATE `employer` SET `balance` = `balance` - $f_price WHERE `username` = '$username'";
    if (!$conn->query($updateBalanceQuery)) {
        echo "Error updating employer balance: " . $conn->error;
        exit();
    }

    // Insert into selected table
    $insertQuery = "INSERT INTO selected (f_username, job_id, e_username, price, valid, deposit) VALUES ('$f_hire', '$job_id', '$username', '$f_price', 1, 1)";
    if (!$conn->query($insertQuery)) {
        echo "Error inserting into selected table: " . $conn->error;
        exit();
    }

    // Delete from apply table
    $deleteApplyQuery = "DELETE FROM apply WHERE job_id = '$job_id'";
    if (!$conn->query($deleteApplyQuery)) {
        echo "Error deleting from apply table: " . $conn->error;
        exit();
    }

    // Update job_offer table
    $updateJobQuery = "UPDATE job_offer SET valid = 0 WHERE job_id = '$job_id'";
    if (!$conn->query($updateJobQuery)) {
        echo "Error updating job_offer table: " . $conn->error;
        exit();
    }

    // Success message
    echo "Job confirmed!\nPayment deducted: $f_price\nNew Balance: " . ($balance - $f_price);
} else {
    echo "Invalid request";
}
?>
