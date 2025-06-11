<?php
session_start();
include_once 'protect.php';
include_once 'conexao.php';
$conexao = conect();
$mensagem = '';
$user_id = $_SESSION['ID_user'];
$stmt = $conexao->prepare("SELECT NAME, DESCRIPTION, IMG FROM CATEGORY WHERE USER_ID = :user_id ORDER BY ID DESC");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$categorias = $stmt->fetchall(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navegue pelas categorias</title>
    <link rel="stylesheet" href="styles/categorias.css?v=<?= time()?>">
</head>
<body>
    <header id="cabecalho">
        <div id="titulo">
            <h1>Bem-vinda(o), <?= $_SESSION['EMAIL_user'] ?>!</h1>
            <a href="logout.php">Sair</a>
        </div>
        
        <div id="pesquisa">
            <input type="text" placeholder="Busque por uma categoria já registrada...">
            <button type="submit">
                <img src="assets/images/search.png" alt="lupa">
            </button>
        </div>

        <div id="logo">
            <h1 >PlantAi</h1>
        </div>
    </header>

    <div id="navegador"> 
            <a href="index.php">Login></a>
            <a href="home.php">Home></a>
    </div>
    
    <div id="fundo">
       <h1>Categorias cadastradas</h1>
       <div id="blocos">
            <div id="bloco_ad_categoria">
                <div id="box_cadastro_categoria">
                    <h1>Cadastrar categoria</h1>
                    <div >
                        <a href="cadastro_categoria.php"><button class="botao_cadastro">+</button></a>    
                    </div>
                </div>
            </div>
            
            <?php if(!empty($categorias)): ?>
                <?php foreach($categorias as $categoria):?>
                    <div class="bloco">
                        <img  style="width:160px; height:160px;"   src="<?= htmlspecialchars($categoria['IMG'])?>" alt="imagem ilustrativa">
                        <a href="planta.php" ><button id="botao_categoria">Categoria</button></a>
                        <p id="nome_categoria"> <?= htmlspecialchars($categoria['NAME']) ?> </p>
                        <p id="desc_categoria"> <?= htmlspecialchars($categoria['DESCRIPTION'])?></p>  
                        <div id="botoes_editar_excluir">
                            <Button id="botao_editar" >Editar</Button>
                            <button id="botao_excluir" >Excluir</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php  else: ?>
                <p>Nenhuma categoria registrada.</p>
            <?php endif; ?> 
        </div>
    </div>
    <footer id="rodape">
    </footer>    
</body>
</html>