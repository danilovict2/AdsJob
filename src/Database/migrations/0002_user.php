<?php declare(strict_types = 1);


return new class{

    public function up() : string{
        $schema = '
            CREATE TABLE IF NOT EXISTS user(
                id INT NOT NULL AUTO_INCREMENT,
                firstName VARCHAR(255) NOT NULL,
                lastName VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                
                CONSTRAINT PK_user PRIMARY KEY(id)
            );
        ';
        return $schema;
    }

    public function down() : string{
        $SQL = 'DROP TABLE IF EXISTS user';
        return $SQL;
    }

};