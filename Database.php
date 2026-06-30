<?php
public $mysqli connection;
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "radhi@11";
    private $db = "employee";

    public function connect() {
        $connection = new mysqli($this->host, $this->user, $this->pass, $this->db);

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        return $connection;
    }
}