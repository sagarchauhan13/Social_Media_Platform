<?php
class dbConfig extends PDO{
    private $host = "localhost";
    private $username = "root";
    private $dbname = "wall_app";
    private $password = "";
    public $conn;
    
    //get the database connection
    
    public function __construct(){
        try{
            parent::__construct("mysql:host=$this->host;dbname=$this->dbname",$this->username,$this->password);
            
        } catch (PDOException $exception){
             echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>