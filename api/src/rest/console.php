<?php

require_once __DIR__ . "/../dao/console_dao.php";

/* Trata request */
$json = $_REQUEST; // post/get/put/delete
if (empty($json)) $json = file_get_contents("php://input");
if (!is_array($json)) $json = json_decode($json, true);

// invocando o metodo
('console_' . $json['metodo'])($json); // console_metodo($json)

function console_listar () {
    $console_dao = new console_dao();
    $resp = $console_dao->listar();
    echo json_encode($resp);
}

function console_cadastrar ($json) {
    $data = $json['data'];
    $console_dao = new console_dao();
    $resp = $console_dao->cadastrar($data);
    echo json_encode($resp);
}

function console_atualizar ($json) {
    $data = $json['data'];
    $console_dao = new console_dao();
    $resp = $console_dao->atualizar($data);
    echo json_encode($resp);
}

function console_remover ($json) {
    $id = $json['data'];
    $console_dao = new console_dao();
    $resp = $console_dao->remover($id);
    echo json_encode($resp);
}

?>