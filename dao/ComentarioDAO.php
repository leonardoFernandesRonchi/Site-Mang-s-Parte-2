<?php

include_once("models/Comentario.php");
include_once("models/Message.php");
include_once("dao/UserDAO.php");

class ComentarioDAO implements ComentarioDAOInterface {

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url) {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }

    public function buildComentario($data) {
        $comentario = new Comentario();

        $comentario->id = $data['id'];
        $comentario->manhwa_id = $data['manhwa_id'];
        $comentario->usuario_id = $data['usuario_id'];
        $comentario->texto = $data['texto'];
        $comentario->data_adicao = $data['data_adicao'];

        return $comentario;
    }

    public function create(Comentario $comentario) {
        $stmt = $this->conn->prepare("INSERT INTO comentarios (manhwa_id, usuario_id, texto, data_adicao) VALUES (:manhwa_id, :usuario_id, :texto, NOW())");

        $stmt->bindParam(":manhwa_id", $comentario->manhwa_id);
        $stmt->bindParam(":usuario_id", $comentario->usuario_id);
        $stmt->bindParam(":texto", $comentario->texto);

        if ($stmt->execute()) {
            $this->message->setMessage("Comentário adicionado com sucesso!", "success", "manhwa.php?id={$comentario->manhwa_id}");
        } else {
            $this->message->setMessage("Erro ao adicionar comentário. Tente novamente.", "error", "manhwa.php?id={$comentario->manhwa_id}");
        }
    }

    public function getComentariosByManhwa($manhwa_id) {
        $stmt = $this->conn->prepare("SELECT * FROM comentarios WHERE manhwa_id = :manhwa_id ORDER BY data_adicao DESC");
        $stmt->bindParam(":manhwa_id", $manhwa_id);
        $stmt->execute();

        $comentarios = [];

        if ($stmt->rowCount() > 0) {
            $comentariosArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($comentariosArray as $data) {
                $comentarios[] = $this->buildComentario($data);
            }
        }

        return $comentarios;
    }

    public function hasUserCommented($manhwa_id, $usuario_id) {
        $stmt = $this->conn->prepare("SELECT * FROM comentarios WHERE manhwa_id = :manhwa_id AND usuario_id = :usuario_id");
        $stmt->bindParam(":manhwa_id", $manhwa_id);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
