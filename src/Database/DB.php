<?php declare(strict_types = 1);

namespace AdsJob\Database;
use PDO;
use AdsJob\Traits\Logger;

class DB{
    use Logger;
    
    private static $connection = null;

    public static function connect(){
        $config = include __DIR__ . '/../../Config.php';
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];
        $dsn = 'mysql:' . http_build_query(data: $config['database'], arg_separator: ';');
        self::$connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        self::createMigrationsTable();
    }

    private static function createMigrationsTable(){
        self::$connection->exec(
            'CREATE TABLE IF NOT EXISTS migrations(
                id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );'
        );
    }

    public static function exists(string $table, string $attribute, string $value) : bool{
        return (self::rawQuery("SELECT COUNT($attribute) AS attrCount FROM $table 
                                WHERE $attribute = :attribute",['attribute' => $value])
                                ->fetch()['attrCount'] > 0);
    }

    public static function rawQuery(string $query, array $params = []){
        $statement = self::$connection->prepare($query);
        $statement->execute($params);
        return $statement;
    }

    public static function migrate(){
        $appliedMigrations = self::getAppliedMigrations();
        $files = scandir(__DIR__ . '/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        $migrated = [];

        foreach($toApplyMigrations as $migration){
            if($migration === '.' || $migration === '..'){
                continue;
            }
            $migrationInstance = require_once(__DIR__ . "/migrations/$migration");
            self::log("Applying migration $migration");
            self::$connection->exec($migrationInstance->up());
            $migrated[] = $migration;
        }
        if(!empty($migrated)){
            self::saveMigrations($migrated);
        }else{
            self::log("Nothing to migrate");
        }
    }

    private static function getAppliedMigrations(){
        $statement = self::rawQuery('SELECT migration FROM migrations');
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    private static function saveMigrations(array $migrations){
        $str = implode(',',array_map(fn($m) => "('$m')", $migrations));
        $statement = self::$connection->prepare("INSERT INTO migrations(migration) VALUES $str");
        $statement->execute();
    }

}

