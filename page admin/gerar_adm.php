<?php
require_once 'C:\xampp\htdocs\teste\php\config.php';

$nome = "Matheus";
$email = "admin@email.com";
$senha_limpa = "123456"; 
$senha_hash = password_hash($senha_limpa, PASSWORD_DEFAULT);

$sql = $conexao->prepare("INSERT INTO adm (nome, email, senha) VALUES (?, ?, ?)");
$sql->bind_param("sss", $nome, $email, $senha_hash);

if ($sql->execute()) {
    echo "Administrador criado com sucesso! <br>";
    echo "Email: admin@email.com <br>";
    echo "Senha: 123456";
} else {
    echo "Erro ao criar: " . $conexao->error;
}
?>