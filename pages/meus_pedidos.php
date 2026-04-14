<?php
session_start();
require_once __DIR__.'/../php/config.php'; 

// SEGURANÇA: Só entra se for CLIENTE
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

$id_cliente = $_SESSION['usuario_id'];

// Buscando os pedidos com o nome do status
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
    <title>Meus Pedidos - Mi Patisserie</title>
    <link rel="stylesheet" href="../css/index.css">
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
    <header class="header-pedidos">
        <a href="index.php"><div style="color: gold; font-size: 24px; font-weight: bold;">
            Mi Patisserie</div></a>
        <nav>
            <a href="index.php" style="color: white; text-decoration: none; margin-right: 20px; font-weight: bold;">CARDÁPIO</a>
        </nav>
    </header>

    <section style="text-align: center; margin-top: 50px;">
        <h2 style="color: gold; border: 2px solid gold; display: inline-block; padding: 10px 40px; border-radius: 5px;">
            Olá, <?php echo explode(' ', $_SESSION['usuario_nome'])[0]; ?>!
        </h2>
        <p style="color: white; margin-top: 15px; font-size: 18px;">Abaixo está o seu histórico de delícias:</p>
    </section>

    <div class="container-pedidos">
        <?php if ($resultado->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nº Pedido</th>
                        <th>Data</th>
                        <th>Total</th>
                        <th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($pedido = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $pedido['n_pedido']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                        <td>R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?></td>
                        <td style="text-align: center;">
                            <span class="status-badge">
                                <?php echo $pedido['nome_status']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: white; text-align: center;">Você ainda não realizou nenhum pedido. <a href="index.php" style="color: gold;">Que tal um doce agora?</a></p>
        <?php endif; ?>
    </div>

    <footer style="text-align: center; color: rgba(255,255,255,0.5); margin-top: 50px; padding-bottom: 30px;">
        Mi Patisserie © 2026
    </footer>
</body>
</html>