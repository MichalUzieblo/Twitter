<?php
session_start();
require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";
require_once dirname(__FILE__) . "/../../classes/Tweet.php";
require_once dirname(__FILE__) . "/../log/isLogged.php";

$switch = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['text'])) {
    
    $text = trim($_POST['text']); 
    
    if (!empty($_POST['text'])) {
        $tweet = new Tweet();
        $tweet ->setUserId($conn, $id);
        $tweet ->setCreationDate();
        $isExist = $tweet ->setText($text);
        
        if ($isExist == null) {
            $switch = 1;
        } else {            
            $tweet ->saveToDB($conn);
        }
    } else {
        $switch = 2;
    }
}

$title = 'Twitter - Main Board';
require_once dirname(__FILE__) . "/../../html/htmlHeader.php";

switch ($switch) {
    case 1:
        echo 'Za długi tekst';
        break;
    case 2:
        echo 'Przesłano puste pole';
        break;                   
}
?>
<center>
<form action="../board/userBoard.php" method="post" role="form">
    <button type="submit" value="userBoard" name="userBoard" class="btn btn-success">
        <?php 
        if ($isLogged) {
            echo $user->getUsername() . ' - profile';
        }
        ?>
    </button>
</form>
<form action="" method="post" role="form">

    <div class="form-group">
        <label for="">What's up 
            <?php 
            if ($isLogged) {
                echo $user->getUsername() . '?'; 
            }
            ?></label>
        <input type="text" class="form-control" name="text" id="name"
               placeholder="Text max 140 signs">

    </div>

    <button type="submit" value="addTweet" class="btn btn-success">ADD TWEET</button>
</form>
<br>

<form action="../log/logOut.php" method="post" role="form">
    <button type="submit" value="logOut" name="logOut" class="btn btn-success">Log Out</button>
</form>
<br>
</center>
<?php

if ($isLogged) {
    $tweetsTable = Tweet::loadAllTweets($conn);
    foreach ($tweetsTable as $value) {
        $id = $value ->getId();
        $userId = $value ->getUserId();
        $text = $value ->getText();
        $creationDate = $value ->getCreationDate();

        $loadeduser = Users::loadUserById($conn, $userId);
        $username = $loadeduser ->getUsername();

        echo "User: <a href=\"../user/displayUser.php?getUserId=$userId&getUsername=$username\">$username</a><br>";
        echo "Creation date: $creationDate<br>";
        echo "Text: <a href=\"../board/postBoard.php?postId=$id\">$text</a><br><br>";

    }
}

require_once dirname(__FILE__) . "/../../html/htmlFooter.php";