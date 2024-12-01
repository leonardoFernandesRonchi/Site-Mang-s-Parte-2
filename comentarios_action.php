<?php
session_start(); 

require_once("globals.php");
require_once("db.php");
require_once("models/Message.php");
require_once("dao/ComentarioDAO.php");
require_once("models/User.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);
$comentarioDao = new ComentarioDAO($conn, $BASE_URL);


$usuario_id = $_SESSION['user_id'] ?? null;

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

$comentario = new Comentario();
$comentario->texto = $texto;
$comentario->manhwa_id = $manhwa_id;
$comentario->usuario_id = $usuario_id;

if ($comentarioDao->create($comentario)) {
    $message->setMessage("Comentário adicionado com sucesso!", "success", "manhwa.php?id={$manhwa_id}");
} else {
    $message->setMessage("Erro ao adicionar comentário. Tente novamente.", "error", "manhwa.php?id={$manhwa_id}");
}
?>