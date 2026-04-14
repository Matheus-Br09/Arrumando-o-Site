<?php
session_start();
session_destroy(); // Mata todas as variáveis de sessão
header("Location: login.php");
exit();
?>