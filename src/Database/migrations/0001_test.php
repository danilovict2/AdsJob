<?php declare(strict_types = 1);


return new class{

    public function up() : string{
        $schema = '
            CREATE TABLE IF NOT EXISTS test(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY
            );
        ';
        return $schema;
    }

    public function down() : string{
        $SQL = 'DROP TABLE IF EXISTS test';
        return $SQL;
    }

};