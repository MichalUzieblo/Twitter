<?php
session_start();
ob_start();

require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";
var_dump($_POST);
var_dump($_SESSION);


$isLogged = FALSE;

if (!empty($_SESSION['hashed_password']) && !empty($_SESSION['password'])
        && !empty($_SESSION['username']) && !empty($_SESSION['email'])
        && is_numeric($_SESSION['id']) && !empty($_SESSION['id'])) {
    
    $hashed_password = $_SESSION['hashed_password'];
    $password = $_SESSION['password'];
    $checkPassword = password_verify($password, $hashed_password);
    $id = $_SESSION['id'];
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    
    if ($checkPassword) {
        $isLogged = TRUE;
    } else {
        header("Location: ../log/logIn.php");
    }
}  else {
    header("Location: ../log/logIn.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteUser'])) {
    
    if (!empty($_POST['deleteUser'])) {
        
        $deleteUser = trim($_POST['deleteUser']);        
        
        if (Users::loadUserById($conn, $id)) {
            $loadedUser = Users::loadUserById($conn, $id);
        } else {
            header("Location: ../log/logIn.php");
        }
        
        switch ($deleteUser) {
            case 'no':
                header("Location: ../board/userBoard.php");
                break;
            case 'yes':
                $loadedUser ->delete($conn);
                header("Location: ../../../index.php");
                                
                break;                
        }
        
    } else {
        echo 'Spróbuj jeszcze raz';
    } 
} 
ob_end_flush();

$conn->close();
$conn = null;


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
                <legend>Are you sure <?php echo $username . ' ?'; ?></legend>
                <button type="submit" value="yes" name="deleteUser" class="btn btn-success">Yes</button>
                <button type="submit" value="no" name="deleteUser" class="btn btn-success">No</button>  
            </form>
            
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        </div>
    </div>
</div>
</body>
</html>

