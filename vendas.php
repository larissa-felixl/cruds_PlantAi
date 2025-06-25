<?php
include_once 'includes/protect.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="assets/styles/home.css?v=<?= time()?>">
</head>
<body>
    <header id="cabecalho">
        <div id="titulo">
            <h1>Bem-vinda(o), <?= $_SESSION['EMAIL_user'] ?>!</h1>
            <a href="logout.php">Sair</a>
        </div>
    
        <div id="logo">
            <h1 >PlantAi</h1>
        </div>
        
    </header>

    <div id="navegador"> 
            <a href="index.php">Login></a>
    </div>
    
    <div id="fundo">
        <h1 id="titulo"  style="margin-top:200px; margin-bottom:200px;"  >Página em construção! </h1>
    </div>

    <footer id="rodape">
    </footer>
</body>
</html>