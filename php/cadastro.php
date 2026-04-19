<?php

//Incluindo o arquivo de configuração para estabelecer a conexão com o banco de dados

require_once __DIR__ . '/../php/config.php';

// Verificando se recebemos os dados do formulário e se é post o metodo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Se o metodo é post, receber os dados do formulario

    $nome =  $_POST['nome'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // fazer uma validação simples para verificar se os campos não estão vazios

    if (empty($nome) || empty($email) || empty($senha)) {
        echo "Por favor, preencha todos os campos.";
        exit;
    }else{

        // 2. VERIFICAÇÃO: O usuário já existe?

        $check_sql = $conexao->prepare("SELECT email FROM cliente WHERE email = ?");
        $check_sql->bind_param("s", $email);
        $check_sql->execute();
        $resultado = $check_sql->get_result();

        if ($resultado->num_rows > 0) {

        // Se encontrou algum registro, o usuário já existe

        echo "Erro: Este e-mail já está cadastrado!";
        $check_sql->close();

    }   else {
        // 3. Se não existe, prossegue com o cadastro
        $check_sql->close(); // Fecha a consulta de verificação

        //preparar o comando sql para inserir os dados do usuário no banco de dados

        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = $conexao->prepare("INSERT INTO cliente (nome,email, senha) VALUES (?, ?, ?)");
        $sql->bind_param("sss", $nome, $email, $senha_hash);

        // Executar o comando SQL

        if ($sql->execute()) {
            echo "<script>alert('Usuário cadastrado com sucesso');</script>";
            // sleep(2);
            header('Location: ../pages/login.php');
            exit();
        } else {
            echo "Erro ao cadastrar: " . $sql->error;
        }

        // fechar a conexão com o banco de dados

        $sql->close();
        }
    }
}

?>