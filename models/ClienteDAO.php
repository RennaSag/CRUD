<?php

class ClienteDAO {
    private $conn;
    private $table_name = "clientes";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    

    //ler pelo id
    public function readById($id) {
    $query = "SELECT * FROM clientes WHERE id = :id LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // funcao para criar
    public function create(Cliente $cliente) {
        if (!$cliente->validar()) {
            return false;
        }
        
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nome=:nome, email=:email, telefone=:telefone, endereco=:endereco";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nome", $cliente->getNome());
        $stmt->bindParam(":email", $cliente->getEmail());
        $stmt->bindParam(":telefone", $cliente->getTelefone());
        $stmt->bindParam(":endereco", $cliente->getEndereco());
        
        return $stmt->execute();
    }
    
    // funcao para ler todos
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // funcao ler so 1
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $cliente = new Cliente($row['nome'], $row['email'], 
                                 $row['telefone'], $row['endereco']);
            $cliente->setId($row['id']);
            return $cliente;
        }
        
        return null;
    }
    
    // funcao update
    public function update(Cliente $cliente) {
        if (!$cliente->validar()) {
            return false;
        }
        
        $query = "UPDATE " . $this->table_name . " 
                  SET nome=:nome, email=:email, telefone=:telefone, endereco=:endereco 
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nome', $cliente->getNome());
        $stmt->bindParam(':email', $cliente->getEmail());
        $stmt->bindParam(':telefone', $cliente->getTelefone());
        $stmt->bindParam(':endereco', $cliente->getEndereco());
        $stmt->bindParam(':id', $cliente->getId());
        
        return $stmt->execute();
    }
    
    // funcao de deletar
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        
        return $stmt->execute();
    }
}
?>