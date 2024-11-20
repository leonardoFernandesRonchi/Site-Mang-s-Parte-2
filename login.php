<?php

  include_once("templates/header.php");
  require_once("globals.php");
  require_once("models/Message.php")


?>
<style>
#main-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 80vh;
  padding: 20px;
  background-image: url('background-manhwa.jpg'); 
  background-size: cover;
  background-position: center;
}

#auth-row {
  background-color: rgba(255, 255, 255, 0.9); 
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  border-radius: 10px;
  padding: 30px;
}

#login-container, #register-container {
  background-color: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  margin: 20px;
}

#login-container h2, #register-container h2 {
  font-family: 'Garamond', serif; 
  color: #4e5d6c;
  border-bottom: 2px solid #4e5d6c;
  padding-bottom: 10px;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  font-size: 1rem;
  font-weight: bold;
  color: #333;
}

.form-control {
  border: 2px solid #ddd;
  border-radius: 5px;
  padding: 10px;
}

.form-control:focus {
  border-color: #4e5d6c;
  box-shadow: 0 0 5px rgba(78, 93, 108, 0.2);
}

.btn.card-btn {
  background-color: #4e5d6c;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  font-weight: bold;
  transition: 0.3s;
  cursor: pointer;
}

.btn.card-btn:hover {
  background-color: #3b4b5b;
}

#register-container {
  border-left: 3px solid #4e5d6c;
}


@media (max-width: 768px) {
  #auth-row {
    flex-direction: column;
  }
}

</style>
<div id="main-container" class="container-fluid">
    <div class="col-md-12">
        <div class="row" id="auth-row">
            <div class="col-md-4" id="login-container">
                <h2>Entrar</h2>
                <form action="<?= $BASE_URL ?>auth_process.php" method="POST">
                    <input type="hidden" name="type" value="login">
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail">
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha:</label>
                        <input type="password" class="form-control" id="senha-login" name="senha" placeholder="Digite sua senha">
                    </div>
                    <input type="submit" class="btn card-btn" value="Entrar">
                </form>
            </div>

            <div class="col-md-4" id="register-container">
                <h2>Criar Conta</h2>
                <form action="<?= $BASE_URL ?>auth_process.php" method="POST">
                    <input type="hidden" name="type" value="register">
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" class="form-control" id="email-register" name="email" placeholder="Digite seu e-mail">
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha:</label>
                        <input type="password" class="form-control" id="senha-register" name="senha" placeholder="Digite sua senha">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirme a senha:</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Digite a senha novamente">
                    </div>
                    <div class="form-group">
                        <label for="nickname">Nickname:</label>
                        <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Digite seu nickname">
                    </div>
                    <input type="submit" class="btn card-btn" value="Registrar">
                </form>
            </div>
        </div>
    </div>
</div>


<?php include_once("templates/footer.php"); ?>  