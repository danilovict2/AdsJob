<?php declare(strict_types = 1);

use AdsJob\Database\Migration;

return new class implements Migration{

    public function up() : string{
        $schema = '
            CREATE TABLE IF NOT EXISTS user(
                id INT NOT NULL AUTO_INCREMENT,
                firstName VARCHAR(255) NOT NULL,
                lastName VARCHAR(255) NOT NULL,
                profilePicture VARCHAR(255),
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                verification_code VARCHAR(10) NOT NULL,
                email_verified_at DATETIME DEFAULT NULL,
                
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