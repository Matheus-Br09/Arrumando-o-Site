<?php
session_start();
require_once __DIR__ . '/../php/config.php';

// 1. TRAVA DE SEGURANÇA: Só entra se for ADM
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'adm') {
    header("Location: ../pages/login.php");
    exit();
}



// 2. BUSCA DE PRODUTOS (Relacionando com a tabela de categorias)
$sql = "SELECT p.*, c.nome_categoria
        FROM produto p
        INNER JOIN categorias c ON p.c_categoria = c.c_categoria
        ORDER BY c.nome_categoria, p.nome_produto";

$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo - Mi Patisserie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">Painel ADM - Mi Patisserie</span>
            <a href="../php/logout.php" id="link" class="btn btn-outline-light btn-sm">Sair</a>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gerenciar Cardápio</h2>
            <a href="cadastrar_produto.php" class="btn btn-success">+ Novo Produto</a>
        </div>

        <table class="table table-hover bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Imagem</th>
                    <th>Produto</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while($prod = $resultado->fetch_assoc()): ?>
                <tr>
                    <td>
                        <img src="../img/<?php echo $prod['imagem']; ?>" alt="foto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                    </td>
                    <td><strong><?php echo $prod['nome_produto']; ?></strong></td>
                    <td><?php echo $prod['nome_categoria']; ?></td>
                    <td>R$ <?php echo number_format($prod['preco'], 2, ',', '.'); ?></td>
                    <td><?php echo $prod['estoque']; ?> un</td>
                    <td>
                        <a href="editar_produto.php?id=<?= $prod['c_produto'];?>" class="btn btn-warning btn-sm">Editar</a>
                        <button onclick="excluirProduto(<?= $prod['c_produto'];?>)" class="btn btn-danger btn-sm">Excluir</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="../JAVASCRIPT/Index.js"></script>
</body>
</html>