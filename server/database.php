<?php

class Database
{
  private $pdo;

  function conectar() {
    try {
      $host = "localhost";
      $db = "pessoas";
      $user = "root";
      $password = "170713";
      $connectionString = "mysql:host=$host;port=33300;dbname=$db;";
      $this->pdo = new PDO($connectionString, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (PDOException $e) {
      die($e->getMessage());
    }   
  }

  function getConexao() {
    return $this->pdo;
  }
}
