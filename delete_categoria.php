<?php
session_start();
include_once 'includes/protect.php';
include_once 'includes/conexao.php';
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

