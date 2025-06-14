<?php

class ClienteController {
    private $db;
    private $clienteDAO;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->clienteDAO = new ClienteDAO($this->db);
    }
    
    // mostrar clientes
    public function show($id) {
    $cliente = $this->clienteDAO->readById($id);

    if ($cliente) {
        header('Content-Type: application/json');
        echo json_encode($cliente);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Cliente não encontrado."]);
    }
}



    // listar todos os clientes
    public function index() {
        $stmt = $this->clienteDAO->readAll();
        $clientes = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clientes[] = $row;
        }
        
        header('Content-Type: application/json');
        echo json_encode($clientes);
    }
    
    // criar novo cliente
    public function store() {
        $data = json_decode(file_get_contents("php://input"));
        
        $cliente = new Cliente(
            $data->nome,
            $data->email,
            $data->telefone,
            $data->endereco
        );
        
        if ($this->clienteDAO->create($cliente)) {
            http_response_code(201);
            echo json_encode(["message" => "Cliente criado com sucesso."]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "Erro ao criar cliente."]);
        }
    }
    
    // atualizar cliente
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));
        
        $cliente = new Cliente(
            $data->nome,
            $data->email,
            $data->telefone,
            $data->endereco
        );
        $cliente->setId($id);
        
        if ($this->clienteDAO->update($cliente)) {
            http_response_code(200);
            echo json_encode(["message" => "Cliente atualizado com sucesso."]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "Erro ao atualizar cliente."]);
        }
    }
    
    // deletar cliente
    public function destroy($id) {
        if ($this->clienteDAO->delete($id)) {
            http_response_code(200);
            echo json_encode(["message" => "Cliente deletado com sucesso."]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "Erro ao deletar cliente."]);
        }
    }
}

?>