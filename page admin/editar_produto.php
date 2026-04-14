<?php
session_start();
include 'config.php';

// 1. Verifica se o ID foi enviado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: painel_adm.php");
    exit();
}

$id = $_GET['id'];

// 2. Busca os dados atuais do produto
$res = $conexao->query("SELECT * FROM produto WHERE c_produto = $id");
if($res->num_rows == 0){
     header("Location: painel_adm.php?msg=produto_nao_encontrado");
     exit();
}
$prod = $res->fetch_assoc();


// 3. Lógica para salvar a alteração
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $conexao->real_escape_string($_POST['nome']);
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];
    $nome_imagem_atual = $prod['imagem']; // Mantemos o nome atual caso não troque
    
    // --- LÓGICA DO UPLOAD DE IMAGEM ---
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $arquivo_tmp = $_FILES['imagem']['tmp_name'];
        $nome_original = $_FILES['imagem']['name'];
        
        // Extrai a extensão do arquivo (jpg, png, etc)
        $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
        
        // Cria um nome único para a imagem para não sobrescrever
        // Ex: bolo_rolo_123.jpg
        $novo_nome_imagem = "produto_" . $id . "_" . time() . "." . $extensao;
        
        $destino = "img/" . $novo_nome_imagem;
        
        // Tenta mover o arquivo físico para a pasta img/
        if (move_uploaded_file($arquivo_tmp, $destino)) {
            // Se mover com sucesso, atualizamos a variável do nome para o banco
            $nome_imagem_atual = $novo_nome_imagem;
            
            // (OPCIONAL) Deletar a imagem antiga para não encher o servidor
            if(!empty($prod['imagem']) && file_exists("img/".$prod['imagem'])){
                unlink("img/".$prod['imagem']); 
            }
        } else {
            echo "<script>alert('Erro ao mover o arquivo de imagem.');</script>";
        }
    }
    // ------------------------------------

    // 4. Executa o UPDATE no banco
    $sql = "UPDATE produto SET 
            nome_produto = '$nome', 
            preco = '$preco', 
            estoque = '$estoque',
            imagem = '$nome_imagem_atual' 
            WHERE c_produto = $id";
    
    if ($conexao->query($sql)) {
        header("Location: painel_adm.php?msg=sucesso");
    } else {
        echo "Erro ao atualizar: " . $conexao->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto - Mi Patisserie</title>
    <link rel="stylesheet" href="css/index.css">
    <style>
        label { font-weight: bold; color: #333; margin-top: 10px; display: block; }
        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;
        }
    </style>
</head>
<body style="background: #e0e0e0; color: black; padding: 30px;">

    <a href="painel_adm.php" style="text-decoration: none; color: #d4af37;">&larr; Voltar ao Painel</a>
    <h2 style="color: #d4af37; text-align: center;">Editar Produto: <?php echo $prod['nome_produto']; ?></h2>

    <form method="POST" enctype="multipart/form-data" style="background: white; padding: 25px; border-radius: 8px; max-width: 500px; margin: 20px auto; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        
        <label>Nome do Produto:</label>
        <input type="text" name="nome" value="<?php echo $prod['nome_produto']; ?>" required>

        <label>Preço (R$):</label>
        <input type="number" step="0.01" name="preco" value="<?php echo $prod['preco']; ?>" required>

        <label>Estoque (un):</label>
        <input type="number" name="estoque" value="<?php echo $prod['estoque']; ?>" required>

       
        <label>Imagem do Produto (clique para trocar):</label>
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
             <?php if(!empty($prod['imagem']) && file_exists("img/".$prod['imagem'])): ?>
                <img src="img/<?php echo $prod['imagem']; ?>" style="width: 70px; height: 70px; object-fit: cover; border-radius: 5px;">
             <?php else: ?>
                <div style="width: 70px; height: 70px; background: #eee; border-radius: 5px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #777;">Sem Foto</div>
             <?php endif; ?>
             
            
             <input type="file" name="imagem" accept="image/png, image/jpeg, image/jpg" style="border: none; padding: 0;">
        </div>

        <button type="submit" style="background: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%;">
            SALVAR ALTERAÇÕES
        </button>
    </form>

</body>
</html>