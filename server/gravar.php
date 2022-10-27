<?php

require('./http.php');
require('./repositorio.php');

class Gravar extends Http
{
  private $repositorio;

  function __construct() {
    $this->repositorio = new Repositorio();
  }

  function execute() {
    $data = $this->getJson();
    $this->repositorio->limparBanco();

    foreach($data['pessoas'] as $chave => $pessoa) {
      $pessoaId = $this->repositorio->salvarPessoa($pessoa['nome']);

      foreach($pessoa['filhos'] as $nomeDoFilho) {
        $this->repositorio->salvarFilho($pessoaId, $nomeDoFilho);
      }
    }
    
    $this->enviar(["sucesso" => true]);
  }
}

$gravar = new Gravar();
$gravar->execute();

