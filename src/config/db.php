<?php

class db {
private  $user = 'root';
private  $pass = 'tiger321';
private  $host = 'localhost';
private  $dbname = 'api';

public function connect() {
    try {
      $dsn = "mysql:host=$this->host;dbname=$this->dbname";
      $pdo = new PDO($dsn, $this->user, $this->pass);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $pdo;
    } catch (PDOException $e) {
      echo 'Opps Somrhing went Wrong' . $e->getMessage();
    }
  }
}
