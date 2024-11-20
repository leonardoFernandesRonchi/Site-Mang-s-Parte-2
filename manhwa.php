<?php
require_once("templates/header.php");


$server_name = "localhost";
$mysql_username = "root";
$mysql_password = "";
$db_name = "meuproprioprojeto2";

try {
    $conn = new PDO("mysql:host=$server_name;port=3309;dbname=$db_name", $mysql_username, $mysql_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexão falhou: " . $e->getMessage());
}

if (isset($_GET['id'])) {
    $manhwa_id = (int) $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM manhwas WHERE id = :id");
    $stmt->bindParam(':id', $manhwa_id, PDO::PARAM_INT);
    $stmt->execute();
    $manhwa = $stmt->fetch(PDO::FETCH_OBJ);

    if ($manhwa) {

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE nickname = :nickname");
    $stmt->bindParam(':nickname', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $usuario_id = $user['id'];

        $stmt = $conn->prepare("INSERT INTO comentarios (manhwa_id, usuario_id, texto, data_adicao) VALUES (:manhwa_id, :usuario_id, :texto, NOW())");
        $stmt->bindParam(':manhwa_id', $manhwa_id, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':texto', $comment, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            echo "<p class='alert alert-success'>Comentário enviado com sucesso!</p>";
        } else {
            echo "<p class='alert alert-danger'>Erro ao enviar comentário. Tente novamente.</p>";
        }
    } else {
        echo "<p class='alert alert-danger'>Usuário não encontrado.</p>";
    }
}

        $stmt = $conn->prepare("SELECT * FROM comentarios WHERE manhwa_id = :manhwa_id ORDER BY id DESC");
        $stmt->bindParam(':manhwa_id', $manhwa_id, PDO::PARAM_INT);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_OBJ);

        ?>

        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6">
                    <img src="<?php echo htmlspecialchars($manhwa->image_url); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($manhwa->titulo); ?>" style="max-width: 100%; height: auto;">
                </div>
                <div class="col-md-6">
                    <h1 class="display-4 text-primary"><?php echo htmlspecialchars($manhwa->titulo); ?></h1>
                    <p class="text-muted"><?php echo htmlspecialchars($manhwa->longa_descricao); ?></p>
                    <small class="text-secondary"><?php echo htmlspecialchars($manhwa->numeroscapitulo); ?> capítulos</small>
                </div>
            </div>

            <div class="row mt-5 mb-3">
                <div class="col-md-8 mx-auto">
                    <h3 class="mb-4 text-primary">Deixe um comentário</h3>
                    <form action="" method="POST" class="p-4 shadow-sm rounded" style="background-color: #f8f9fa;">
                        <div class="form-group mb-3">
                            <label for="username" class="form-label fw-bold text-secondary">Nome</label>
                            <input type="text" id="username" name="username" class="form-control border-0 shadow-sm" placeholder="Digite seu nome" required style="background-color: #eef2f3;">
                        </div>
                        <div class="form-group mb-3">
                            <label for="comment" class="form-label fw-bold text-secondary">Comentário</label>
                            <textarea id="comment" name="comment" class="form-control border-0 shadow-sm" rows="4" placeholder="Digite seu comentário" required style="background-color: #eef2f3;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-3">Enviar</button>
                    </form>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-8 mx-auto">
                    <h3 class="mb-4 text-primary">Comentários</h3>
                    <?php if ($comments): ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="border rounded p-3 mb-3" style="background-color: #f8f9fa;">
                                <h5 class="fw-bold text-secondary"><?php echo htmlspecialchars($comment->username); ?></h5>
                                <p><?php echo htmlspecialchars($comment->comment); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Nenhum comentário ainda. Seja o primeiro a comentar!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
    } else {
        echo "<p>Manhwa não encontrado.</p>";
    }
} else {
    echo "<p>ID do manhwa não especificado.</p>";
}

require_once("templates/footer.php");
?>
