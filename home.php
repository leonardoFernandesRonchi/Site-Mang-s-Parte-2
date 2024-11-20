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

$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 15; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 0; 
$offset = $page * $limit;

$stmt = $conn->prepare("SELECT * FROM manhwas WHERE titulo LIKE :search LIMIT :limit OFFSET :offset");
$stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$manhwas = $stmt->fetchAll(PDO::FETCH_OBJ); 

$stmt_count = $conn->prepare("SELECT COUNT(*) as total FROM manhwas WHERE titulo LIKE :search");
$stmt_count->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$stmt_count->execute();
$total_manhwas = $stmt_count->fetch(PDO::FETCH_OBJ)->total;

?>

<style>
    .card-img-top {
        width: 100%;
        height: auto;
        max-height: 200px;
        object-fit: cover;
    }
    .search-container {
        margin: 20px 0;
        text-align: center;
    }
    .search-form {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }
    .search-form input[type="text"] {
        flex: 1;
        max-width: 300px;
    }
    .search-form button {
        flex-shrink: 0;
    }
    .container .row {
        display: flex;
        flex-wrap: wrap;
    }
    .container .row > [class*='col-'] {
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }
    .col-md-4, .col-sm-6, .col-xs-12 {
        flex: 0 0 33.3333%;
        max-width: 33.3333%;
        box-sizing: border-box;
    }
</style>

<h1 class="text-center">Mangás Recentes</h1>

<div class="container search-container">
    <form method="GET" action="" class="search-form">
        <input type="text" name="search" placeholder="Buscar manhwa..." class="form-control" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Pesquisar</button>
    </form>
</div>

<div class="container">
    <div class="row">
        <?php if ($manhwas): ?>
            <?php foreach ($manhwas as $manhwa): ?>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="card mb-4 border-0">
                        <img src="<?php echo $manhwa->image_url; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($manhwa->titulo); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($manhwa->titulo); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($manhwa->descricao); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><?php echo htmlspecialchars($manhwa->numeroscapitulo); ?> capítulos</small>
                                <a href="manhwa.php?id=<?php echo $manhwa->id; ?>" class="btn btn-warning btn-sm">Leia mais</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Nenhum manhwa encontrado.</p>
        <?php endif; ?>
    </div>
</div>

<div class="container text-center mb-3">
    <div class="btn-group" role="group" aria-label="Navegação">
        <?php if ($page > 0): ?>
            <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>" class="btn btn-secondary">
                &#x2190; Voltar
            </a>
        <?php endif; ?>
        <?php if ($total_manhwas > ($page + 1) * $limit): ?>
            <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>" class="btn btn-primary">
                Avançar &#x2192;
            </a>
        <?php endif; ?>
    </div>
</div>

<?php require_once("templates/footer.php"); ?>
