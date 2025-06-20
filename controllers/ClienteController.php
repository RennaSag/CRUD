<?php
class ClienteController {
    private $db;
    private $clienteDAO;
    
    public function __construct() {
        try {
            $database = new Database();
            
            // criar banco e tabela se n existirem ainda
            $database->createDatabaseAndTable();
            
            $this->db = $database->getConnection();
            $this->clienteDAO = new ClienteDAO($this->db);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Erro de conexão: " . $e->getMessage()]);
            exit;
        }
    }
    
    // mostrar algum cliente específico
    public function show($id) {
        try {
            if (!is_numeric($id) || $id <= 0) {
                http_response_code(400);
                echo json_encode(["message" => "ID inválido"]);
                return;
            }
            
            $cliente = $this->clienteDAO->readById($id);
            
            if ($cliente) {
                header('Content-Type: application/json');
                echo json_encode($cliente);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Cliente não encontrado"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Erro ao buscar cliente: " . $e->getMessage()]);
        }
    }
    
    // lista todos os clientes
    public function index() {
        try {
            $stmt = $this->clienteDAO->readAll();
            $clientes = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $clientes[] = $row;
            }
            
            header('Content-Type: application/json');
            echo json_encode($clientes);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Erro ao listar clientes: " . $e->getMessage()]);
        }
    }
    
    // criar um novo cliente
    public function store() {
        try {
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(["message" => "JSON inválido"]);
                return;
            }
            
            if (!isset($data['nome']) || !isset($data['email']) || !isset($data['telefone']) || !isset($data['endereco'])) {
                http_response_code(400);
                echo json_encode(["message" => "Dados obrigatórios não fornecidos"]);
                return;
            }
            
            // verificar se email ja existe em algun outro cliente
            if ($this->clienteDAO->emailExists($data['email'])) {
                http_response_code(409);
                echo json_encode(["message" => "Email já está em uso"]);
                return;
            }
            
            $cliente = new Cliente(
                $data['nome'],
                $data['email'],
                $data['telefone'],
                $data['endereco']
            );
            
            $id = $this->clienteDAO->create($cliente);
            
            if ($id) {
                http_response_code(201);
                echo json_encode([
                    "message" => "Cliente criado com sucesso",
                    "id" => $id
                ]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Erro ao criar cliente"]);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }
    
    // atualiza cliente
    public function update($id) {
        try {
            if (!is_numeric($id) || $id <= 0) {
                http_response_code(400);
                echo json_encode(["message" => "ID inválido"]);
                return;
            }
            // verificar se cliente existe
            $clienteExistente = $this->clienteDAO->readById($id);
            if (!$clienteExistente) {
                http_response_code(404);
                echo json_encode(["message" => "Cliente não encontrado"]);
                return;
            }
            
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(["message" => "JSON inválido"]);
                return;
            }
            
            if (!isset($data['nome']) || !isset($data['email']) || !isset($data['telefone']) || !isset($data['endereco'])) {
                http_response_code(400);
                echo json_encode(["message" => "Dados obrigatórios não fornecidos"]);
                return;
            }
            
            // verifica se email existe em outro cliente na edicao em outro cliente ja cadastrado
            if ($this->clienteDAO->emailExists($data['email'], $id)) {
                http_response_code(409);
                echo json_encode(["message" => "Email já está em uso por outro cliente"]);
                return;
            }
            
            $cliente = new Cliente(
                $data['nome'],
                $data['email'],
                $data['telefone'],
                $data['endereco']
            );
            $cliente->setId($id);
            
            if ($this->clienteDAO->update($cliente)) {
                http_response_code(200);
                echo json_encode(["message" => "Cliente atualizado com sucesso"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Erro ao atualizar cliente"]);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }
    
    // deleta cliente
    public function destroy($id) {
        try {
            if (!is_numeric($id) || $id <= 0) {
                http_response_code(400);
                echo json_encode(["message" => "ID inválido"]);
                return;
            }
            
            // verificar se o cliente realmente existe
            $cliente = $this->clienteDAO->readById($id);
            if (!$cliente) {
                http_response_code(404);
                echo json_encode(["message" => "Cliente não encontrado"]);
                return;
            }
            
            if ($this->clienteDAO->delete($id)) {
                http_response_code(200);
                echo json_encode(["message" => "Cliente deletado com sucesso"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Erro ao deletar cliente"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Erro ao deletar cliente: " . $e->getMessage()]);
        }
    }
}
?>