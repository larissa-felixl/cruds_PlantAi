<?php
include_once'protect.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
</head>
<body>
    <h1>Bem-vindo, usuário <?= $_SESSION['ID'] ?>!</h1>
    <a href="logout.php">Sair</a>
</body>
</html>