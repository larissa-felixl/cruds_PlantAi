<?php
include_once 'conexao.php';
$conexao = conect();
    if (isset($_POST['email']) || isset($_POST['password'])) {

        if (strlen($_POST['email']) == 0) {
            echo "Preencha o campo email!";
        } else if (strlen($_POST['password']) == 0) {
            echo "Preencha o campo senha!";
        } else {
            $email = ($_POST['email']);
            $password = ($_POST['password']);

            $sql_code = "SELECT * FROM USER WHERE EMAIL = :email AND PASSWORD = :password ";
            $stmt = $conexao->prepare($sql_code);
            $stmt->bindparam(':email', $email);
            $stmt->bindparam(':password', $password);
            $stmt->execute();

            if ($stmt->rowcount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!isset($_SESSION)) {
                    session_start();
                }

                $_SESSION['ID'] = $user['ID'];
                header('Location: home.php');
                exit();
            } else {
                echo "Falha ao logar! E-mail ou senha incorretos.";
            }
        }
}