<?php
class Cliente extends Pessoa {
    private $id;
    private $telefone;
    private $endereco;
    private $dataCadastro;
    
    public function __construct($nome = '', $email = '', $telefone = '', $endereco = '') {
        parent::__construct($nome, $email);
        $this->telefone = $telefone;
        $this->endereco = $endereco;
        $this->dataCadastro = date('Y-m-d H:i:s');
    }
    
    public function validar() {
        $errors = [];
        
        // valida nome
        if (empty(trim($this->nome))) {
            $errors[] = "Nome é obrigatório";
        } elseif (strlen(trim($this->nome)) < 2) {
            $errors[] = "Nome deve ter pelo menos 2 caracteres";
        }
        
        // valida email
        if (empty(trim($this->email))) {
            $errors[] = "Email é obrigatório";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email deve ter um formato válido";
        }
        
        // valida telefone
        if (empty(trim($this->telefone))) {
            $errors[] = "Telefone é obrigatório";
        }
        
        // valida endereço
        if (empty(trim($this->endereco))) {
            $errors[] = "Endereço é obrigatório";
        }
        
        return empty($errors) ? true : $errors;
    }
    
    // get e Set
    public function getId() { 
        return $this->id; 
    }
    
    public function setId($id) { 
        $this->id = $id; 
    }
    
    public function getTelefone() { 
        return $this->telefone; 
    }
    
    public function setTelefone($telefone) { 
        $this->telefone = $telefone; 
    }
    
    public function getEndereco() { 
        return $this->endereco; 
    }
    
    public function setEndereco($endereco) { 
        $this->endereco = $endereco; 
    }
    
    public function getDataCadastro() { 
        return $this->dataCadastro; 
    }
    
    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }
    
    // converte para array
    public function toArray() {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'endereco' => $this->endereco,
            'data_cadastro' => $this->dataCadastro
        ];
    }
    
    // criar cliente a partir do array
    public static function fromArray($data) {
        $cliente = new self(
            $data['nome'] ?? '',
            $data['email'] ?? '',
            $data['telefone'] ?? '',
            $data['endereco'] ?? ''
        );
        
        if (isset($data['id'])) {
            $cliente->setId($data['id']);
        }
        
        if (isset($data['data_cadastro'])) {
            $cliente->setDataCadastro($data['data_cadastro']);
        }
        
        return $cliente;
    }
}
?>