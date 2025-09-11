<?php
try {
$host = 'localhost';
$dbname = 'iti_project';
$username = 'root';
$password = '';
$connect= new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

}catch(Exception $e) {
    die("Connection failed: " . $e->getMessage());

}
session_start();

?>