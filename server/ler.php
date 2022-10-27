<?php

require('./http.php');
require('./repositorio.php');

class Ler extends Http
{
  private $repositorio;

  function __construct() {
    $this->repositorio = new Repositorio();
  }

  function execute() {
    $resultado = $this->repositorio->listarPessoasEFilhos();
    $pessoas = [];
    foreach($resultado as $chave => $valor) {
      $pessoaId = $valor['pessoa_id'];
      $filhos = isset($pessoas[$pessoaId]) ? $pessoas[$pessoaId]['filhos'] : [];
      if (isset($valor['nome_filho'])) {
        $filhos[] = $valor['nome_filho'];
      }
      $pessoas[$pessoaId] = [
        "nome" => $valor["nome"],
        "filhos" => $filhos,
      ];
    }
    $resposta = [];
    foreach($pessoas as $pessoaId => $valor) {
      $resposta[] = $valor;
    }
    $this->enviar(["pessoas" => $resposta]);
  }
}

$ler = new Ler();
$ler->execute();
