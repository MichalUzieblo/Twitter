<?php

session_start();
ob_start();

require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])
        && isset($_POST['password'])) {
    
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        
        $sql = "SELECT * FROM Users WHERE email = '$email'"; 
        $query = $conn->query($sql);
        
        if ($query->num_rows > 0) {
            $row = $query->fetch_assoc();                      
            $hashed_password = $row['hashed_password'];
            $checkPassword = password_verify($password, $hashed_password);
            
            if ($checkPassword) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['password'] = $password;
                $_SESSION['hashed_password'] = $row['hashed_password'];
                unset ($_SESSION['logOut']);
                header("Location: ../board/mainBoard.php");

            } else {
                $badPass = 'wrongPass';
            }
            
        } else {
            $badPass = 'wrongEmail';
        }
    } elseif (isset($_POST['register'])) {
        header("Location: ../user/newUser.php");
    } elseif (isset($_POST['mainPage'])) {
        header("Location: ../../../index.php");
    } else {
        $badPass = 'completeData';
    }
} elseif (isset($_POST['register'])) {
    header("Location: ../user/newUser.php");
} else {    
    $badPass = 'noData';

} 
ob_end_flush();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Twitter - Log In </title>
    <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            
            <?php
            if (!empty($badPass)) {                
                switch ($badPass) {
                    case 'wrongPass':
                        echo '<h3>Nieprawidłowe haslo</h3>';
                        break;
                    case 'wrongEmail':
                        echo '<h3>Nieprawidłowy email</h3>';
                        break;
                    case 'completeData':
                        echo '<h3>Niepodano wszystkich danych</h3>';
                        break;
                    case 'noData':
                        echo '<h3>Podaj dane do logowania</h3>';
                        break;                   
                }
            }  
            
            $conn->close();
            $conn = null;
            ?>
            
            <form action="" method="post" role="form">
                <legend>Log in</legend>
                <div class="form-group">
                    <label for="">E-mail</label>
                    <input type="email" class="form-control" name="email" id="email"
                           placeholder="email@email.com">
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" class="form-control" name="password" id="email"
                           placeholder="Password">
                </div>
                <button type="submit" value="logInn" class="btn btn-success">Log in</button>
                <button type="submit" value="newUser" name="register" class="btn btn-success">Register</button>
                <button type="submit" value="mainPage" name="mainPage" class="btn btn-success">Main Page</button>
            </form>
            
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        </div>
    </div>
</div>
</body>
</html>

