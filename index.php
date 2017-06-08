<?php
session_start();
require_once dirname(__FILE__) . "/src/actions/connection/connect.php";
$isLogOut = FALSE;

if (isset($_SESSION['logOut']) && !empty($_SESSION['logOut'])) {
    if ($_SESSION['logOut'] == 'logOut') {
        $isLogOut = TRUE;
    }
}

$title = 'Twitter';
require_once dirname(__FILE__) . "/src/html/htmlHeader.php";

?>
<center>
        <form action="src/actions/log/logIn.php" method="post" role="form" id="center">
        <?php
        if ($isLogOut) {
            echo 'Pomyslnie wylogowano<br>';
        }
        ?>
        <legend>Welcome on Twitter</legend>                               
        <button type="submit" value="logInn" class="btn btn-success">Log in</button>
    </form>
    <br>
    <form action="src/actions/user/newUser.php" method="post" role="form">
        <button type="submit" value="newUser" class="btn btn-success">Register</button>
    </form>                
</center>  
<?php
require_once dirname(__FILE__) . "/src/html/htmlFooter.php";

