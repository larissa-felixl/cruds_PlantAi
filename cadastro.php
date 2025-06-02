<?php
session_start();
include_once 'conexao.php';
$conexao = conect();
if (isset($_POST['email']) || isset($_POST['password'] )) {
    if (strlen(trim($_POST['email'])) == 0 ) {
        $mensagem = "Preencha o campo email!";

    } else if (strlen(trim($_POST['password'])) == 0 ) {
        $mensagem = "Preencha o campo senha!";

    } else {
        $email = trim($_POST['email']);
        $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
        
        try {
            $vrf = $conexao->prepare("SELECT * FROM USER WHERE EMAIL = :email");
            $vrf->bindParam(':email', $email);
            $vrf->execute();

            if($vrf->rowcount() > 0){
                $mensagem = 'Email já cadastrado <a href="index.php"  id="link_login">Clique aqui para fazer login.</a>';    
            
            } else{
                $stmt = $conexao->prepare("INSERT INTO USER ( EMAIL, PASSWORD ) VALUES(:email, :password)");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                
            
                if ($stmt->execute()) {
                    if ($stmt->rowCount() > 0) {
                        $_SESSION['EMAIL_user'] = $email;
                        header('Location: home.php');
                        exit();
                } else {
                    $mensagem = "Erro ao tentar efetivar cadastro";
                }
            } else {
                throw new PDOException("Erro: Não foi possível executar a declaraçãosql");
                }
            }    
        } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
        }       
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se cadastre no PlantAi </title>
     <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <div id="box">
        <form action="" method="POST">
        <h1 id="titulo">PlantAi</h1>
        <p>Faça seu cadastro como administrador:</p>

        <?php if (!empty($mensagem)): ?>
            <p id="mensagem"> <?= $mensagem ?></p>
        <?php endif; ?>
        
        <label for="email">E-mail:</label>
        <input type="text" id="email"  name="email">
            
            
        <label for="password">Senha:</label>
        <input type="password" id="password"  name="password">
        
        <button type="submit">Cadastrar</button>
    </form>    
    </div>
    
</body>
</html>