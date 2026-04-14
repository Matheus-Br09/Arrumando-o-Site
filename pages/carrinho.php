<?php
session_start();
require_once 'C:\xampp\htdocs\teste\php\config.php';

// Lógica para remover item ou alterar quantidade
if (isset($_GET['remover'])) {
    $id_remov = $_GET['remover'];
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
    <link rel="stylesheet" href="css/index.css">
    <style>
        /* Ajustes específicos para a página de pedidos */
        body {
            background-image: linear-gradient(to bottom, #4e1a1a, #2b0d0d); /* Cor de fundo do projeto */
            min-height: 100vh;
        }
        .header-pedidos {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background: rgba(0,0,0,0.3);
        }
        .container-pedidos {
            max-width: 1000px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            color: white;
        }
        th {
            border-bottom: 2px solid gold;
            padding: 15px;
            text-align: left;
            color: gold;
            text-transform: uppercase;
        }
        td {
            padding: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            background: gold;
            color: black;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Mi Patisserie</div>
        <a href="index.php" style="color: gold;">VOLTAR AO CARDÁPIO</a>
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
                        $sql = "SELECT * FROM produto WHERE c_produto = $id";
                        $res = $conexao->query($sql);
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
                                <a href="carrinho.php?remover=<?php echo $id; ?>" style="color: #ff4d4d;">Remover</a>
                            </td>
                        </tr>
                    <?php } 
                } else {
                    echo "<tr><td colspan='5'>Seu carrinho está vazio.</td></tr>";
                } ?>
            </tbody>
        </table>
        
        <h3 style="text-align: right; margin-top: 20px;">Total: R$ <?php echo number_format($total_geral, 2, ',', '.'); ?></h3>
        
        <?php if ($total_geral > 0): ?>
            <a href="finalizar_pedido.php" class="btn-finalizar">PROSSEGUIR COM A COMPRA</a>
        <?php endif; ?>
    </div>
</body>
</html>