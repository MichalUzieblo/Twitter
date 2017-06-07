<?php
session_start();
require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";
require_once dirname(__FILE__) . "/../../classes/Tweet.php";
require_once dirname(__FILE__) . "/../../classes/Comment.php";
require_once dirname(__FILE__) . "/../../classes/Message.php";

$isLogged = FALSE;

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['messageId']) 
        && is_numeric($_GET['messageId']) && !empty($_GET['status'])
        && !empty($_GET['user'])) {
    
    $messageId = $_GET['messageId'];
    $status = $_GET['status'];
    $user = $_GET['user'];
}

if (!empty($_SESSION['hashed_password'] && !empty($_SESSION['password']))) {
     
    $hashed_password = $_SESSION['hashed_password'];
    $password = $_SESSION['password'];
    $checkPassword = password_verify($password, $hashed_password);

    if ($checkPassword) {
        $isLogged = TRUE;
    } else {
        header("Location: ../log/logIn.php");
    }
}  else {
        header("Location: ../log/logIn.php");
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Twitter - Message</title>
    <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            
        </div>
        
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <center>
            <form action="../board/mainBoard.php" method="post" role="form">
                <button type="submit" value="mainBoard" class="btn btn-success">Main Board</button>
            </form>
            <form action="../board/userBoard.php" method="post" role="form">
                <button type="submit" value="userBoard" class="btn btn-success">User Board</button>
            </form>
                <form action="messagePage.php" method="post" role="form">
                <button type="submit" value="messagePage" class="btn btn-success">Messages</button>
            </form>
            <form action="../log/logOut.php" method="post" role="form">
                <button type="submit" value="logOut" name="logOut" class="btn btn-success">Log Out</button>
            </form>
            <br>
            </center>
            <?php

            if ($isLogged) {
                
                $message = Message::loadMessageById($conn, $messageId);
                
                switch ($status) {
                    case 'send':
                       
                        echo '<h4>Message sent: </h4>';

                        $messageReceiverId = $message ->getMessageReceiverId();
                        $text = $message ->getText();
                        $creationDate = $message ->getCreationDate();

                        $loadeduser = Users::loadUserById($conn, $messageReceiverId);
                        $usernameReceiver = $loadeduser ->getUsername();
                        $_SESSION['getUserId'] = $messageReceiverId;

                        echo "Receiver: $usernameReceiver<br>";
                        echo "Creation date: $creationDate<br>";
                        echo "Text: <a href=\"singleMessage.php?messageId=$messageId&status=send&user=$usernameReceiver\">$text</a><br><br>";                        
                        break;
                    
                    case 'received':
                        
                        echo '<h4>Message received: </h4>';

                        $messageSenderId = $message ->getMessageSenderId();
                        $text = $message ->getText();
                        $creationDate = $message ->getCreationDate();
                        $isRead = $message ->setIsRead($conn, 1);
                        $message ->saveToDB($conn);

                        $loadeduser = Users::loadUserById($conn, $messageSenderId);
                        $usernameSender = $loadeduser ->getUsername();
                        $_SESSION['getUserId'] = $messageSenderId;

                        echo "Sender: $usernameSender<br>";
                        echo "Creation date: $creationDate<br>";
                        echo "Text: <a href=\"singleMessage.php?messageId=$messageId&status=received&user=$usernameSender\">$text</a><br><br>";

                        break;
                }
            }

            ?>
            
                    <form action="sendMessage.php" method="post" role="form">
                        <div class="form-group">
                         <button type="submit" value="reply">Reply</button>
                        </div>                 
                    </form>
            <?php
          
            $conn->close();
            $conn = null;                        
            ?>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        </div>
    </div>
</div>
</body>
</html>


