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
$categoria = $stmt->fetchall(PDO::FETCH_ASSOC);
$id_category = $categoria [0]['ID'];

if (isset($_POST['name']) || isset($_POST['description']) || isset($_POST['image'])) {
    if (strlen(trim($_POST['name'])) == 0) {
        $mensagem = "Preencha o campo nome da planta!";
    } else if (strlen(trim($_POST['description'])) == 0) {
        $mensagem = "Preencha o campo descrição da planta!";
    } else if (strlen(trim($_POST['price'])) == 0) {
        $mensagem = "Preencha o campo preço da planta!";
    }else if ($_FILES['image']['error'] == 4) {
        $mensagem = "Preencha o campo imagem da planta!";
    }else {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = trim($_POST['price']);
        $upload_diretorio = "assets/images/imgs_plantas";
        
        if (!is_dir($upload_diretorio)) {
            mkdir($upload_diretorio, 0777, true);
        }

        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $caminho_temporario = $_FILES['image']['tmp_name'];
        $nome_original = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));

        if (!in_array($ext, $extensoes_permitidas)) {
            $mensagem = "Formato de imagem inválido. Use JPG, PNG, JPEG ou WEBP.";
        } else {
            $novo_nome = uniqid("planta_", true) . "." . $ext;
            $caminho_final = $upload_diretorio . '/' . $novo_nome;

            if (move_uploaded_file($caminho_temporario, $caminho_final)){
                try {
                    $vrf = $conexao->prepare("SELECT * FROM PLANT WHERE NAME = :name");
                    $vrf->bindParam(':name', $name);
                    $vrf->execute();

                    if($vrf->rowcount() > 0){
                        $mensagem = ' Planta já cadastrada <a href="plantas.php" id="link">Clique aqui para voltar para a página de plantas.</a>';    
                    
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
    <title>Cadastre uma nova planta na categoria </title>
    <link rel="stylesheet" href="assets/styles/cadastro_planta.css?v=<?= time()?>"   >
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
                <img src="assets/images/upload.png" alt="Upload Icon" id="img_salva">
                
                <img style="display: none; width: 180px; height: 140px;" id="preview" src="#" alt="imagem ilustrativa">
            </div>
            <input type="file" id="image" name="image" accept="image/*" style="display: none;">
            
            <button type="submit">Cadastrar</button>
        </form>
    </div>
    <script src="assets/js/index.js"></script>
</body>
</html>