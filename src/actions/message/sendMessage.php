<?php
session_start();
require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";
require_once dirname(__FILE__) . "/../../classes/Tweet.php";
require_once dirname(__FILE__) . "/../../classes/Comment.php";
require_once dirname(__FILE__) . "/../../classes/Message.php";
require_once dirname(__FILE__) . "/../log/isLogged.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['id']) && !empty($_SESSION['id'])) {
    
    $_SESSION['user2id'] = $_POST['id'];
    
    if ($_POST['id'] == $_SESSION['id']) {        
        header("Location: errorMessage.php");
    }
}

$isSend = FALSE;
$isError = FALSE;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message']) && $isLogged
        && isset($_SESSION['user2id'])) {
    
    $text = trim($_POST['message']);
    $messageReceiverId = $_SESSION['user2id']; 
    $messageSenderId = $_SESSION['id'];
        
    if (!empty($_POST['message']) && !empty($_SESSION['user2id'])) {
        $message = new Message();
        $message ->setCreationDate();
        $message ->setMessageReceiverId($conn, $messageReceiverId);
        $message ->setMessageSenderId($conn, $messageSenderId);
        if ($message ->setText($text) == NULL) {
            $isError = TRUE;
        } else {
            if ($message ->saveToDB($conn) === TRUE) {
                $isSend = TRUE;
            }
            $isError = TRUE;
        }
        
    } 
}

$title = 'Twitter - Send Message';
require_once dirname(__FILE__) . "/../../html/htmlHeader.php";
?>
            
<center>
<?php

if ($isSend) {
    echo 'Wysłano';
} elseif ($isError) {
    echo 'za długi text';
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

<?php
require_once dirname(__FILE__) . "/../../html/htmlFooter.php";