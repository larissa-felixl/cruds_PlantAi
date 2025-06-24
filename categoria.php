<?php
session_start();
include_once 'includes/protect.php';
include_once 'includes/conexao.php';
$conexao = conect();
$mensagem = '';
$user_id = $_SESSION['ID_user'];
$stmt = $conexao->prepare("SELECT ID, NAME, DESCRIPTION, IMG FROM CATEGORY WHERE USER_ID = :user_id ORDER BY ID DESC");
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
    <link rel="stylesheet" href="assets/styles/categorias.css?v=<?= time()?>">
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
                <?php foreach($categorias as $categoria):?> <!-para cada categoria dentro do array é executqado o bloco de codigo abaixo-!>
                    <div class="bloco">
                        <div id="botao_imagem_categoria">
                            <img  style="width:200px; height:160px;"   src="<?= htmlspecialchars($categoria['IMG'])?>" alt="imagem ilustrativa">
                            <a href="plantas.php?ID_category=<?= $categoria['ID']?>" ><button id="botao_categoria">Categoria</button></a>
                        </div>
                        
                        <div id="nome_descricao">
                            <p id="nome_categoria"> <?= htmlspecialchars($categoria['NAME']) ?> </p>
                            <p id="desc_categoria"> - <?= htmlspecialchars($categoria['DESCRIPTION'])?></p>  
                        </div>
                        <div id="botoes_editar_excluir">
                            <a href="upgrade_categoria.php?ID_category=<?= $categoria['ID']?>"><Button type="submit" id="botao_editar">Editar</Button></a>    
                            <a href="delete_categoria.php?ID_category=<?= $categoria['ID']?>" onclick=" return confirm('Tem certeza que deseja excluir esta categoria e todas as plantas dentro dela?'); "><button type="submit" id="botao_excluir" >Excluir</button></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php  else: ?>
                <p id="mensagem" >Nenhuma categoria registrada.</p>
            <?php endif; ?> 
        </div>
    </div>
    <footer id="rodape">
    </footer>    
</body>
</html>