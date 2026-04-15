<?php
session_start();
include __DIR__ . '/../php/config.php';

// 1. SEGURANÇA: Verifica se o ID foi enviado pela URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // 2. OPCIONAL: Buscar o nome da imagem para apagar o arquivo da pasta img/
    $res = $conexao->query("SELECT imagem FROM produto WHERE c_produto = $id");
    if ($prod = $res->fetch_assoc()) {
        $foto = "img/" . $prod['imagem'];
        if (file_exists($foto) && !empty($prod['imagem'])) {
            unlink($foto); // Isso apaga o arquivo físico da pasta
        }
    }

    // 3. Executa o comando DELETE no banco de dados
    $sql = "DELETE FROM produto WHERE c_produto = $id";

    if ($conexao->query($sql)) {
        // Redireciona e PARA o script imediatamente
        header("Location: ./page%20admin/painel_adm.php?msg=excluido");
        exit(); 
    } else {
        echo "Erro ao excluir: " . $conexao->error;
    }
} else {
    header("Location: ./page%20admin/painel_adm.php");
    exit();
}