<?php

class Comentario {

    public $id;
    public $manhwa_id;
    public $usuario_id;
    public $texto;
    public $data_adicao;

}

interface ComentarioDAOInterface {

    public function buildComentario($data);
    public function create(Comentario $comentario);
    public function getComentariosByManhwa($manhwa_id);
    public function hasUserCommented($manhwa_id, $usuario_id);

}
