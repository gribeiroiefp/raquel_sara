<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "livros_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro de ligação: " . $conn->connect_error);
}
?>
