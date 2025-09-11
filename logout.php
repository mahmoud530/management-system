<?php
require_once 'connection.php';
session_start();
session_unset();
session_destroy();
unset($_SESSION['id']);
unset($_SESSION['name']);
header("Location: login.php");
exit;
?>