<?php 
class ClienteDAO {
    private $conn;
    private $table_name = "clientes";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // cria cliente
    public function create(Cliente $cliente) {
        $validacao = $cliente->validar();
        if ($validacao !== true) {
            throw new Exception("Dados inválidos: " . implode(', ', $validacao));
        }
        
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nome=:nome, email=:email, telefone=:telefone, endereco=:endereco";
        
        $stmt = $this->conn->prepare($query);
        
        // sanitiza/reduz dados
        $nome = htmlspecialchars(strip_tags($cliente->getNome()));
        $email = htmlspecialchars(strip_tags($cliente->getEmail()));
        $telefone = htmlspecialchars(strip_tags($cliente->getTelefone()));
        $endereco = htmlspecialchars(strip_tags($cliente->getEndereco()));
        
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":telefone", $telefone);
        $stmt->bindParam(":endereco", $endereco);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }
    
    // verifica se email ja existe
    public function emailExists($email, $excludeId = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        
        if ($excludeId) {
            $query .= " AND id != :excludeId";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        
        if ($excludeId) {
            $stmt->bindParam(':excludeId', $excludeId, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // contar total de clientes
    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // ler todos clientes
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // ler cliente por id
    public function readById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ler cliente por id retornando objeto Cliente
    public function readOne($id) {
        $data = $this->readById($id);
        
        if ($data) {
            return Cliente::fromArray($data);
        }
        
        return null;
    }

    // atualizar cliente
    public function update(Cliente $cliente) {
        $validacao = $cliente->validar();
        if ($validacao !== true) {
            throw new Exception("Dados inválidos: " . implode(', ', $validacao));
        }
        
        $query = "UPDATE " . $this->table_name . " 
                  SET nome=:nome, email=:email, telefone=:telefone, endereco=:endereco 
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        // sanitiza/reduz dados
        $nome = htmlspecialchars(strip_tags($cliente->getNome()));
        $email = htmlspecialchars(strip_tags($cliente->getEmail()));
        $telefone = htmlspecialchars(strip_tags($cliente->getTelefone()));
        $endereco = htmlspecialchars(strip_tags($cliente->getEndereco()));
        $id = $cliente->getId();
        
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // deleta cliente
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}
