<?php
session_start();
include_once 'includes/protect.php';
include_once 'includes/conexao.php';
$conexao = conect();
$mensagem = '';
$id_planta = $_GET['ID_plant'];
$stmt = $conexao->prepare("SELECT * FROM PLANT WHERE ID = :id_planta");
$stmt->bindParam(':id_planta', $id_planta);
$stmt->execute();
$planta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$id_planta) {
    die("planta não selecionada.");

}  else {
    try {
        $alterado = false;
        if(isset($_POST['name'])){
            $new_name = $_POST['name'];
            $stmt = $conexao->prepare("UPDATE PLANT SET NAME = :name WHERE ID = :id_planta");
            $stmt->bindParam(':name', $new_name);
            $stmt->bindParam(':id_planta', $id_planta);
            $stmt->execute();
            $alterado = true;
        } 

        if(isset($_POST['description'])){
            $new_description = $_POST['description'];
            $stmt = $conexao->prepare("UPDATE PLANT SET DESCRIPTION = :description WHERE ID = :id_planta");
            $stmt->bindParam(':description', $new_description);
            $stmt->bindParam(':id_planta', $id_planta);
            $stmt->execute();
            $alterado = true;
        }

        if(isset($_POST['price'])){
            $new_price = $_POST['price'];
            $stmt = $conexao->prepare("UPDATE PLANT SET DESCRIPTION = :price WHERE ID = :id_planta");
            $stmt->bindParam(':price', $new_price);
            $stmt->bindParam(':id_planta', $id_planta);
            $stmt->execute();
            $alterado = true;
        }

        if(isset($_FILES['image'])){
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
                    $stmt = $conexao->prepare("UPDATE PLANT SET IMG = :image WHERE ID = :id_planta");
                    $stmt->bindParam(':image', $caminho_final);
                    $stmt->bindParam(':id_planta', $id_planta);
                    $stmt->execute();
                    $alterado = true;
                } else {
                    $mensagem = "Falha ao mover o arquivo de imagem.";
                }
            }
        }
                             
        if ($alterado) {                           
                header('Location: plantas.php');
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
    <title>Faça edições na sua planta.</title>
    <link rel="stylesheet" href="assets/styles/upgrade_planta.css?v=<?= time()?>">
</head>
<body>
    <div id="box">
        <form action="" method="POST" enctype="multipart/form-data">
            <h1 id="titulo">Edite a sua planta</h1>

            <?php if(!empty($mensagem)): ?>
            <p id="mensagem"> <?= $mensagem ?>  </p>
            <?php endif; ?>
            
            <label for="name" >Nome:</label>
            <input type="text" id="name"  name="name" value="<?= htmlspecialchars($planta['NAME']) ?>">
                
            <label for="description">Descrição:</label>
            <input type="text" id="description"  name="description" value="<?= htmlspecialchars($planta['DESCRIPTION']) ?>">

            <label for="price">Preço:</label>
            <input type="decimal" id="price"  name="price" value="<?= htmlspecialchars($planta['PRICE']) ?>">

            <div id="imagem_antiga_nova">
                <div id="imagem_atual">
                    <p>Imagem atual:</p>
                    <img src="<?= htmlspecialchars($planta['IMG']) ?>" alt="imagem atual"  style="width:200px; height: 180px;"  >
                </div>

                <div id="nova_imagem_area">
                    <label for="image">Fazer upload de uma nova imagem ilustrativa:</label>
                    <div id="upload-area" onclick="document.getElementById('image').click();">
                        <img style="display: none; width: 200px; height: 180px;" id="preview" src="#" alt="imagem ilustrativa">
                        
                        <p>Fazer upload de arquivo .png ou .jpeg</p>
                        <div>
                            <img src="assets/images/upload.png" alt="Upload Icon" id="img_salva">
                        </div>
                        
                    </div>
                </div>
            </div>
            
            <input type="file" id="image" name="image" accept="image/*" style="display: none;">
            <button type="submit" id="botao_editar">Editar</button>
        </form>
    </div>
    <script src="assets/js/index.js"></script>
</body>
</html>