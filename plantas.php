<?php
session_start();
include_once 'includes/protect.php';
include_once 'includes/conexao.php';
$conexao = conect();
$mensagem = '';
$user_id = $_SESSION['ID_user'];
$stmt = $conexao->prepare("SELECT * FROM CATEGORY WHERE USER_ID = :user_id ORDER BY ID DESC");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$categoria = $stmt->fetchall(PDO::FETCH_ASSOC);

$id_category = $categoria [0]['ID'];
$stmt = $conexao->prepare("SELECT * FROM PLANT WHERE CATEGORY_ID = :category_id ORDER BY ID DESC");
$stmt->bindParam(':category_id', $id_category);
$stmt->execute();
$plantas = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navegue pelas plantas</title>
    <link rel="stylesheet" href="assets/styles/plantas.css?v=<?= time()?>">
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
            <a href="categoria.php">Categorias></a>
    </div>
    
    <div id="fundo">
       <h1>Plantas cadastradas na categoria <?=$categoria[0]['NAME']?></h1>
       <div id="blocos">
            <div id="bloco_ad_categoria">
                <div id="box_cadastro_categoria">
                    <h1>Cadastrar nova planta na categoria <?=$categoria[0]['NAME']?></h1>
                    <div >
                        <a href="cadastro_planta.php?ID_category=<?= $categoria[0] ['ID']?>"><button class="botao_cadastro">+</button></a>    
                    </div>
                </div>
            </div>
            
            <?php if(!empty($plantas)): ?>
                <?php foreach($plantas as $planta):?>
                    <div class="bloco">
                        <div id="imagem">
                            <img  style="width:200px; height:160px;"   src="<?= htmlspecialchars($planta['IMG'])?>" alt="imagem ilustrativa">
                        </div>
                        
                        <div id="nome_descricao">
                            <p id="nome_categoria"> <?= htmlspecialchars($planta['NAME']) ?> </p>
                            <p id="desc_categoria"> - <?= htmlspecialchars($planta['DESCRIPTION'])?></p>  
                        </div>
                        <div id= "preco">
                            <p> R$ <?=htmlspecialchars($planta['PRICE'])?></p>
                        </div>
                        <div id="botoes_editar_excluir">
                            <a href="upgrade_planta.php?ID_plant=<?= $planta['ID']?>"><Button type="submit" id="botao_editar">Editar</Button></a>    
                            <a href="delete_planta.php?ID_plant=<?= $planta['ID']?>" onclick=" return confirm('Tem certeza que deseja excluir esta categoria?'); "><button type="submit" id="botao_excluir" >Excluir</button></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php  else: ?>
                <p id="mensagem" >Nenhuma planta registrada na categoria <?=$categoria[0]['NAME']?>.</p>
            <?php endif; ?> 
        </div>
    </div>
    <footer id="rodape">
    </footer>    
</body>
</html>