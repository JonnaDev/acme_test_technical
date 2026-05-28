<?php 
class Database {

    public string $servername;
    public string $username;
    public string $password;
    public string $dbname;
    public ?mysqli $conn = null;

    public function __construct(string $servername, string $username, string $password, string $dbname)
    {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    public function connection(){
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if($this->conn->connect_error){
            die("Conexión fallida" . $this->conn->connect_error);
        } 
    }
}

$db_instance = new Database('127.0.0.1', 'root', '', 'acme');
$db_instance->connection();