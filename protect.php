<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(!isset($_SESSION['EMAIL_user'])){
    exit("você não pode acessar essa página sem estar logado!
    <p><a href =\"index.php\"> Entrar <a></p>");
}
?>
