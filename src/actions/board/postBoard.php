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

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Twitter - Post Board</title>
    <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            
        </div>
        
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
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


