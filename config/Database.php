<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'crud_clientes';
    private $username = 'root';
    private $password = '';
    private $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username, 
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch(PDOException $exception) {
            throw new Exception("Erro na conexão com o banco de dados: " . $exception->getMessage());
        }
        
        return $this->conn;
    }
    
    // Metodo para criar banco e tabela se ja n existir um
    public function createDatabaseAndTable() {
        try {
            // conecta sem especificar banco para criar o banco
            $conn = new PDO(
                "mysql:host=" . $this->host, 
                $this->username, 
                $this->password
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // cria banco se n existir
            $conn->exec("CREATE DATABASE IF NOT EXISTS `{$this->db_name}` CHARACTER SET utf8 COLLATE utf8_general_ci");
            
            // Usa banco
            $conn->exec("USE `{$this->db_name}`");
            
            // Cria tabela se n existir
            $sql = "CREATE TABLE IF NOT EXISTS `clientes` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `nome` varchar(255) NOT NULL,
                `email` varchar(255) NOT NULL,
                `telefone` varchar(20) NOT NULL,
                `endereco` text NOT NULL,
                `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            
            $conn->exec($sql);
            
            return true;
        } catch(PDOException $exception) {
            throw new Exception("Erro ao criar banco/tabela: " . $exception->getMessage());
        }
    }
}
?>