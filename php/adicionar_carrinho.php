<?php
session_start();

// Verifica se o ID do produto foi enviado
if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];

    // Se o carrinho ainda não existir, cria um array vazio
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    // Se o produto já estiver no carrinho, aumenta a quantidade
    if (isset($_SESSION['carrinho'][$id_produto])) {
        $_SESSION['carrinho'][$id_produto]++;
    } else {
        // Se for novo, adiciona com quantidade 1
        $_SESSION['carrinho'][$id_produto] = 1;
    }
}

// Volta para o cardápio
header("Location: index.php");
exit();