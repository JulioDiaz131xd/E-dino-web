<?php
// assets/models/db.php
class Database {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "e_dino";
    private $conn;

    // Establecer la conexión a la base de datos
    public function connect() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }

        return $this->conn;
    }

    // Cerrar la conexión
    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
