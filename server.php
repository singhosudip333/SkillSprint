<?php
// server.php
// This file handles user registration and login for freelancers, employers, and admins.
// It connects to the database, processes form submissions, and manages session variables.

session_start();

// Create connection
$conn = new mysqli("localhost", "root", "", "project_2");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Initialize variables for user input
$username = $name = $email = $password = $contactNo = $birthdate = $address = "";

// Handle user registration
if (isset($_POST["register"])) {
    $username = test_input($_POST["username"]);
    $name = test_input($_POST["name"]);
    $email = test_input($_POST["email"]);
    $password = test_input($_POST["password"]);
    $repassword = test_input($_POST["repassword"]);
    $contactNo = test_input($_POST["contactNo"]);
    $gender = test_input($_POST["gender"]);
    $birthdate = test_input($_POST["birthdate"]);
    $address = test_input($_POST["address"]);
    $usertype = test_input($_POST["usertype"]);

    // Check if the username is already taken
    if ($usertype == "freelancer") {
        $sql = "SELECT * FROM freelancer, employer WHERE freelancer.username = '$username' OR employer.username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $_SESSION["errorMsg2"] = "The username is already taken";
        } else {
            unset($_SESSION["errorMsg2"]);
            $sql = "INSERT INTO freelancer (username, password, Name, email, contact_no, address, gender, birthdate) VALUES ('$username', '$password', '$name', '$email', '$contactNo', '$address', '$gender', '$birthdate')";
            $result = $conn->query($sql);
            if ($result == true) {
                $_SESSION["Username"] = $username;
                $_SESSION["Usertype"] = 1;
                header("location: freelancerProfile.php");
            }
        }
    } else {
        $sql = "SELECT * FROM freelancer, employer WHERE freelancer.username = '$username' OR employer.username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $_SESSION["errorMsg2"] = "The username is already taken";
        } else {
            unset($_SESSION["errorMsg2"]);
            $sql = "INSERT INTO employer (username, password, Name, email, contact_no, address, gender, birthdate) VALUES ('$username', '$password', '$name', '$email', '$contactNo', '$address', '$gender', '$birthdate')";
            $result = $conn->query($sql);
            if ($result == true) {
                $_SESSION["Username"] = $username;
                $_SESSION["Usertype"] = 2;
                header("location: employerProfile.php");
            }
        }
    }
}

// Handle user login
if (isset($_POST["login"])) {
    session_unset();
    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]);
    $usertype = test_input($_POST["usertype"]);

    // Check user type and validate credentials
    if ($usertype == "freelancer") {
        $sql = "SELECT * FROM freelancer WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $_SESSION["Username"] = $username;
            $_SESSION["Usertype"] = 1;
            unset($_SESSION["errorMsg"]);
            header("location: freelancerProfile.php");
        } else {
            $_SESSION["errorMsg"] = "username/password is incorrect";
        }
    } else if ($usertype == "employer") {
        $sql = "SELECT * FROM employer WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $_SESSION["Username"] = $username;
            $_SESSION["Usertype"] = 2;
            unset($_SESSION["errorMsg"]);
            header("location: employerProfile.php");
        } else {
            $_SESSION["errorMsg"] = "username/password is incorrect";
        }
    } else if ($usertype == "admin") {
        $sql = "SELECT * FROM admin WHERE username = '$username' AND pass = '$password'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $_SESSION["Username"] = $username;
            $_SESSION["Usertype"] = 3; // Adjust Usertype for admin
            unset($_SESSION["errorMsg"]);
            header("location: adminProfile.php");
        } else {
            $_SESSION["errorMsg"] = "username/password is incorrect";
        }
    }
}

// Handle error messages
if (isset($_SESSION["errorMsg"])) {
    $errorMsg = $_SESSION["errorMsg"];
    unset($_SESSION["errorMsg"]);
} else {
    $errorMsg = "";
}

if (isset($_SESSION["errorMsg2"])) {
    $errorMsg2 = $_SESSION["errorMsg2"];
    unset($_SESSION["errorMsg2"]);
} else {
    $errorMsg2 = "";
}

// Function to sanitize user input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to send messages
function sendmess($sender, $msgTo, $msgBody, $conn) {
    $sql = "INSERT INTO message (`sender`, `receiver`, `msg`, `timestamp`, `status`) VALUES ('$sender', '$msgTo', '$msgBody', NOW(), 0)";
    $result = $conn->query($sql);
}

// Close the database connection
// $conn->close();
?>