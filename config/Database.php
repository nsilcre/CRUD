<?php
// Clase de ayuda para obtener una conexión PDO a MySQL.

class Database
{
    private string $host     = 'localhost';
    private string $db_name  = 'loginphp';
    private string $username = 'root';
    private string $password = '';

    private ?PDO $conn = null;

    public function getConnection(): ?PDO
    {
        if ($this->conn instanceof PDO) {
            return $this->conn;
        }

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            // En un entorno real, sería preferible loguear el error
            // y mostrar un mensaje genérico al usuario final.
            die('Error de conexión a la base de datos');
        }

        return $this->conn;
    }
}
