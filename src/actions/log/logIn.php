<?php

session_start();

require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";

unset ($_SESSION['id']);

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
$title = 'Twitter - Log In';
require_once dirname(__FILE__) . "/../../html/htmlHeader.php";
?>
      
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
            
<?php
require_once dirname(__FILE__) . "/../../html/htmlFooter.php";