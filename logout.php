<?php
session_start();
session_destroy(); // Encerra a sessão
header("Location: home.php"); // Redireciona para a página inicial
exit;
?>