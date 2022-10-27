<?php

require('database.php');

class Repositorio 
{
  private $conexao;

  function __construct() {
    $database = new Database();
    $database->conectar();
    $this->conexao = $database->getConexao();
  }

  function limparBanco() {
    $queryDeletaTabelaFilho = "DELETE FROM filho;";
    $stmt= $this->conexao->prepare($queryDeletaTabelaFilho);
    $stmt->execute();

    $queryDeletaTabelaPessoa = "DELETE FROM pessoa;";
    $stmt= $this->conexao->prepare($queryDeletaTabelaPessoa);
    $stmt->execute();
  }

  function salvarPessoa($nome) {
    $query = "INSERT INTO pessoa(nome) VALUES(?);";
    $stmt= $this->conexao->prepare($query);
    $stmt->execute([$nome]);
    return $this->pegarUltimoIdDePessoa();
  }

  function salvarFilho($pessoaId, $nomeDoFilho) {
    $query = "INSERT INTO filho(pessoa_id, nome) VALUES(?,?);";
    $stmt= $this->conexao->prepare($query);
    $stmt->execute([$pessoaId, $nomeDoFilho]);
  }

  function pegarUltimoIdDePessoa() {
    $query = "SELECT pessoa_id FROM pessoa ORDER BY pessoa_id DESC LIMIT 1;";
    $stmt= $this->conexao->prepare($query);
    $stmt->execute();
    return $stmt->fetch()['pessoa_id'];
  }

  function listarPessoasEFilhos() {
    $query = "SELECT p.*, f.nome as nome_filho FROM pessoa p LEFT JOIN filho f ON p.pessoa_id = f.pessoa_id;";
    $stmt= $this->conexao->prepare($query);
    $stmt->execute();
    $resultado = [];
    while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $resultado[] = $linha;
    }
    return $resultado;
  }
}
