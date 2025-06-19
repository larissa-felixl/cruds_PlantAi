<?php
session_start();
include_once 'protect.php';
include_once 'conexao.php';
$conexao = conect();
$mensagem = '';
$id_category = $_GET['ID_category'];
$stmt = $conexao->prepare("SELECT * FROM CATEGORY WHERE ID = :id_category");
$stmt->bindParam(':id_category', $id_category);
$stmt->execute();
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$id_category) {
    die("Categoria não selecionada.");

}  else {
    try {
        $alterado = false;
        if(isset($_POST['name'])){
            $new_name = $_POST['name'];
            $stmt = $conexao->prepare("UPDATE CATEGORY SET NAME = :name WHERE ID = :id_category");
            $stmt->bindParam(':name', $new_name);
            $stmt->bindParam(':id_category', $id_category);
            $stmt->execute();
            $alterado = true;
        } 

        if(isset($_POST['description'])){
            $new_description = $_POST['description'];
            $stmt = $conexao->prepare("UPDATE CATEGORY SET DESCRIPTION = :description WHERE ID = :id_category");
            $stmt->bindParam(':description', $new_description);
            $stmt->bindParam(':id_category', $id_category);
            $stmt->execute();
            $alterado = true;
        }

        if(isset($_POST['image'])){
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
                $novo_nome = uniqid("categoria_", true) . "." . $ext;
                $caminho_final = $upload_dir . '/' . $novo_nome;

                if (move_uploaded_file($caminho_tmp, $caminho_final)){
                    $stmt = $conexao->prepare("UPDATE CATEGORY SET IMG = :image WHERE ID = :id_category");
                    $stmt->bindParam(':image', $caminho_final);
                    $stmt->bindParam(':id_category', $id_category);
                    $alterado = true;
                } else {
                    $mensagem = "Falha ao mover o arquivo de imagem.";
                }
            }
        }
                             
        if ($alterado) {                           
                header('Location: categoria.php');
                exit();
        } 
             
    } catch (PDOException $erro) { 
        $mensagem = "Erro no banco de dados: " . $erro->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faça edições na sua categoria.</title>
    <link rel="stylesheet" href="styles/upgrade_categoria.css?v=<?= time()?>">
</head>
<body>
    <div id="box">
        <form action="" method="POST" enctype="multipart/form-data">
            <h1 id="titulo">Edite a sua categoria</h1>

            <?php if(!empty($mensagem)): ?>
            <p id="mensagem"> <?= $mensagem ?>  </p>
            <?php endif; ?>
            
            <label for="name" >Nome:</label>
            <input type="text" id="name"  name="name" value="<?= htmlspecialchars($plant['NAME']) ?>">
                
            <label for="description">Descrição:</label>
            <input type="text" id="description"  name="description" value="<?= htmlspecialchars($plant['DESCRIPTION']) ?>">

            <label for="price">Preço:</label>
            <input type="decimal" id="price"  name="price" value="<?= htmlspecialchars($plant['PRICE']) ?>">

            <label for="image">Fazer upload de imagem ilustrativa:</label>
            <div id="upload-area" onclick="document.getElementById('image').click();">
                <p>Fazer upload de arquivo .png ou .jpeg</p>
                <img src="<?= htmlspecialchars($plant['IMG']) ?>" alt="Upload Icon">
            </div>
            
            <input type="file" id="image" name="image" accept="image/*" style="display: none;">
            <button type="submit" id="botao_editar">Editar</button>
        </form>
    </div>
    
</body>
</html>