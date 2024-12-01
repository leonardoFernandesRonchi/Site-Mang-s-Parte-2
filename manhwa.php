<?php

require_once("templates/header.php");
require_once("models/User.php");
require_once("dao/UserDAO.php");

$server_name = "localhost";
$mysql_username = "root";
$mysql_password = "";
$db_name = "meuproprioprojeto2";
$userLoggedIn = isset($_SESSION['user_nickname']) ? $_SESSION['user_nickname'] : null;

try {
    $conn = new PDO("mysql:host=$server_name;port=3309;dbname=$db_name", $mysql_username, $mysql_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexão falhou: " . $e->getMessage());
}

function getRandomProfileImage() {
    $images = [
        "https://robohash.org/" . rand() . "?set=set4", 
        
    ];
    return $images[array_rand($images)];
}

if (isset($_GET['id'])) {
    $manhwa_id = (int) $_GET['id'];

    
    $stmt = $conn->prepare("SELECT * FROM manhwas WHERE id = :id");
    $stmt->bindParam(':id', $manhwa_id, PDO::PARAM_INT);
    $stmt->execute();
    $manhwa = $stmt->fetch(PDO::FETCH_OBJ);

    if ($manhwa) {
        echo "<div class='container mt-5'>
                <div class='row'>
                    <div class='col-md-6'>
                        <img src='" . htmlspecialchars($manhwa->image_url) . "' class='img-fluid rounded' alt='" . htmlspecialchars($manhwa->titulo) . "' style='max-width: 100%; height: auto;'>
                    </div>
                    <div class='col-md-6'>
                        <h1 class='display-4 text-primary'>" . htmlspecialchars($manhwa->titulo) . "</h1>
                        <p class='text-muted'>" . htmlspecialchars($manhwa->longa_descricao) . "</p>
                        <small class='text-secondary'>" . htmlspecialchars($manhwa->numeroscapitulo) . " capítulos</small>
                    </div>
                </div>";

        if ($userLoggedIn) {
            echo "<div class='row mt-5 mb-3'>
                    <div class='col-md-8 mx-auto'>
                        <h3 class='mb-4 text-primary'>Deixe um comentário</h3>
                        <form action='comentarios_action.php' method='POST' class='p-4 shadow-sm rounded' style='background-color: #f8f9fa;'>
                            <div class='form-group mb-3'>
                                <label for='comment' class='form-label fw-bold text-secondary'>Comentário</label>
                                <textarea id='comment' name='texto' class='form-control border-0 shadow-sm' rows='4' placeholder='Digite seu comentário' required style='background-color: #eef2f3;'></textarea>
                            </div>
                            <input type='hidden' name='manhwa_id' value='$manhwa_id'>
                            <button type='submit' class='btn btn-primary btn-lg w-100 mt-3'>Enviar</button>
                        </form>
                    </div>
                </div>";
        } else {
            echo "<p class='alert alert-info'>Você precisa estar logado para comentar.</p>";
        }

        $stmt = $conn->prepare("SELECT c.*, u.nickname FROM comentarios c INNER JOIN usuarios u ON c.usuario_id = u.id WHERE c.manhwa_id = :manhwa_id ORDER BY c.id DESC");
        $stmt->bindParam(':manhwa_id', $manhwa_id, PDO::PARAM_INT);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo "<div class='row mt-5'>
                <div class='col-md-8 mx-auto'>
                    <h3 class='mb-4 text-primary'>Comentários</h3>";

        if ($comments) {
            foreach ($comments as $comment) {
                echo "<div class='border rounded p-3 mb-3' style='background-color: #f8f9fa;'>
                        <div class='d-flex align-items-center mb-2'>
                            <img src='" . getRandomProfileImage() . "' alt='Avatar' class='rounded-circle me-2' style='width: 40px; height: 40px;'>
                            <h5 class='fw-bold text-secondary mb-0'>" . htmlspecialchars($comment->nickname) . "</h5>
                        </div>
                        <p>" . htmlspecialchars($comment->texto) . "</p>
                      </div>";
            }
        } else {
            echo "<p class='text-muted'>Nenhum comentário ainda. Seja o primeiro a comentar!</p>";
        }

        echo "</div>
              </div>
            </div>";

    } else {
        echo "<p>Manhwa não encontrado.</p>";
    }
} else {
    echo "<p>ID do manhwa não especificado.</p>";
}

require_once("templates/footer.php");

?>
