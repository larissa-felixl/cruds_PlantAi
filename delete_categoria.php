<?php
session_start();
include_once 'protect.php';
include_once 'conexao.php';
$conexao = conect();
$mensagem = '';
$id_category = isset($_GET['ID_category']) ? $_GET['ID_category'] : null;

if (!$id_category) {
    die("Categoria não selecionada.");  
                    
} else{
    try{
        $stmt = $conexao->prepare("DELETE FROM CATEGORY WHERE ID = $id_category");
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {                            
            header('Location: categoria.php');
            exit();
            } else {
            $mensagem = "Erro ao tentar efetivar a exclusão da categoria.";
            } 
        } else {
        throw new PDOException("Erro: Não foi possível executar a declaraçãosql");
        }   
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
        }       
    }



?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faça edições na sua categoria.</title>
    <link rel="stylesheet" href="styles/delete_categoria.css?v=<?= time()?>">
</head>
<body>
    <div id="box">
        <h1>Categorias cadastradas</h1>
        <div id="botoes_editar_excluir">
            <a href="categoria.php?ID_category=<?= $categoria['ID']?>"><Button type="submit" id="botao_editar">Sim</Button></a>    
            <a href="categoria.php?ID_category=<?= $categoria['ID']?>"><button type="submit" id="botao_excluir">Não</button></a>
        </div>
    </div>
</body>
</html>