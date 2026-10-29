<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "noliktavas_sistema";
$port = "3307";

$conn = null;

try{
    $conn = new PDO("mysql: host=$servername; port=$port; dbname=$dbname; charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
catch (PDOException $e) {
    echo "Sistēmas kļūda" . $e->getMessage(); 
}
?>