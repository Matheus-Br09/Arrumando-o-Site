<?php
session_start();
require_once __DIR__ . '/../php/config.php';

// SEGURANÇA: Garante que só clientes logados vejam seu próprio histórico
if (!isset($_SESSION['usuario_id']) || $_SESSION['perfil'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

$id_cliente = $_SESSION['usuario_id'];

// BUSCA: Pedidos do cliente com o nome do status (ex: Entregue, Cancelado)
$sql = "SELECT p.*, s.nome_status
        FROM pedido p
        INNER JOIN status_pedido s ON p.c_status = s.c_status
        WHERE p.c_cliente = $id_cliente
        ORDER BY p.data_pedido DESC";

$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Pedidos - Mi Patisserie</title>
    <link rel="stylesheet" href="css/index.css">
    <style>
        /* Estilo rápido para a tabela de histórico */
        .container-historico {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        th { color: gold; text-transform: uppercase; }
        .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
            color: black;
        }
    </style>
</head>
<body>
    <header class="header-topo">
        <a href="index.php" style="color: gold; text-decoration: none; font-weight: bold;">← VOLTAR AO CARDÁPIO</a>
        <h1 style="color: white; font-size: 20px;">Meu Histórico</h1>
    </header>

    <div class="container-historico">
        <?php if ($resultado->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>Data</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['n_pedido']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['data_pedido'])); ?></td>
                        <td>R$ <?php echo number_format($row['valor_total'], 2, ',', '.'); ?></td>
                        <td>
                            <span class="status-badge" style="background: gold;">
                                <?php echo $row['nome_status']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">Você ainda não tem pedidos finalizados.</p>
        <?php endif; ?>
    </div>
</body>
</html>