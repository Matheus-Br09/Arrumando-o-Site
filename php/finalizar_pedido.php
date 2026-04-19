<?php
session_start();
require_once __DIR__ . '/../php/config.php';

// 1. Bloqueia acesso de quem não está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./pages/login.php");
    exit();
}

$id_cliente = $_SESSION['usuario_id'];
$carrinho = $_SESSION['carrinho'] ?? [];

if (empty($carrinho)) {
    echo "<script>alert('Seu carrinho está vazio!'); window.location='../index.php';</script>";
    exit();
}

// 2. Calcula o valor total para gravar no pedido
$total_pedido = 0;
foreach ($carrinho as $id => $qtd) {
    $res = $conexao->query("SELECT preco FROM produto WHERE c_produto = $id");
    $p = $res->fetch_assoc();
    $total_pedido += ($p['preco'] * $qtd);
}

// 3. Insere o Pedido Principal
// Status 1 = Pendente (ou o ID que você definiu na tabela status_pedido)
date_default_timezone_set('America/Sao_Paulo');
$data_atual = date('Y-m-d H:i:s');
$sql_pedido = $conexao->prepare("INSERT INTO pedido (c_cliente, valor_total, data_pedido, c_status) VALUES (?, ?, ?, 1)");
$sql_pedido->bind_param("ids", $id_cliente, $total_pedido, $data_atual);

if ($sql_pedido->execute()) {
    $n_pedido = $conexao->insert_id; // Pega o ID do pedido que acabou de ser criado

    // 4. Insere cada item do carrinho na tabela item_pedido
    foreach ($carrinho as $id_prod => $qtd) {
        $res_p = $conexao->query("SELECT preco FROM produto WHERE c_produto = $id_prod");
        $prod_info = $res_p->fetch_assoc();
        $preco_un = $prod_info['preco'];

        $sql_item = $conexao->prepare("INSERT INTO item_pedido (n_pedido, c_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
        $sql_item->bind_param("iiid", $n_pedido, $id_prod, $qtd, $preco_un);
        $sql_item->execute();
    }

    // 5. Sucesso! Limpa o carrinho e avisa o cliente
    unset($_SESSION['carrinho']);
    echo "<script>alert('Pedido #$n_pedido realizado com sucesso!'); window.location='../pages/meus_pedidos.php';</script>";
} else {
    echo "Erro ao processar pedido: " . $conexao->error;
}
?>