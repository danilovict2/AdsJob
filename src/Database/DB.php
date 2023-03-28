<?php declare(strict_types = 1);

namespace AdsJob\Database;
use PDO;


class DB{

    private $connection;

    public function __construct(array $config, string $username = 'root', string $password = ''){

        $dsn = 'mysql:' . http_build_query(data: $config['database'], arg_separator: ';');
        $this->connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    public function rawQuery(string $query, array $params = []){
        $statement = $this->connection->prepare($query);
        $statement->execute($params);
        return $statement;
    }
}

