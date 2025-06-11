<?php
session_start();
include_once 'conexao.php';
$conexao = conect();
$mensagem = '';

if (isset($_POST['email']) || isset($_POST['password'])) {
    if (strlen($_POST['email']) == 0) {
        $mensagem = "Preencha o campo email!";
    } else if (strlen($_POST['password']) == 0) {
        $mensagem = "Preencha o campo senha!";
    } else {
        $email = ($_POST['email']);
        $password = ($_POST['password']);

        $stmt = $conexao->prepare("SELECT * FROM USER WHERE EMAIL = :email ");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['PASSWORD'])) {
                $_SESSION['ID_user'] = $user['ID'];
                $_SESSION['EMAIL_user'] = $email;
                header('Location: home.php');
                exit();
            } else {
            $mensagem = "Falha ao logar! E-mail ou senha incorretos.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faça seu login na PlantAi!</title>
     <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <div id="box">
        <form action="" method="POST">
            <h1 id="titulo">PlantAi</h1>
            <p>Faça seu login como administrador:</p>

            <?php if(!empty($mensagem)): ?>
            <p id="mensagem"> <?= $mensagem ?>  </p>
            <?php endif; ?>
            
            <label for="email" >E-mail:</label>
            <input type="text" id="email"  name="email">
                
            <label for="password">Senha:</label>
            <input type="password" id="password"  name="password">
            
            <button type="submit">Entrar</button>
            
            <div id="link_cadastro">
                <p>Não está cadastrado?</p>
                <p ><a href="cadastro_user.php">Cadastrar-se</a></p>
            </div>
        </form>
    </div>
</body>
</html>