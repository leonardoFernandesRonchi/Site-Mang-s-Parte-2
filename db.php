<?php

  session_start();

  $db_name = 'meuproprioprojeto2';
  $db_host = '127.0.0.1:3309';
  $db_user = 'root';
  $db_pass = '';

  try{
  $conn = new PDO("mysql:dbname=".$db_name.";host=".$db_host, $db_user, $db_pass);
} catch (PDOException $e)
{
  echo "Erro na conexão: " . $e->getMessage();
}
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);