<?php

abstract class Pessoa {
    protected $nome;
    protected $email;
    
    public function __construct($nome = '', $email = '') {
        $this->nome = $nome;
        $this->email = $email;
    }
    
    abstract public function validar();
    
    public function getNome() {
        return $this->nome;
    }
    
    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
}

?>