<?php
session_start();

require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";
require_once dirname(__FILE__) . "/../log/isLogged.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteUser'])) {
    
    if (!empty($_POST['deleteUser'])) {
        
        $deleteUser = trim($_POST['deleteUser']);        
        
        switch ($deleteUser) {
            case 'no':
                header("Location: ../board/userBoard.php");
                break;
            case 'yes':
                echo $user->getId();
                if ($user->delete($conn)) {
                    header("Location: ../../../index.php");
                } else {
                    echo 'nie udalo sie';
                }
                
                break;                
        }
        
    } else {
        echo 'Spróbuj jeszcze raz';
    } 
} 

$title = 'Twitter - Delete User';
require_once dirname(__FILE__) . "/../../html/htmlHeader.php";
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Twitter - Delete User</title>
    <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <form action="" method="post" role="form">
                <legend>Are you sure <?php echo $user->getUsername() . ' ?'; ?></legend>
                <button type="submit" value="yes" name="deleteUser" class="btn btn-success">Yes</button>
                <button type="submit" value="no" name="deleteUser" class="btn btn-success">No</button>  
            </form>
            
<?php
require_once dirname(__FILE__) . "/../../html/htmlFooter.php";