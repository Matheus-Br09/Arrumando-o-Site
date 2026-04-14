<?php
session_start();
require_once __DIR__ . '/../php/config.php';

// 1. LÓGICA PARA ADICIONAR ITEM (Caso venha do index.php)
if (isset($_GET['add'])) {
    $id_add = intval($_GET['add']);
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

// Se já existe, aumenta a qtd, se não, começa com 1
if (isset($_SESSION['carrinho'][$id_add])) {
        $_SESSION['carrinho'][$id_add]++;
    } else {
        $_SESSION['carrinho'][$id_add] = 1;
    }
    header("Location: carrinho.php"); // Limpa o "add" da URL
    exit();
}

// 2. LÓGICA PARA REMOVER ITEM
if (isset($_GET['remover'])) {
    $id_remov = intval($_GET['remover']);
    unset($_SESSION['carrinho'][$id_remov]);
    header("Location: carrinho.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Carrinho - Mi Patisserie</title>
    <link rel="stylesheet" href="../css/index.css">
    <style>
        body {
            background-image: linear-gradient(to bottom, #4e1a1a, #2b0d0d);
            min-height: 100vh;
            color: white;
            font-family: Arial, sans-serif;
        }
        .tabela-carrinho {
            max-width: 1000px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background: rgba(0,0,0,0.3);
        }
        .logo { font-size: 24px; color: gold; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { border-bottom: 2px solid gold; padding: 15px; text-align: left; color: gold; text-transform: uppercase; }
        td { padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        
        /* Estilo que faltava para o botão */
        .btn-finalizar {
            display: inline-block;
            background: gold;
            color: black;
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            float: right;
            margin-top: 20px;
        }
        .btn-finalizar:hover { background: white; }
    </style>
</head>
<body>
    <header>
        <div class="logo">Mi Patisserie</div>
        <a href="index.php" style="color: gold; text-decoration: none;">← VOLTAR AO CARDÁPIO</a>
    </header>

    <div class="tabela-carrinho">
        <h2>Seu Carrinho</h2>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço Un.</th>
                    <th>Qtd.</th>
                    <th>Subtotal</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_geral = 0;
                if (isset($_SESSION['carrinho']) && count($_SESSION['carrinho']) > 0) {
                    foreach ($_SESSION['carrinho'] as $id => $qtd) {
                        // Segurança: garantir que o ID é número
                        $id = intval($id);
                        $sql = "SELECT * FROM produto WHERE c_produto = $id";
                        $res = $conexao->query($sql);
                        
                        if ($res && $res->num_rows > 0) {
                            $p = $res->fetch_assoc();
                            $subtotal = $p['preco'] * $qtd;
                            $total_geral += $subtotal;
                            ?>
                            <tr>
                                <td><?php echo $p['nome_produto']; ?></td>
                                <td>R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></td>
                                <td><?php echo $qtd; ?></td>
                                <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                                <td>
                                    <a href="carrinho.php?remover=<?php echo $id; ?>" style="color: #ff4d4d; text-decoration: none;">Remover</a>
                                </td>
                            </tr>
                        <?php 
                        }
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; padding:30px;'>Seu carrinho está vazio.</td></tr>";
                } ?>
            </tbody>
        </table>
        
        <div style="overflow: hidden;"> <h3 style="text-align: right; margin-top: 20px; color: gold;">
                Total: R$ <?php echo number_format($total_geral, 2, ',', '.'); ?>
            </h3>
            
            <?php if ($total_geral > 0): ?>
                <a href="finalizar_pedido.php" class="btn-finalizar">PROSSEGUIR COM A COMPRA</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>