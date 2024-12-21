<?php 
/**
 * message.php
 * 
 * This file handles the messaging functionality of the application.
 * It includes features such as displaying conversations, sending messages,
 * searching through inbox and sent messages, and managing user profiles.
 * 
 * The script checks if a user is logged in, fetches their messages,
 * and allows them to interact with other users through a chat interface.
 * 
 */

// Include server connection
include('server.php');

// Check if user is logged in
if(isset($_SESSION["Username"])){
    $username = $_SESSION["Username"];
    
    // Fetch the user's name based on their type
    if ($_SESSION["Usertype"] == 1) {
        $sqlName = "SELECT name FROM freelancer WHERE username='$username'";
    } else {
        $sqlName = "SELECT name FROM employer WHERE username='$username'";
    }
    $resultName = $conn->query($sqlName);
    $userName = $resultName->fetch_assoc()['name']; // Get the user's name

    // Set profile links based on user type
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
    $userName = ""; // Default name if not logged in
}

// Fetch unread messages
$sql = "SELECT * FROM message WHERE receiver='$username' and status=0 ORDER BY timestamp DESC";
$result = $conn->query($sql);
$f = 0;

// Handle search requests
if(isset($_POST["sr"])){
    $t = $_POST["sr"];
    $sql = "SELECT * FROM freelancer WHERE username='$t'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $_SESSION["f_user"] = $t;
        header("location: viewFreelancer.php");
    } else {
        $sql = "SELECT * FROM employer WHERE username='$t'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $_SESSION["e_user"] = $t;
            header("location: viewEmployer.php");
        }
    }
}

// Handle inbox and sent message searches
if(isset($_POST["s_inbox"])){
    $t = $_POST["s_inbox"];
    $sql = "SELECT * FROM message WHERE receiver='$username' and sender='$t' ORDER BY timestamp DESC";
    $result = $conn->query($sql);
    $f = 0;
}

if(isset($_POST["s_sm"])){
    $t = $_POST["s_sm"];
    $sql = "SELECT * FROM message WHERE sender='$username' and receiver='$t' ORDER BY timestamp DESC";
    $result = $conn->query($sql);
    $f = 1;
}

if(isset($_POST["inbox"])){
    $sql = "SELECT * FROM message WHERE receiver='$username' ORDER BY timestamp DESC";
    $result = $conn->query($sql);
    $f = 0;
}

if(isset($_POST["sm"])){
    $sql = "SELECT * FROM message WHERE sender='$username' ORDER BY timestamp DESC";
    $result = $conn->query($sql);
    $f = 1;
}

// Handle message reply
if (isset($_POST["rep"])) {
    $t = $_POST["rep"];
    $messageId = $_POST["message_id"];

    // Update the status of the message to 1 in the database
    $updateStatusQuery = "UPDATE message SET status = 1 WHERE message_id = '$messageId'";
    $conn->query($updateStatusQuery);

    $_SESSION["msgRcv"] = $t;
    header("location: sendMessage.php");
}

// Count unread messages
$unreadMessagesCount = 0;
if(isset($_SESSION["Username"])) {
    $username = $_SESSION["Username"];
    $sqlCount = "SELECT COUNT(*) as count FROM message WHERE receiver='$username' AND status=0";
    $resultCount = $conn->query($sqlCount);
    if($resultCount) {
        $row = $resultCount->fetch_assoc();
        $unreadMessagesCount = $row['count'];
    }
}

// Handle chat with a specific user
if(isset($_GET["chat_with"])) {
    $chat_with = $conn->real_escape_string($_GET["chat_with"]);
    
    // Get all messages between the two users, ordered by timestamp DESC for recent first
    $sql = "SELECT * FROM message 
            WHERE (sender='$username' AND receiver='$chat_with')
            OR (sender='$chat_with' AND receiver='$username')
            ORDER BY timestamp DESC";
    $result = $conn->query($sql);
    
    // Mark messages as read
    $updateSql = "UPDATE message 
                  SET status = 1 
                  WHERE sender='$chat_with' 
                  AND receiver='$username' 
                  AND status = 0";
    $conn->query($updateSql);
}

// Get list of unique conversations
$conversationsSql = "SELECT DISTINCT 
    CASE 
        WHEN sender = '$username' THEN receiver
        ELSE sender 
    END as contact,
    MAX(timestamp) as last_message_time,
    MAX(CASE WHEN receiver = '$username' AND status = 0 THEN 1 ELSE 0 END) as has_unread
    FROM message 
    WHERE sender = '$username' OR receiver = '$username'
    GROUP BY contact
    ORDER BY last_message_time DESC";
$conversationsResult = $conn->query($conversationsSql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Message</title>
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
        /* Custom styles for the message page */
        body {
            padding-top: 3%;
            margin: 0;
            font-family: 'Josefin Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1;
            padding: 1% 3% 1% 3%;
        }
        
        .footer-section {
            margin-top: auto;
            width: 100%;
            background: #222;
            color: #fff;
            padding: 40px 0 0 0;
        }

        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); 
            background: #fff;
        }

        .conversations-list {
            border-right: 1px solid #ddd;
            height: 600px;
            overflow-y: auto;
        }

        .conversation-item {
            display: block;
            padding: 15px;
            border-bottom: 1px solid #eee;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .conversation-item:hover,
        .conversation-item.active {
            background-color: #f8f9fa;
            text-decoration: none;
        }

        .contact {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .unread-badge {
            width: 10px;
            height: 10px;
            background-color: #007bff;
            border-radius: 50%;
            margin-left: auto;
        }

        .chat-container {
            height: 600px;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .messages-wrapper {
            flex-grow: 1;
            overflow-y: auto;
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .message {
            max-width: 70%;
            margin: 5px 0;
        }

        .message.incoming {
            align-self: flex-start;
        }

        .message.outgoing {
            align-self: flex-end;
        }

        .message-content {
            padding: 10px 15px;
            border-radius: 15px;
            background-color: #f1f0f0;
            position: relative;
        }

        .message.outgoing .message-content {
            background-color: #007bff;
            color: white;
        }

        .message-time {
            font-size: 0.75rem;
            color: #666;
            margin-top: 5px;
        }

        .message.outgoing .message-time {
            color: #dee2e6;
        }

        .message-input {
            padding: 15px;
            border-top: 1px solid #ddd;
        }

        .message-form {
            margin-bottom: 0;
        }

        .select-conversation {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #666;
        }

        .select-conversation i {
            font-size: 3rem;
            margin-bottom: 15px;
        }
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
                    <li><a href="allEmployer.php">Browse Employers</a></li>
                    <li class="dropdown" style="background:#000;padding:0 20px 0 20px;">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <span class="glyphicon glyphicon-user"></span> 
                            <?php echo htmlspecialchars($userName); ?>
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
                                <?php if ($unreadMessagesCount > 0): ?>
                                    <span class="badge"><?php echo $unreadMessagesCount; ?></span>
                                <?php endif; ?>
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
    <div class="main-content">
        <div class="row">
            <!-- Column 1 -->
            <div class="col-lg-9">
                <!-- Freelancer Profile Details -->
                <div class="card" style="padding:20px 20px 5px 20px;margin-top:20px">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3><i class="fas fa-comments"></i> Messages</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <!-- Conversations List -->
                                <div class="col-md-4 conversations-list">
                                    <?php while($conv = $conversationsResult->fetch_assoc()): ?>
                                        <?php 
                                        $contact = $conv['contact'];
                                        // Fetch the name of the contact
                                        $sqlContactName = "SELECT name FROM freelancer WHERE username='$contact' UNION SELECT name FROM employer WHERE username='$contact'";
                                        $resultContactName = $conn->query($sqlContactName);
                                        $contactName = $resultContactName->fetch_assoc()['name']; // Get the contact's name
                                        $isActive = isset($_GET['chat_with']) && $_GET['chat_with'] === $contact;
                                        ?>
                                        <a href="?chat_with=<?php echo urlencode($contact); ?>" 
                                           class="conversation-item <?php echo $isActive ? 'active' : ''; ?>">
                                            <div class="contact">
                                                <i class="fas fa-user-circle"></i>
                                                <span class="contact-name"><?php echo htmlspecialchars($contactName); ?></span>
                                                <?php if($conv['has_unread']): ?>
                                                    <span class="unread-badge"></span>
                                                <?php endif; ?>
                                            </div>
                                        </a>
                                    <?php endwhile; ?>
                                </div>

                                <!-- Chat Messages -->
                                <div class="col-md-8 chat-container">
                                    <?php if(isset($_GET["chat_with"])): ?>
                                        <div class="chat-header">
                                            <h4>
                                                <i class="fas fa-user-circle"></i>
                                                <?php 
                                                    $chat_with = $conn->real_escape_string($_GET["chat_with"]);
                                                    // Fetch the full name of the user being chatted with
                                                    $sqlFullName = "SELECT name FROM freelancer WHERE username='$chat_with' UNION SELECT name FROM employer WHERE username='$chat_with'";
                                                    $resultFullName = $conn->query($sqlFullName);
                                                    $fullName = $resultFullName->fetch_assoc()['name']; // Get the full name
                                                    echo htmlspecialchars($fullName); // Display the full name
                                                ?>
                                            </h4>
                                        </div>
                                        <div class="messages-wrapper" id="messagesContainer">
                                            <?php 
                                            // Store messages in an array first
                                            $messages = array();
                                            while($row = $result->fetch_assoc()) {
                                                $messages[] = $row;
                                            }
                                            // Reverse the array to show newest messages at the bottom
                                            $messages = array_reverse($messages);
                                            
                                            // Display messages
                                            foreach($messages as $row): 
                                                $isOutgoing = $row['sender'] === $username;
                                                $messageClass = $isOutgoing ? 'outgoing' : 'incoming';
                                            ?>
                                                <div class="message <?php echo $messageClass; ?>">
                                                    <div class="message-content">
                                                        <?php echo htmlspecialchars($row['msg']); ?>
                                                        <div class="message-time">
                                                            <?php 
                                                            $msgDate = new DateTime($row['timestamp']);
                                                            echo $msgDate->format('M d, g:i A'); 
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <!-- Message Input -->
                                        <div class="message-input">
                                            <form method="POST" action="sendMessage.php" class="message-form">
                                                <input type="hidden" name="msgTo" value="<?php echo htmlspecialchars($_GET['chat_with']); ?>">
                                                <div class="input-group">
                                                    <input type="text" name="msgBody" class="form-control" placeholder="Type your message..." required>
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-primary" type="submit" name="send">
                                                            <i class="fas fa-paper-plane"></i> Send
                                                        </button>
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <div class="select-conversation">
                                            <i class="fas fa-comments"></i>
                                            <p>Select a conversation to start chatting</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Freelancer Profile Details -->
            </div>
            <!-- End Column 1 -->

            <!-- Column 2 -->
            <div class="col-lg-3">
                <!-- Main profile card -->
                <div class="card" style="padding:20px 20px 5px 20px;margin-top:20px">
                    <form action="message.php" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="s_inbox" placeholder="Search Inbox">
                            <center><button type="submit" class="btn btn-info">Search Inbox</button></center>
                        </div>
                    </form>
                    <form action="message.php" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="s_sm" placeholder="Search Sent Messages">
                            <center><button type="submit" class="btn btn-info">Search Sent Messages</button></center>
                        </div>
                    </form>
                    <form action="message.php" method="post">
                        <div class="form-group">
                            <center><button type="submit" name="inbox" class="btn btn-warning">Inbox Messages</button></center>
                        </div>
                    </form>
                    <form action="message.php" method="post">
                        <div class="form-group">
                            <center><button type="submit" name="sm" class="btn btn-warning">Sent Messages</button></center>
                        </div>
                    </form>
                </div>
                <!-- End Main profile card -->
            </div>
            <!-- End Column 2 -->
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
    <script>
        // Scroll to bottom of messages when page loads
        document.addEventListener('DOMContentLoaded', function() {
            var messagesContainer = document.getElementById('messagesContainer');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        });
    </script>
</body>
</html>