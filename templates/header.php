<?php
// Inicia a sessão
session_start();



// Inclui arquivos necessários
include_once("globals.php");
include_once("models/Message.php");

// Instancia a classe de mensagens e busca mensagem flash
$message = new Message($BASE_URL);
$flashMessage = $message->getMessage();

// Checa se o usuário está logado
$userLoggedIn = isset($_SESSION['user_nickname']) ? $_SESSION['user_nickname'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>MeusManhwas</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" defer></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Estilos personalizados para a navbar */
        .navbar {
            border: none;
            width: 100%;
        }
        .navbar-brand {
            cursor: default;
        }
        .navbar-brand:hover {
            color: #fff;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="navbar-brand">MeusManhwas</span>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li><a href="home.php">Home</a></li>
                <li><a href="forum.php">Fórum</a></li>
                <li><a href="manhwas.php">Manhwas</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if ($userLoggedIn): ?>
                    <li><span class="navbar-text">Olá, <?= htmlspecialchars($userLoggedIn); ?></span></li>
                    <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php"><span class="glyphicon glyphicon-user"></span> Login</a></li>
                    <li><a href="register.php"><span class="glyphicon glyphicon-log-in"></span> Registrar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<?php if (!empty($flashMessage["msg"])): ?>
    <div class="msg-container">
        <p class="msg <?= $flashMessage["type"] ?>"><?= htmlspecialchars($flashMessage["msg"]); ?></p>
    </div>
    <?php $message->clearMessage(); ?>
<?php endif; ?>
