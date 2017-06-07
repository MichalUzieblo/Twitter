<?php
session_start();
require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";
require_once dirname(__FILE__) . "/../../classes/Tweet.php";
require_once dirname(__FILE__) . "/../../classes/Comment.php";

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

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Twitter - User Board</title>
    <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            
        </div>
        
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <center>
            <form action="../user/editProfile.php" method="post" role="form">
                <div class="form-group">
                    <label for="">Hello <?php echo $_SESSION['username']; ?></label>
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


