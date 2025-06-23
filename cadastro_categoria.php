<?php
session_start();
include_once 'protect.php';
include_once 'conexao.php';
$conexao = conect();
$mensagem = '';

if (isset($_POST['name']) || isset($_POST['description']) || isset($_POST['image'])) {
    if (strlen($_POST['name']) == 0) {
        $mensagem = "Preencha o campo nome da categoria!";
    } else if (strlen($_POST['description']) == 0) {
        $mensagem = "Preencha o campo descrição da categoria!";
    } else if (($_FILES['image']) == 0) {
        $mensagem = "Preencha o campo imagem da categoria!";
    }else {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
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
                try {
                    $vrf = $conexao->prepare("SELECT * FROM CATEGORY WHERE NAME = :name");
                    $vrf->bindParam(':name', $name);
                    $vrf->execute();

                    if($vrf->rowcount() > 0){
                        $mensagem = ' Categoria já cadastrada <a href="categoria.php"  id="link_login">Clique aqui para voltar para a página de categorias.</a>';    
                    
                    } else{
                        $user_id = $_SESSION['ID_user'];
                        $stmt = $conexao->prepare("INSERT INTO CATEGORY ( NAME, DESCRIPTION, IMG, USER_ID ) VALUES(:name, :description, :image, :user_id)");
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':image', $caminho_final);
                        $stmt->bindParam(':user_id', $user_id);
                        if ($stmt->execute()) {
                            if ($stmt->rowCount() > 0) {                            
                            header('Location: categoria.php');
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
    <title>Cadastre uma nova categoria</title>
    <link rel="stylesheet" href="styles/cadastro_categoria.css?v=<?= time()?>"   >
</head>
<body>
    <div id="box">
        <form action="" method="POST" enctype="multipart/form-data">
            <h1 id="titulo">Descreva sua categoria</h1>

            <?php if(!empty($mensagem)): ?>
            <p id="mensagem"> <?= $mensagem ?>  </p>
            <?php endif; ?>
            
            <label for="name" >Nome:</label>
            <input type="text" id="name"  name="name">
                
            <label for="description">Descrição:</label>
            <input type="text" id="description"  name="description">

            <label for="image">Fazer upload de imagem ilustrativa:</label>
            <div id="upload-area" onclick="document.getElementById('image').click();">
                <p>Fazer upload de arquivo .png ou .jpeg</p>
                <img src="assets/images/upload.png" alt="Upload Icon" id="img_salva">
                <img style="display: none; width: 180px; height: 140px;" id="preview" src="#" alt="h">
            </div>
            <input type="file" id="image" name="image" accept="image/*" style="display: none;">
            
            <button type="submit">Cadastrar</button>
        </form>
    </div>
    <script>
        function change_src(event) {
        document.getElementById('preview').src = event.target.result;
        document.getElementById('preview').style.display = 'block';
        
        document.querySelector('#upload-area > p').style.opacity = '0';
        document.querySelector('#upload-area > img:first-of-type').style.opacity = '0';
    }

    function showPreview() {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = change_src;
            reader.readAsDataURL(file);
        } else {
            document.getElementById('preview').style.display = 'none';
            document.getElementById('preview').src = '#';
        }
    }

    document.getElementById('image').addEventListener('change', showPreview);
       
    </script>
</body>
</html>