<?php
session_start();
require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";
require_once dirname(__FILE__) . "/../../classes/Tweet.php";
require_once dirname(__FILE__) . "/../../classes/Comment.php";
require_once dirname(__FILE__) . "/../../classes/Message.php";

$isLogged = FALSE;
if (!empty($_SESSION['hashed_password']) && !empty($_SESSION['password'])) {
    
    $hashed_password = $_SESSION['hashed_password'];
    $password = $_SESSION['password'];
    $checkPassword = password_verify($password, $hashed_password);

    if ($checkPassword) {
        $isLogged = TRUE;
    } else {
        header("Location: ../log/logIn.php");
    }
} else {
        header("Location: ../log/logIn.php");
}

ob_start();
$isAllData = FALSE;
if (!empty($_SESSION['getUserId']) && !empty($_SESSION['id'])) {
    $isAllData = TRUE;
    
    if ($_SESSION['getUserId'] == $_SESSION['id']) {
        header("Location: errorMessage.php");
    }
}
ob_end_flush();

$isSend = FALSE;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message']) && $isLogged
        && $isAllData) {
    
    $text = trim($_POST['message']);
    $messageReceiverId = $_SESSION['getUserId'];
    $messageSenderId = $_SESSION['id'];
        
    if (!empty($_POST['message'])) {
        $message = new Message();
        $message ->setCreationDate();
        $message ->setMessageReceiverId($conn, $messageReceiverId);
        $message ->setMessageSenderId($conn, $messageSenderId);
        $message ->setText($text);
        $message ->saveToDB($conn);
        $isSend = TRUE;
    } 
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Twitter - Send Message</title>
    <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            
        </div>
        
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            
            <center>
            <?php
            
            if ($isSend) {
                echo 'Wysłano';
                $conn->close();
                $conn = null;
            }
                        
            ?>
            <form action="" method="post" role="form">
                <div class="form-group">
                    <label for="">Message</label>
                    <input type="text" class="form-control" name="message" id="message"
                           placeholder="Message max 140 signs">
                </div>
                
                <button type="submit" value="send" class="btn btn-success">Send</button>
            </form>           
                       
                <form action="../log/logOut.php" method="post" role="form">
                <button type="submit" value="logOut" name="logOut" class="btn btn-success">Log Out</button>
            </form>
                <form action="../board/mainBoard.php" method="post" role="form">
                <button type="submit" value="mainBoard" class="btn btn-success">Main Board</button>
            </form>
            <br>
            </center>
            
            
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        </div>
    </div>
</div>
</body>
</html>




