<?php
// Configurações
$servidor = "localhost";
$usuario  = "root";
$senha    = "";
$banco    = "mi_patisserie_test"; // Verifique se o nome está correto no seu MySQL

// Criando a conexão (Estilo Orientado a Objetos)
$conexao = new mysqli($servidor, $usuario, $senha, $banco);

// Verificando a conexão
if ($conexao->connect_error) {
    // Em produção, não exiba o erro detalhado para o usuário por segurança
    die("Falha na conexão: " . $conexao->connect_error);
}

// Define o charset para evitar problemas com acentos (ex: cafés, pães)
$conexao->set_charset("utf8mb4");

// echo "Conexão estabelecida com sucesso!";
?>