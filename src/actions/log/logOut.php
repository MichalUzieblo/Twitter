<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['logOut'])) {    
    
    $logOut = trim($_POST['logOut']); 
    
    if ($logOut == 'logOut') {        
        unset ($_SESSION['id']);
        unset ($_SESSION['postId']);        
        unset ($_SESSION['user2id']); 
        $_SESSION['logOut'] = $logOut;
        header("Location: ../../../index.php");        
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
    <title>Twitter - Log Out</title>
    <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    
    <div class="row">
        
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            
        </div>
        
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            
            <form action= method="post" role="form">
                <button type="submit" value="logOut" name="logOut" class="btn btn-success">Log out</button>
            </form>
            
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        </div>
    </div>
</div>
</body>
</html>

