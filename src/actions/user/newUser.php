<?php
session_start();

require_once dirname(__FILE__) . "/../connection/connect.php";
require_once dirname(__FILE__) . "/../../classes/Users.php";

$switch = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])
        && isset($_POST['password']) && isset($_POST['username'])
        && isset($_POST['repeatPassword'])) {
    
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $repeatPassword = trim($_POST['repeatPassword']);
    
    if (!empty($_POST['email']) && !empty($_POST['password'])
            && !empty($_POST['username']) && !empty($_POST['repeatPassword'])) {
        
        if ($password == $repeatPassword) {
         
            $newUser = new Users();
            $newUser ->setUsername($username);
            $newUser ->setPassword($password);
            $newUser ->setEmail($email);
            $isExist = $newUser ->saveToDB($conn);
            if ($isExist == null) {
                $switch = 1;
            } else {             
                $_SESSION['id'] = $newUser ->getId();
                header("Location: ../board/mainBoard.php");
            }            
        } else {
            $switch = 2;            
        }
    } else {
        $switch = 3;        
    } 
} 

$title = 'Twitter - New User';
require_once dirname(__FILE__) . "/../../html/htmlHeader.php";

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
    <legend>New User</legend>
    <div class="form-group">
        <label for="">Username</label>
        <input type="text" class="form-control" name="username" id="email"
               placeholder="Username">
    </div>
    <div class="form-group">
        <label for="">E-mail</label>
        <input type="email" class="form-control" name="email" id="email"
               placeholder="email@email.com">
    </div>
    <div class="form-group">
        <label for="">Password</label>
        <input type="password" class="form-control" name="password" id="email"
               placeholder="Password">
        <input type="password" class="form-control" name="repeatPassword" id="email"
               placeholder="Repeat Password">
    </div>
    <center><button type="submit" value="newUser" name="newUser" class="btn btn-success">Register</button></center>
    <!--<button type="submit" value="logIn" name="logIn" class="btn btn-success">Go to login</button>-->
</form>
<form action="../log/logIn.php" method="post" role="form">
    <center><button type="submit" value="logIn" name="logIn" class="btn btn-success">Go to login</button></center>
</form>
            
<?php
require_once dirname(__FILE__) . "/../../html/htmlFooter.php";