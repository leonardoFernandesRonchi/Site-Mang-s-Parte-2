<?php
session_start(); // Iniciar a sessão

require_once("db.php");
require_once("globals.php");
require_once("models/User.php");
require_once("dao/UserDAO.php");
require_once("models/Message.php");

$userDao = new UserDAO($conn, $BASE_URL);
$message = new Message($BASE_URL);

$type = filter_input(INPUT_POST, "type");

if ($type === "register") {
    $nickname = filter_input(INPUT_POST, "nickname");
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, "senha");
    $confirmPassword = filter_input(INPUT_POST, "confirm_password");
    $sobremim = "";  // Sobremim está vazio por padrão

    // Validação dos campos obrigatórios
    if ($nickname && $email && $password && $confirmPassword) {
        if ($password === $confirmPassword) {
            // Verifica se o email já está cadastrado
            if ($userDao->findByEmail($email) === false) {
                // Cria o usuário
                $user = new User();
                $userToken = $user->generateToken();
                $finalPassword = password_hash($password, PASSWORD_DEFAULT);

                $user->nickname = $nickname;
                $user->email = $email;
                $user->senha = $finalPassword;
                $user->token = $userToken;
                $user->sobremim = $sobremim;
                $user->data_criacao = date("Y-m-d H:i:s");

                // Criação do usuário no banco
                $userDao->create($user, true);
                $message->setMessage("Cadastro realizado com sucesso!", "success", "home.php");
                exit(); // Evitar que o código continue executando
            } else {
                $message->setMessage("E-mail já cadastrado.", "error", "back");
                exit();
            }
        } else {
            $message->setMessage("As senhas não conferem.", "error", "back");
            exit();
        }
    } else {
        $message->setMessage("Preencha todos os campos.", "error", "back");
        exit();
    }
} else if ($type === "login") {
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, "senha");

    if ($email && $password) {
        $authResult = $userDao->authenticateUser($email, $password);

        if ($authResult) {
            // Salva o nickname do usuário na sessão
            $_SESSION['user_nickname'] = $authResult->nickname; 
            $message->setMessage("Login realizado com sucesso!", "success", "home.php");
            exit();
        } else {
            $message->setMessage("Credenciais inválidas.", "error", "login.php");
            exit();
        }
    } else {
        $message->setMessage("Preencha todos os campos.", "error", "back");
        exit();
    }
}
?>
