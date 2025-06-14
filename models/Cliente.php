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
        return !empty($this->nome) && 
               strlen($this->nome) >= 2 &&
               filter_var($this->email, FILTER_VALIDATE_EMAIL) &&
               !empty($this->telefone) &&
               !empty($this->endereco);
    }
    
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getTelefone() { return $this->telefone; }
    public function setTelefone($telefone) { $this->telefone = $telefone; }
    
    public function getEndereco() { return $this->endereco; }
    public function setEndereco($endereco) { $this->endereco = $endereco; }
    
    public function getDataCadastro() { return $this->dataCadastro; }
    
    // converter para array
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
}

?>