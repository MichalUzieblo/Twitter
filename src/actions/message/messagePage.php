<?php
session_start();
require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";
require_once dirname(__FILE__) . "/../../classes/Tweet.php";
require_once dirname(__FILE__) . "/../../classes/Comment.php";
require_once dirname(__FILE__) . "/../../classes/Message.php";
require_once dirname(__FILE__) . "/../log/isLogged.php";

$title = 'Twitter - Messages';
require_once dirname(__FILE__) . "/../../html/htmlHeader.php";
?>

<center>

<form action="../log/logOut.php" method="post" role="form">
    <button type="submit" value="logOut" name="logOut" class="btn btn-success">Log Out</button>
</form>
<form action="../board/mainBoard.php" method="post" role="form">
    <button type="submit" value="mainBoard" class="btn btn-success">Main Board</button>
</form>
<form action="../board/userBoard.php" method="post" role="form">
    <button type="submit" value="userBoard" class="btn btn-success">User Board</button>
</form>

</center>

<?php

if ($isLogged) {

    $sendMessageTable = Message::loadAllSendMessageByUserId($conn, $user->getId());
    echo '<h4>Send: </h4>';
    foreach ($sendMessageTable as $value) {
        $messageId = $value ->getId();
        $messageReceiverId = $value ->getMessageReceiverId();
        $text = $value ->getText();
        $creationDate = $value ->getCreationDate();
        $isRead = $value ->getIsRead();

        $loadeduser = Users::loadUserById($conn, $messageReceiverId);
        $usernameReceiver = $loadeduser ->getUsername();

        if ($isRead == 0) {
            echo '<b>';
        }
        echo "Receiver: $usernameReceiver<br>";
        echo "Creation date: $creationDate<br>";
        if (strlen($text) > 30) {
            $text = substr($text, 0, 30) . "... ";
        }
        echo "Text: <a href=\"singleMessage.php?messageId=$messageId&status=send&user=$usernameReceiver\">$text</a><br><br>";
        if ($isRead == 0) {
            echo '</b>';
        }
    }

    $receivedMessageTable = Message::loadAllReceiveMessageByUserId($conn, $user->getId());
    echo '<h4>Received: </h4>';
    foreach ($receivedMessageTable as $value) {
        $messageId = $value ->getId();
        $messageSenderId = $value ->getMessageSenderId();
        $text = $value ->getText();
        $creationDate = $value ->getCreationDate();
        $isRead = $value ->getIsRead();

        $loadeduser = Users::loadUserById($conn, $messageSenderId);
        $usernameSender = $loadeduser ->getUsername();

        if ($isRead == 0) {
            echo '<b>';
        }
        echo "Sender: $usernameSender<br>";
        echo "Creation date: $creationDate<br>";
        if (strlen($text) > 30) {
            $text = substr($text, 0, 30) . "... ";
        }
        echo "Text: <a href=\"singleMessage.php?messageId=$messageId&status=received&user=$usernameSender\">$text</a><br><br>";
        if ($isRead == 0) {
            echo '</b>';
        }
    }
}

require_once dirname(__FILE__) . "/../../html/htmlFooter.php";