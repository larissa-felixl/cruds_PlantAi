<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(!isset($_SESSION['ID'])){
    exit("você não pode acessar essa página sem estar logado!");
}
?>
