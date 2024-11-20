<?php

class User {
    public $id;
    public $nickname;
    public $email;
    public $senha;
    public $sobremim;
    public $token;
    public $data_criacao;

    public function getNickName() {
        return $this->nickname;
    }

    public function generateToken() {
        return bin2hex(random_bytes(50));  
    }

    public function generateImageName() {
        return bin2hex(random_bytes(60)) . ".jpg"; 
    }
  
  
  }

  interface UserDAOInterface {

    public function buildUser($data);
    public function create(User $user, $authUser = false);
    public function update(User $user);
    public function findByToken($token);
    public function verifyToken($protected = true);
    public function setTokenToSession($token, $redirect = true);
    public function authenticateUser($email, $password);
    public function findByEmail($email);
    public function findById($id); 
    public function changePassword(User $user); 

}
?>