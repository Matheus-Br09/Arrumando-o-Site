<?php
session_start();
session_destroy(); // Mata todas as variáveis de sessão
header("Location: ../pages/login.php");
exit();
?>