<?php

/* Trata request */
$json = $_REQUEST; // post/get/put/delete
if (empty($json)) $json = file_get_contents("php://input");
if (!is_array($json)) $json = json_decode($json, true);

// invocando o metodo
$json['metodo']($json);

function listar ($json) {
    $response = array("success"=>false, "data"=>"", "msg"=>"");

    $data = @file_get_contents(__DIR__ . "/../../../db/console_db.json");
    if ($data===false) {
        $response['success'] = false;
        $response['msg'] = "O arquivo não foi encontrado!";
        die (json_encode($response));
    }

    $data = json_decode($data);

    // tratamento

    $response['success'] = true;
    $response['data'] = $data;

    echo json_encode($response);
}

?>