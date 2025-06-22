<?php
session_start();
include_once 'protect.php';
include_once 'conexao.php';
$conexao = conect();
$mensagem = '';
$id_planta = $_GET['ID_plant'];

if (!$id_planta) {
    die("planta não selecionada.");
                    
} else{
    try{
        $stmt = $conexao->prepare("DELETE FROM PLANT WHERE ID = $id_planta");
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {                            
            header('Location: plantas.php');
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

