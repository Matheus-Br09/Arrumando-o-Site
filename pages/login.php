<?php
require_once __DIR__ . '/../php/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!empty($email) && !empty($senha)) {

        // --- PASSO 1: Tenta encontrar na tabela de ADM ---
        $sql_adm = $conexao->prepare("SELECT c_adm, nome, senha FROM adm WHERE email = ?");
        $sql_adm->bind_param("s", $email);
        $sql_adm->execute();
        $res_adm = $sql_adm->get_result();

        if ($res_adm->num_rows === 1) {
            $adm = $res_adm->fetch_assoc();
            // Verifica se a senha bate com o hash do ADM
            if (password_verify($senha, $adm['senha'])) {
                $_SESSION['usuario_id'] = $adm['c_adm'];
                $_SESSION['usuario_nome'] = $adm['nome'];
                $_SESSION['perfil'] = 'adm';
                header("Location: ../page%20admin/painel_adm.php");
                exit;

            }
        }

        // --- PASSO 2: Se não for ADM, tenta encontrar na tabela de CLIENTE ---
        $sql_cli = $conexao->prepare("SELECT c_cliente, nome, senha FROM cliente WHERE email = ?");
        $sql_cli->bind_param("s", $email);
        $sql_cli->execute();
        $res_cli = $sql_cli->get_result();

        if ($res_cli->num_rows === 1) {
            $cliente = $res_cli->fetch_assoc();
            // Verifica se a senha bate com o hash do Cliente
            if (password_verify($senha, $cliente['senha'])) {
                $_SESSION['usuario_id'] = $cliente['c_cliente'];
                $_SESSION['usuario_nome'] = $cliente['nome'];
                $_SESSION['perfil'] = 'cliente'; 
                header("Location: ../index.php"); 
                exit();
            }
        }

        // Se chegou aqui, as credenciais estão erradas
        $erro = "E-mail ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mi Patisserie</title>
    <link rel="stylesheet" href="../css_login/style.css?v=<?php echo time(); ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
</head>
<body>
    <main id="container">
        <div class="wrapper-login">
            <form action="./login.php" method="POST">
                <h1>Login</h1>
                
                <?php if(isset($erro)): ?>
                    <p style="color: #ff4d4d; text-align: center; font-size: 14px;"><?php echo $erro; ?></p>
                <?php endif; ?>

                <div class="input-box">
                    <input type="text" name="email" placeholder="E-mail" required>
                    <i class='bx bx-user' ></i>
                </div>

                <div class="input-box">
                    <input type="password" name="senha" placeholder="Senha" required>
                    <i class='bx bx-lock-alt' ></i>
                </div>

                <div class="remenber-forgot">
                    <label><input type="checkbox"> Lembre de mim </label>
                    <a href="#">Esqueceu a senha?</a>
                </div>

                <button type="submit" class="bnt"> Login </button>
            </form>
            <div class="register-link">
                <p>Não tem uma conta?
                <a href="javascript:void(0)" onclick="mostrarCadastro()">Registrar</a></p>
            </div>
        </div>

        <div class="wrapper-cadastro">
            <form action="../php/cadastro.php" method="POST">
                <h1>Cadastro</h1>
                <div class="input-box">
                    <input type="text" name="nome" placeholder="Nome" required>
                    <i class="bx bx-user"></i>
                </div>
                <div class="input-box">
                    <input type="text" name="email" placeholder="E-mail" required>
                    <i class="bx bx-envelope"></i>
                </div>
                <div class="input-box">
                    <input type="password" name="senha" placeholder="Senha" required>
                    <i class='bx bx-lock-alt'></i>
                </div>
                <button type="submit" class="bnt">Criar Conta</button>
            </form>
            <div class="register-link">
                <p>Já tem uma conta? <a href="javascript:void(0)" onclick="voltarLogin()">Login</a></p>
            </div>
        </div>
        <img src="../Imagens-Referencias/logo2.0.png" alt="logo" class="logo">
    </main>

    <script>
        function mostrarCadastro(){
            document.getElementById("container").classList.add("active")
        }
        function voltarLogin(){
            document.getElementById("container").classList.remove("active")
        }
    </script>
</body>
</html>