<?php
session_start();

require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";
require_once dirname(__FILE__) . "/../log/isLogged.php";

$switch = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])
        && isset($_POST['password']) && isset($_POST['username'])
        && isset($_POST['repeatPassword']) && is_numeric($_SESSION['id']) && !empty($_SESSION['id'])) {
    
    if (!empty($_POST['email']) && !empty($_POST['password'])
            && !empty($_POST['username']) && !empty($_POST['repeatPassword'])) {
        
        $newPassword = trim($_POST['password']);
        $newRepeatPassword = trim($_POST['repeatPassword']);
        $newUsername = trim($_POST['username']);
        $newEmail = trim($_POST['email']);
        
        if ($newPassword == $newRepeatPassword ) {

            $editUser = Users::loadUserById($conn, $id);
            $editUser ->setUsername($newUsername);
            $editUser ->setPassword($newPassword);
            $editUser ->setEmail($newEmail);
            $isExist = $editUser ->saveToDB($conn);
            if ($isExist == null) {
                $switch = 1;
            } else {
                $_SESSION['id'] = $editUser ->getId();
                header("Location: ../board/userBoard.php");
            }
        } else {
            $switch = 2;           
        }
        
    } else {
        $switch = 3;        
    } 
} 

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
    <title>Twitter - Edit Profile</title>
    <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <?php
            switch ($switch) {
                case 1:
                    echo 'Email się powtarza';
                    break;
                case 2:
                    echo 'Podane hasła nie zgadzają się';
                    break;
                case 3:
                    echo 'Wypełnij wszystkie pola lub zaloguj się ponownie';
                    break;                    
            }
            ?>
            <form action="" method="post" role="form">
                <legend>Hello <?php echo $user->getUsername(); ?></legend>
                <div class="form-group">
                    <label for="">New Username</label>
<!--                    TO DO Docelowo zmienic type na email-->
                    <input type="text" class="form-control" name="username" id="email"
                           value='<?php echo $user->getUsername(); ?>'>
                </div>
                <div class="form-group">
                    <label for="">New E-mail</label>
<!--                    TO DO Docelowo zmienic type na email-->
                    <input type="email" class="form-control" name="email" id="email"
                           value='<?php echo $user->getEmail(); ?>'>
                </div>
                <div class="form-group">
                    <label for="">New password</label>
                    <input type="password" class="form-control" name="password" id="email"
                           placeholder="New password">
                    <input type="password" class="form-control" name="repeatPassword" id="email"
                           placeholder="Repeat new password">
                </div>
                <center><button type="submit" value="editProfile" class="btn btn-success">Save</button></center>              
            <center>
            </form>
            <form action="../log/logOut.php" method="post" role="form">
                <button type="submit" value="logOut" name="logOut" class="btn btn-success">Log Out</button>
            </form>
            <form action="../board/userBoard.php" method="post" role="form">
                <button type="submit" value="userBoard" class="btn btn-success">User Board</button>
            </form>
            <form action="../board/mainBoard.php" method="post" role="form">
                <button type="submit" value="mainBoard" class="btn btn-success">Main Board</button>
            </form>
            <form action="deleteUser.php" method="post" role="form">
                <button type="submit" value="deleteUser" class="btn btn-success">Delete User</button>
            </form>
            </center>
            
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        </div>
    </div>
</div>
</body>
</html>

