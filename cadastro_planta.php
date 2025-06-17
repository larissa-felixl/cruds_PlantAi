<?php
session_start();
include_once 'protect.php';
include_once 'conexao.php';
$conexao = conect();
$mensagem = '';
$user_id = $_SESSION['ID_user'];
$stmt = $conexao->prepare("SELECT ID, NAME, DESCRIPTION, IMG FROM CATEGORY WHERE USER_ID = :user_id ORDER BY ID DESC");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$categoria = $stmt->fetchall(PDO::FETCH_ASSOC);
$id_category = $categoria [0]['ID'];

if (isset($_POST['name']) || isset($_POST['description']) || isset($_POST['image'])) {
    if (strlen($_POST['name']) == 0) {
        $mensagem = "Preencha o campo nome da planta!";
    } else if (strlen($_POST['description']) == 0) {
        $mensagem = "Preencha o campo descrição da planta!";
    } else if (strlen($_POST['price']) == 0) {
        $mensagem = "Preencha o campo preço da planta!";
    }else if (($_FILES['image']) == 0) {
        $mensagem = "Preencha o campo imagem da planta!";
    }else {
        $name = ($_POST['name']);
        $description = ($_POST['description']);
        $price = ($_POST['price']);
        $upload_dir = "assets/images/imgs_planta";
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $ext_permitida = ['jpg', 'jpeg', 'png', 'webp'];
        $caminho_tmp = $_FILES['image']['tmp_name'];
        $nome_original = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));

        if (!in_array($ext, $ext_permitida)) {
            $mensagem = "Formato de imagem inválido. Use JPG, PNG, JPEG ou WEBP.";
        } else {
            $novo_nome = uniqid("planta_", true) . "." . $ext;
            $caminho_final = $upload_dir . '/' . $novo_nome;

            if (move_uploaded_file($caminho_tmp, $caminho_final)){
                try {
                    $vrf = $conexao->prepare("SELECT * FROM PLANT WHERE NAME = :name");
                    $vrf->bindParam(':name', $name);
                    $vrf->execute();

                    if($vrf->rowcount() > 0){
                        $mensagem = ' Categoria já cadastrada <a href="plantas.php" id="link_login">Clique aqui para voltar para a página de plantas.</a>';    
                    
                    } else{
                        $stmt = $conexao->prepare("INSERT INTO PLANT ( NAME, DESCRIPTION, PRICE, IMG, USER_ID, CATEGORY_ID ) VALUES(:name, :description, :price, :image, :user_id, :category_id)");
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':image', $caminho_final);
                        $stmt->bindParam(':user_id', $user_id);
                        $stmt->bindParam(':category_id', $id_category);
                        if ($stmt->execute()) {
                            if ($stmt->rowCount() > 0) {                            
                            header('Location: plantas.php');
                                exit();
                        } else {
                            $mensagem = "Erro ao tentar efetivar cadastro de categoria.";
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
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastre uma nova plamta na categoria </title>
    <link rel="stylesheet" href="styles/cadastro_planta.css?v=<?= time()?>"   >
</head>
<body>
    <div id="box">
        <form action="" method="POST" enctype="multipart/form-data">
            <h1 id="titulo">Descreva sua planta</h1>

            <?php if(!empty($mensagem)): ?>
            <p id="mensagem"> <?= $mensagem ?>  </p>
            <?php endif; ?>
            
            <label for="name" >Nome:</label>
            <input type="text" id="name"  name="name">
                
            <label for="description">Descrição:</label>
            <input type="text" id="description"  name="description">

            <label for="price">Preço:</label>
            <input type="decimal" id="price"  name="price">

            <label for="image">Fazer upload de imagem ilustrativa:</label>
            <div id="upload-area" onclick="document.getElementById('image').click();">
                <p>Fazer upload de arquivo .png ou .jpeg</p>
                <img src="assets/images/upload.png" alt="Upload Icon">
            </div>
            <input type="file" id="image" name="image" accept="image/*" style="display: none;">
            
            <button type="submit">Cadastrar</button>
        </form>
    </div>
</body>
</html>