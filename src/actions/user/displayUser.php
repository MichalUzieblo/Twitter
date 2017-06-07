<?php
session_start();
require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";
require_once dirname(__FILE__) . "/../../classes/Tweet.php";
require_once dirname(__FILE__) . "/../../classes/Comment.php";
require_once dirname(__FILE__) . "/../log/isLogged.php";

$isSetId = FALSE;

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['getUserId'])
        && !empty($_GET['getUsername'])) {
    
    $isSetId = TRUE;
    $user2 = Users::loadUserById($conn, $_GET['getUserId']);
}

$title = 'Twitter - Display User';
require_once dirname(__FILE__) . "/../../html/htmlHeader.php";
?>
<center>
    <form action="../message/sendMessage.php" method="post" role="form">
    <div class="form-group">
        <label for="">Page 
            <?php  
            if ($isSetId) {
                echo $user2->getUsername(); 
            }
            ?></label>
    </div>                
    <button type="submit" name="id" class="btn btn-success" 
        <?php  
        if ($isSetId) {
            echo 'value=' . $user2->getId(); 
        }
        ?>>Send Message to this User</button>
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

if ($isLogged && $isSetId) {
    $tweetsTable = Tweet::loadAllTweetsByUserId($conn, $user2->getId());
    foreach ($tweetsTable as $value) {
        $id = $value ->getId();
        $userId = $value ->getUserId();
        $text = $value ->getText();
        $creationDate = $value ->getCreationDate();

        $loadeduser = Users::loadUserById($conn, $userId);
        $username = $loadeduser ->getUsername();

        echo "User: $username<br>";
        echo "Creation date: $creationDate<br>";
        echo "Text: <a href=\"../board/postBoard.php?postId=$id\">$text</a><br>";

        $commentsTable = Comment::loadAllCommentByPostId($conn, $id);
        $nrOfComments = count($commentsTable);

        echo 'Number of comments: ' . $nrOfComments . '<br><br>';
    }
}

require_once dirname(__FILE__) . "/../../html/htmlFooter.php";