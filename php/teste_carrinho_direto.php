<?php
session_start();
require_once 'config.php';

// 1. Buscamos o primeiro produto que existir no seu banco para o teste
$res = $conexao->query("SELECT c_produto, nome_produto FROM produto LIMIT 1");

if ($res->num_rows > 0) {
    $prod = $res->fetch_assoc();
    $id = $prod['c_produto'];
    $nome = $prod['nome_produto'];

    // 2. Simulamos a lógica do adicionar_carrinho.php
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]++;
    } else {
        $_SESSION['carrinho'][$id] = 1;
    }

    echo "<h2>Sucesso!</h2>";
    echo "O produto <b>$nome (ID: $id)</b> foi adicionado ao seu carrinho virtual.<br>";
    echo "<a href='carrinho.php'>Ir para o Carrinho agora</a> | <a href='index.php'>Voltar ao Cardápio</a>";
} else {
    echo "<h2>Erro no Teste</h2>";
    echo "Não encontrei nenhum produto na sua tabela 'produto'. <br>";
    echo "Cadastre um produto no seu painel ADM primeiro!";
}
?>