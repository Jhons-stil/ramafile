<?php 
// destroy session
session_start();

$_SESSION = [];
session_unset();
session_destroy();

// destroy cookie
setcookie('phase', '', time() - 3600);
setcookie('key', '', time() - 3600);

header("Location: login.php");
?>