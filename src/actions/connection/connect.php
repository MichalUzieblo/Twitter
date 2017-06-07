<?php
require_once dirname(__FILE__) . "/../../../config/database.php";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_DBNAME);
if ($conn->connect_error) {
    die("Polaczenie z products_ex nieudane. Blad: " .
    $conn->connect_error);
} 
