<?php declare(strict_types = 1);

use AdsJob\Database\Migration;

return new class implements Migration{

    public function up() : string{
        $schema = "
            CREATE TABLE IF NOT EXISTS job(
                id INT NOT NULL AUTO_INCREMENT,
                user_id INT NOT NULL,
                name VARCHAR(30) NOT NULL,
                location VARCHAR(30) NOT NULL,
                description VARCHAR(255) DEFAULT '',
                
                CONSTRAINT PK_job PRIMARY KEY(id, user_id),
                CONSTRAINT FK_user FOREIGN KEY(user_id) REFERENCES user(id) ON DELETE CASCADE
            );
        ";
        return $schema;
    }

    public function down() : string{
        $SQL = 'DROP TABLE IF EXISTS job';
        return $SQL;
    }

};