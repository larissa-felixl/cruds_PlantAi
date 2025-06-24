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
        <div id="container1">
            <h1>Ir para cadastro de categorias</h1>
            <a href="categoria.php"><button class="botao">Ir</button></a>
        </div>
        
        <div id="container2">
            <h1>Ir para cadastro de vendas</h1>
            <a href="index.php"><button class="botao">Ir</button></a>
        </div>
    </div>

    <footer id="rodape">
    </footer>
</body>
</html>