<?php

class Database {
    private static ?PDO $conexao = null;

    public static function conectar(): PDO {
        if (self::$conexao === null) {
            try {
                // Recomendado: usar variáveis de ambiente para credenciais
                $host = getenv('DB_HOST') ?: 'localhost';
                $dbname = getenv('DB_NAME') ?: 'sistema_certificados';
                $usuario = getenv('DB_USER') ?: 'root';
                $senha = getenv('DB_PASS') ?: '';

                $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

                self::$conexao = new PDO($dsn, $usuario, $senha, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT => true // opcional: mantém conexão persistente
                ]);
            } catch (PDOException $e) {
                error_log("Erro ao conectar ao banco: " . $e->getMessage());
                throw new RuntimeException("Erro interno ao conectar ao banco de dados.");
            }
        }

        return self::$conexao;
    }

    public static function desconectar(): void {
        self::$conexao = null;
    }
}
