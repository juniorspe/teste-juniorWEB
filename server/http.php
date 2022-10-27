<?php

abstract class Http 
{
  function getJson() {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    return $data;
  }

  function enviar($dados) {
    echo json_encode($dados);
  }
}
