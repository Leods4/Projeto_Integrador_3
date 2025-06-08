<?php
// app/config/Database.php

class Database {
    private static $instance = null;
    private $connection;

    // Configurações do banco (substitua com suas credenciais)
    private $host = 'localhost';
    private $dbname = 'sistema_certificados';
    private $username = 'root';
    private $password = '';

    // Construtor privado (padrão Singleton)
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }

    // Método estático para acessar a conexão
    public static function conectar() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}