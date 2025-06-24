<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(!isset($_SESSION['EMAIL_user'])){
   header('Location: index.php');
   exit();
}
?>
