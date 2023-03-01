<?php declare (strict_types = 1);

namespace AdsJob\Database;
use PDO;

class DB{

    private PDO $pdo;

    public function __construct(){
        $host = getenv('DB_HOST');
        $dbname = getenv('DB_DATABASE');
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbname",getenv('DB_USERNAME'),getenv('DB_PASSWORD'));
    }

    public function select(){
        
    }

    public function insert(){

    }
    
    public function update(){

    }

    public function delete(){

    }

}

return new DB();