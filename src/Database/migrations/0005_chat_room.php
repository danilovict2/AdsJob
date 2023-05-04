<?php declare(strict_types = 1);

use AdsJob\Database\Migration;

return new class implements Migration{

    public function up() : string{
        $schema = "
            CREATE TABLE IF NOT EXISTS chat_room(
                id INT NOT NULL AUTO_INCREMENT,
                name VARCHAR(50) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                CONSTRAINT PK_chat_room PRIMARY KEY(id)
            );
        ";
        return $schema;
    }

    public function down() : string{
        $SQL = 'DROP TABLE IF EXISTS chat_room';
        return $SQL;
    }

};