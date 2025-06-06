<?php
include_once'protect.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
    <link rel="stylesheet" href="styles/home.css">
</head>
<body>
    <header id="cabecalho">
        <div id="logo">
            <h1 >PlantAi</h1>
        </div>
    </header>

    <div id="navegador"> 
            <a href="index.php">Login</a>
    </div>
    

    <div id="fundo">
        <div></div>
        <div></div>
        <h1>Bem-vindo, usuário <?= $_SESSION['EMAIL_user'] ?>!</h1>
        <a href="logout.php">Sair</a>
    </div>

    <footer>

    </footer>
</body>
</html>