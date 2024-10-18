<?php
class Database {
    private $servername = "localhost";
    private $username = "edino47pc_adminDino";
    private $password = "e/5Fj8>s>b@";
    private $dbname = "edino47pc_e_dino";
    private $conn;

    public function connect() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }

        return $this->conn;
    }

    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
