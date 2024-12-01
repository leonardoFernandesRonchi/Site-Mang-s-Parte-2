<?php
session_start(); // Inicia a sessão no início do script

require_once("globals.php");
require_once("db.php");
require_once("models/Message.php");
require_once("dao/ComentarioDAO.php");
require_once("models/User.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);
$comentarioDao = new ComentarioDAO($conn, $BASE_URL);

// Variável para verificar o estado do usuário logado
$usuario_id = $_SESSION['user_id'] ?? null;

// Verificar se o usuário está logado
if (!$usuario_id) {
    $message->setMessage("Você precisa estar logado para comentar.", "error", "home.php");
    exit;
}

$texto = filter_input(INPUT_POST, "texto", FILTER_SANITIZE_SPECIAL_CHARS);
$manhwa_id = filter_input(INPUT_POST, "manhwa_id", FILTER_VALIDATE_INT);

if (!$texto) {
    $message->setMessage("O campo de texto do comentário está vazio.", "error", "manhwa.php?id={$manhwa_id}");
    exit;
}

if (!$manhwa_id) {
    $message->setMessage("ID do manhwa inválido.", "error", "home.php");
    exit;
}

// Criar um objeto de comentário
$comentario = new Comentario();
$comentario->texto = $texto;
$comentario->manhwa_id = $manhwa_id;
$comentario->usuario_id = $usuario_id;

// Tentar salvar o comentário
if ($comentarioDao->create($comentario)) {
    $message->setMessage("Comentário adicionado com sucesso!", "success", "manhwa.php?id={$manhwa_id}");
} else {
    $message->setMessage("Erro ao adicionar comentário. Tente novamente.", "error", "manhwa.php?id={$manhwa_id}");
}
?>