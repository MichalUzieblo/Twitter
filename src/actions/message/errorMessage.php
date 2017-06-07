<?php

session_start();
require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";
require_once dirname(__FILE__) . "/../../classes/Tweet.php";
require_once dirname(__FILE__) . "/../../classes/Comment.php";
require_once dirname(__FILE__) . "/../../classes/Message.php";
require_once dirname(__FILE__) . "/../log/isLogged.php";

$title = 'Twitter - Error Message';
require_once dirname(__FILE__) . "/../../html/htmlHeader.php";
?>

<center>
Nie możesz wysłać wiadomości do siebie
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