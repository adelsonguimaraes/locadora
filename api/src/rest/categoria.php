<?php

require_once __DIR__ . "/../dao/categoria_dao.php";

$json = $_REQUEST;
if (empty($json)) $json = file_get_contents("php://input");
if (!is_array($json)) $json = json_decode($json, true);

('categoria_' . $json['metodo'])($json); // categoria_metodo($json)

function categoria_listar($json) {
    $categoria_dao = new categoria_dao();
    $resp = $categoria_dao->listar();
    echo json_encode($resp);
}

function categoria_cadastrar($json) {
    $data = $json['data'];
    $categoria_dao = new categoria_dao();
    $resp = $categoria_dao->cadastrar($data);
    echo json_encode($resp);
}

function categoria_atualizar($json) {
    $data = $json['data'];
    $categoria_dao = new categoria_dao();
    $resp = $categoria_dao->atualizar($data);
    echo json_encode($resp);
}

function categoria_remover ($json) {
    $id = $json['data'];
    $categoria_dao = new categoria_dao();
    $resp = $categoria_dao->remover($id);
    echo json_encode($resp);
}

?>