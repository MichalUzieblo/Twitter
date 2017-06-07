<?php

$isLogged = FALSE;

if (!empty($_SESSION['id'])) {
    
    $isLogged = TRUE;
    $id = $_SESSION['id'];
    $user = Users::loadUserById($conn, $id);
    
} else {
    header("Location: ../log/logIn.php");
}

