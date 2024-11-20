<?php

class UserDAO implements UserDAOInterface {
    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url) {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);  
    }

    public function buildUser($data) {
        $user = new User();
        $user->id = $data["id"];
        $user->nickname = $data["nickname"];
        $user->email = $data["email"];
        $user->senha = $data["senha"];
        $user->sobremim = $data["sobremim"];
        $user->token = $data["token"] ?? null;
        $user->data_criacao = $data["data_criacao"];
        return $user;
    }

    public function create(User $user, $authUser = false) {
        try {
            
            $stmt = $this->conn->prepare("INSERT INTO usuarios (nickname, email, senha, sobremim, data_criacao) 
                                         VALUES (:nickname, :email, :senha, :sobremim, :data_criacao)");
            $stmt->bindParam(":nickname", $user->nickname);
            $stmt->bindParam(":email", $user->email);
            $stmt->bindParam(":senha", $user->senha);
            $stmt->bindParam(":sobremim", $user->sobremim);
            $stmt->bindParam(":data_criacao", $user->data_criacao);

            $stmt->execute();

            if ($authUser) {
                $this->setTokenToSession($user->token);
            }

        } catch (PDOException $e) {
            $this->message->setMessage("Erro ao criar o usuário: " . $e->getMessage(), "error", "auth.php");
        }
    }

    public function findByEmail($email) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch();
                return $this->buildUser($data); 
            }
            return false;

        } catch (PDOException $e) {
            $this->message->setMessage("Erro ao buscar usuário: " . $e->getMessage(), "error", "auth.php");
            return false;
        }
    }

    public function findByToken($token) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE token = :token");
            $stmt->bindParam(":token", $token);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch();
                return $this->buildUser($data);
            }
            return false;

        } catch (PDOException $e) {
            $this->message->setMessage("Erro ao buscar usuário por token: " . $e->getMessage(), "error", "auth.php");
            return false;
        }
    }

    public function findById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch();
                return $this->buildUser($data);
            }
            return false;

        } catch (PDOException $e) {
            $this->message->setMessage("Erro ao buscar usuário por ID: " . $e->getMessage(), "error", "auth.php");
            return false;
        }
    }

    public function authenticateUser($email, $password) {
        try {
            $user = $this->findByEmail($email);
    
            if ($user && password_verify($password, $user->senha)) {
                $token = $user->generateToken();
                $this->setTokenToSession($token, false);
                $user->token = $token;
    
                $this->update($user);
                return $user; // Retorna o objeto do usuário
            }
            return false;
    
        } catch (PDOException $e) {
            $this->message->setMessage("Erro ao autenticar o usuário: " . $e->getMessage(), "error", "auth.php");
            return false;
        }
    }

    public function update(User $user) {
        try {
            $stmt = $this->conn->prepare("UPDATE usuarios SET nickname = :nickname, email = :email, sobremim = :sobremim, token = :token WHERE id = :id");

            $stmt->bindParam(":nickname", $user->nickname);
            $stmt->bindParam(":email", $user->email);
            $stmt->bindParam(":sobremim", $user->sobremim);
            $stmt->bindParam(":token", $user->token);
            $stmt->bindParam(":id", $user->id);

            $stmt->execute();

        } catch (PDOException $e) {
            $this->message->setMessage("Erro ao atualizar os dados do usuário: " . $e->getMessage(), "error", "editprofile.php");
        }
    }

    public function changePassword(User $user) {
        try {
            $stmt = $this->conn->prepare("UPDATE usuarios SET senha = :senha WHERE id = :id");
            $stmt->bindParam(":senha", $user->senha);
            $stmt->bindParam(":id", $user->id);
            $stmt->execute();

            $this->message->setMessage("Senha alterada com sucesso!", "success", "editprofile.php");
        } catch (PDOException $e) {
            $this->message->setMessage("Erro ao alterar a senha: " . $e->getMessage(), "error", "editprofile.php");
        }
    }

    public function destroyToken() {
        $_SESSION["token"] = "";
        $this->message->setMessage("Você fez logout com sucesso!", "success", "index.php");
    }

    public function setTokenToSession($token, $redirect = true) {
        $_SESSION["token"] = $token;

        if ($redirect) {
            $this->message->setMessage("Seja bem-vindo!", "success", "home.php");
        }
    }

    public function verifyToken($protected = true) {
        if (!empty($_SESSION["token"])) {
            $token = $_SESSION["token"];
            $user = $this->findByToken($token);

            if ($user) {
                return $user;
            } else if ($protected) {
                $this->message->setMessage("Faça a autenticação para acessar esta página.", "error", "index.php");
            }
        } else {
            return false;
        }
    }

    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}

?>
