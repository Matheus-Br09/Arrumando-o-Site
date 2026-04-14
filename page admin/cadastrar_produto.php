<?php
session_start();
require_once __DIR__ . '/../php/config.php';

// SEGURANÇA: Só ADM acessa
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'adm') {
    header("Location: login.php");
    exit();
}

// BUSCAR CATEGORIAS para o <select>
$query_cat = "SELECT * FROM categorias";
$categorias = $conexao->query($query_cat);

// LÓGICA DE CADASTRO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome_produto'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];
    $categoria = $_POST['c_categoria'];

    // Tratamento da Imagem
    $arquivo = $_FILES['imagem'];
    $nome_imagem = time() . "_" . $arquivo['name']; // Nome único para não sobrescrever
    $destino = "../img/" . $nome_imagem;

    if (move_uploaded_file($arquivo['tmp_name'], $destino)) {
        // Salva no Banco de Dados
        $sql = $conexao->prepare("INSERT INTO produto (nome_produto, descricao, preco, estoque, c_categoria, imagem) VALUES (?, ?, ?, ?, ?, ?)");
        $sql->bind_param("ssdiis", $nome, $descricao, $preco, $estoque, $categoria, $nome_imagem);
        
        if ($sql->execute()) {
            echo "<script>alert('Produto cadastrado com sucesso!'); window.location='painel_adm.php';</script>";
        } else {
            echo "Erro ao cadastrar: " . $conexao->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto - Mi Patisserie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm p-4">
            <h2>Novo Produto</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Nome do Produto</label>
                    <input type="text" name="nome_produto" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Descrição</label>
                    <textarea name="descricao" class="form-control"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Preço (ex: 15.50)</label>
                        <input type="number" step="0.01" name="preco" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Estoque Inicial</label>
                        <input type="number" name="estoque" class="form-control" value="0">
                    </div>
                </div>
                <div class="mb-3">
                    <label>Categoria</label>
                    <select name="c_categoria" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php while($cat = $categorias->fetch_assoc()): ?>
                            <option value="<?php echo $cat['c_categoria']; ?>"><?php echo $cat['nome_categoria']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Foto do Produto</label>
                    <input type="file" name="imagem" class="form-control" accept="image/*" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="painel_adm.php" class="btn btn-secondary">Voltar</a>
                    <button type="submit" class="btn btn-primary">Salvar Produto</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>