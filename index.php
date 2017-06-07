<?php
session_start();

$isLogOut = FALSE;

if (isset($_SESSION['logOut']) && !empty($_SESSION['logOut'])) {
    if ($_SESSION['logOut'] == 'logOut') {
        $isLogOut = TRUE;
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
    <title>Twitter</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"><center>
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
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        </div>
    </div>
</div>
</body>
</html>

