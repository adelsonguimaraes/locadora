<?php

date_default_timezone_set('America/Manaus');

class categoria_dao {
    private $path = "";
    private $db = "";

    public function __construct () {
        $this->path = __DIR__ . "/../../../db/categoria_db.json";
        $result = @file_get_contents($this->path);
        if ($result===false) die(json_decode(array("success"=>false, "msg"=>"Arquivo {$this->path} não localizado!")));
        $this->db = json_decode($result, true);
    }

    function writeFile ($data, $metodo) {
        $response = array ("success"=> false, "data"=> "", "msg"=> "");
        $result = @file_put_contents($this->path, json_encode($data, JSON_PRETTY_PRINT));
        if ($result===false) {
            $response['success'] = false;
            $response['msg'] = "[{$metodo}]: Arquivo não encontrado.";
        }else{
            $response['success'] = true;
            $response['data'] = true;
        }
        return $response;
    }

    function listar () {
        $response = array("success"=>true, "data"=>$this->db, "msg"=>"");
        return $response;
    }

    function cadastrar ($data) {
        $response = array ("success"=> false, "data"=> "", "msg"=> "");

        // incremantar id
        $id = 0;
        if (empty($this->db)) {
            $id = 1;
        }else{
            $id = $this->db[count($this->db)-1]['id'] + 1;
        }

        $obj = array (
            "id"=> $id,
            "descricao"=> $data['descricao'],
            "datacadastro"=> date('Y-m-d H:i:s'),
            "dataedicao"=> ""
        );

        array_push($this->db, $obj);

        $resp = $this->writeFile($this->db, "Categoria:Cadastrar");
        if (!$resp['success']) return $resp;

        $response['success'] = true;
        $response['data'] = $id;

        return $response;
    }

    function atualizar ($data) {
        $response = array ("success"=> false, "data"=> "", "msg"=> "");

        $obj = array(
            "id"=> intval($data['id']),
            "descricao"=> $data['descricao'],
            "datacadastro"=> "",
            "dataedicao"=> date('Y-m-d H:i:s')
        );

        $array = array();
        foreach ($this->db as $key) {
            if (intval($key['id']) === intval($obj['id'])) {
                $obj['datacadastro'] = $key['datacadastro'];
                array_push($array, $obj);
            }else{
                array_push($array, $key);
            }
        }

        $resp = $this->writeFile($array, "Categoria:Atualizar");
        if (!$resp['success']) return $resp;

        $response['success'] = true;
        $response['data'] = true;

        return $response;
    }

    function remover ($id) {
        $response = array ("success"=> false, "data"=> "", "msg"=> "");

        $array = array();
        foreach($this->db as $key) {
            if (intval($key['id'] !== intval($id))) {
                array_push($array, $key);
            }
        }

        $resp = $this->writeFile($array, "Categoria:Remover");
        if (!$resp['success']) return $resp;

        $response['success'] = true;
        $response['data'] = true;

        return $response;
    }
}

?>