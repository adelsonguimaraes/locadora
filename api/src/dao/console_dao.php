<?php

class console_dao {
    private $path = "";
    private $db = "";

    public function __construct () {
        $this->path = __DIR__ . "/../../../db/console_db.json";
        $result = @file_get_contents($this->path);
        if ($result===false) die(json_encode(array('success'=>false, 'msg'=>"Arquivo não {$this->path} localizado!")));
        $this->db = json_decode($result, true);
    }

    function writeFile ($data, $metodo) {
        $response = array("success"=>false, "data"=>"", "msg"=>"");
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
        $response = array("success"=>false, "data"=>"", "msg"=>"");
        
        // incrementando o id
        $id = 0;
        if (empty($this->db)) {
            $id = 1;
        }else{
            $id = $this->db[count($this->db)-1]['id'] + 1;
        }

        // setar o nosso id do obj
        $obj = array(
            "id"=> $id,
            "nome"=> $data['nome'],
            "valor_hora"=> floatval($data['valor_hora'])
        );

        // adicionando novo obj dentro no final do array
        array_push($this->db, $obj);

        $resp = $this->writeFile($this->db, "Console::Cadastrar");
        if (!$resp['success']) return $resp;

        $response['success'] = true;
        $response['data'] = $id;

        return $response;
    }

    function atualizar ($data) {
        $response = array("success"=>false, "data"=>"", "msg"=>"");

        // tratando
        $obj = array(
            "id"=> intval($data['id']),
            "nome"=> $data['nome'],
            "valor_hora"=> floatval($data['valor_hora'])
        );

        // consultar o obj no banco
        $array = array();
        foreach ($this->db as $key) {
            if (intval($key['id']) === intval($obj['id'])) {
                array_push($array, $obj);
            }else{
                array_push($array, $key);
            }
        }

        $resp = $this->writeFile($array, "Console::Atualizar");
        if (!$resp['success']) return $resp;

        $response['success'] = true;
        $response['data'] = true;

        return $response;
    }

    function remover ($id) {
        $response = array("success"=>false, "data"=>"", "msg"=>"");

        // consultar o obj no banco
        $array = array();
        foreach ($this->db as $key) {
            if (intval($key['id']) !== intval($id)) {
                array_push($array, $key);
            }
        }

        // escrever no banco
        $resp = $this->writeFile($array, "Console::Remover");
        if (!$resp['success']) return $resp;

        $response['success'] = true;
        $response['data'] = true;

        return $response;
    }

}

?>