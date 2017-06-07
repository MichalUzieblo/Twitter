<?php
session_start();
require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";
require_once dirname(__FILE__) . "/../../classes/Tweet.php";
require_once dirname(__FILE__) . "/../../classes/Comment.php";
require_once dirname(__FILE__) . "/../log/isLogged.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['postId'])) {
    
    $_SESSION['postId'] = $_GET['postId'];
    $postId = $_GET['postId'];
}

$switch = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['text']) && $isLogged) {
    
    $newText = trim($_POST['text']);
    $postId = $_SESSION['postId'];
    
    if (!empty($_POST['text'])) {
        $comment = new Comment();
        $comment ->setCreationDate();
        $comment ->setPostId($conn, $postId);
        $comment ->setUserId($conn, $id);
        $isExist = $comment ->setText($newText);
        
        if ($isExist == null) {
            $switch = 1;
        } else {            
            $comment ->saveToDB($conn);
        }

    } 
}
$title = 'Twitter - Post Board';
require_once dirname(__FILE__) . "/../../html/htmlHeader.php";
?>
<center>
<form action="mainBoard.php" method="post" role="form">
    <button type="submit" value="mainBoard" class="btn btn-success">Main Board</button>
</form>
<form action="userBoard.php" method="post" role="form">
    <button type="submit" value="userBoard" class="btn btn-success">User Board</button>
</form>
    <form action="../log/logOut.php" method="post" role="form">
    <button type="submit" value="logOut" name="logOut" class="btn btn-success">Log Out</button>
</form>
<br>
</center>

<?php

if ($isLogged) {
    $tweet = Tweet::loadTweetById($conn, $postId);
    $id = $tweet ->getId();
    $userId = $tweet ->getUserId();
    $text = $tweet ->getText();
    $creationDate = $tweet ->getCreationDate();

    $loadeduser = Users::loadUserById($conn, $userId);
    $username = $loadeduser ->getUsername();

    echo "<h4>Tweet: </h4>";
    echo "User: $username<br>";
    echo "Creation date: $creationDate<br>";
    echo "Text:$text<br><br>";

    switch ($switch) {
        case 1:
            echo '<b>Za długi tekst w komentarzu!</b>';
            break;
    }

    echo "<br><h4>Komentarze: </h4>";
    $commentsTable = Comment::loadAllCommentByPostId($conn, $_SESSION['postId']);

    foreach ($commentsTable as $value) {
        $id = $value ->getId();
        $userId = $value ->getUserId();
        $text = $value ->getText();
        $creationDate = $value ->getCreationDate();

        $loadeduser = Users::loadUserById($conn, $userId);
        $username = $loadeduser ->getUsername();

        echo "User: $username<br>";
        echo "Creation date: $creationDate<br>";
        echo "Text: $text<br><br>";
    }                
}

?>

<form action="" method="post" role="form">
    <div class="form-group">
    <input type="text" class="form-control" name="text" id="name"
           placeholder="Text max 60 signs">
    <button type="submit" value="addComment">add comment</button>
    </div>                 
</form>

<?php
echo "<a href=\"mainBoard.php\">Go to main board</a><br><br>";
require_once dirname(__FILE__) . "/../../html/htmlFooter.php";
?>