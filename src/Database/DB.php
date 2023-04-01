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
        $this->createMigrationsTable();
    }

    private function createMigrationsTable(){
        $this->connection->exec(
            'CREATE TABLE IF NOT EXISTS migrations(
                id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );'
        );
    }

    public function exists(string $table, string $attribute, string $value) : bool{
        return ($this->rawQuery("SELECT COUNT($attribute) AS attrCount FROM $table 
                                WHERE $attribute = :attribute",['attribute' => $value])
                                ->fetch()['attrCount'] > 0);
    }

    public function rawQuery(string $query, array $params = []){
        $statement = $this->connection->prepare($query);
        $statement->execute($params);
        return $statement;
    }

    public function migrations(){
        $appliedMigrations = $this->getAppliedMigrations();
        $files = scandir(__DIR__ . '/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        $migrated = [];

        foreach($toApplyMigrations as $migration){
            if($migration === '.' || $migration === '..'){
                continue;
            }
            $migrationInstance = require_once(__DIR__ . "/migrations/$migration");
            $this->log("Applying migration $migration");
            $this->connection->exec($migrationInstance->up());
            $migrated[] = $migration;
        }
        if(!empty($migrated)){
            $this->saveMigrations($migrated);
        }else{
            $this->log("Nothing to migrate");
        }
    }

    private function getAppliedMigrations(){
        $statement = $this->connection->prepare('SELECT migration FROM migrations');
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    private function saveMigrations(array $migrations){
        $str = implode(',',array_map(fn($m) => "('$m')", $migrations));
        $statement = $this->connection->prepare("INSERT INTO migrations(migration) VALUES $str");
        $statement->execute();
    }

    private function log(string $message){
        echo '[' . date('Y-m-d H:i:s') . ']' . $message . PHP_EOL;
    }

}

