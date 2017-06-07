<?php
session_start();
require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";
require_once dirname(__FILE__) . "/../../classes/Tweet.php";
require_once dirname(__FILE__) . "/../../classes/Comment.php";
require_once dirname(__FILE__) . "/../log/isLogged.php";

$title = 'Twitter - User Board';
require_once dirname(__FILE__) . "/../../html/htmlHeader.php";
?>

<center>
<form action="../user/editProfile.php" method="post" role="form">
    <div class="form-group">
        <label for="">Hello <?php echo $user->getUsername(); ?></label>
    </div>                
    <button type="submit" value="editProfile" class="btn btn-success">Edit profile</button>
</form>

<form action="../log/logOut.php" method="post" role="form">
    <button type="submit" value="logOut" name="logOut" class="btn btn-success">Log Out</button>
</form>
<form action="mainBoard.php" method="post" role="form">
    <button type="submit" value="mainBoard" class="btn btn-success">Main Board</button>
</form>
    </form>
    <form action="../message/messagePage.php" method="post" role="form">
    <button type="submit" value="messagePage" class="btn btn-success">Messages</button>
</form>
<br>
</center>
<?php

if ($isLogged) {
    $tweetsTable = Tweet::loadAllTweetsByUserId($conn, $_SESSION['id']);
    foreach ($tweetsTable as $value) {
        $id = $value ->getId();
        $userId = $value ->getUserId();
        $text = $value ->getText();
        $creationDate = $value ->getCreationDate();

        $loadeduser = Users::loadUserById($conn, $userId);
        $username = $loadeduser ->getUsername();

        echo "User: $username<br>";
        echo "Creation date: $creationDate<br>";
        echo "Text: <a href=\"postBoard.php?postId=$id\">$text</a><br>";

        $commentsTable = Comment::loadAllCommentByPostId($conn, $id);
        $nrOfComments = count($commentsTable);

        echo 'Number of comments: ' . $nrOfComments . '<br><br>';
    }
}
            
require_once dirname(__FILE__) . "/../../html/htmlFooter.php";