<?php
    include __DIR__ . '/../php/config.php';

    $total_carrinho = 0;
    if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
        foreach ($_SESSION['carrinho'] as $id => $qtd) {
            $res_p = $conexao->query("SELECT preco FROM produto WHERE c_produto = ".intval($id));
                if ($p = $res_p->fetch_assoc()) {
                    $total_carrinho += $p['preco'] * $qtd;
                }
            }
        }

?>