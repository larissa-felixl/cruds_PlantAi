<?php
function conect() {
    try {
        $conexao = new PDO(
            "mysql:host=localhost;dbname=plantai_data;charset=utf8mb4", "root", ''
        );
        return $conexao;
    } catch (PDOException $erro) {
        echo "Erro na conexão. senha ou usuário incorretos. " ;
    }
}
?>