<?php declare(strict_types = 1);


return new class{

    public function up() : string{
        $schema = '
            CREATE TABLE IF NOT EXISTS test(
                id INT NOT NULL AUTO_INCREMENT
            );
        ';
        return $schema;
    }

    public function down() : string{
        $SQL = 'DROP TABLE test';
        return $SQL;
    }

};