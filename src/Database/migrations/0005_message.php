<?php declare(strict_types = 1);


return new class{

    public function up() : string{
        $schema = "
            CREATE TABLE IF NOT EXISTS message(
                id INT NOT NULL AUTO_INCREMENT,
                from_user_id INT NOT NULL,
                to_user_id INT NOT NULL,
                message VARCHAR(255) NOT NULL,

                CONSTRAINT PK_message PRIMARY KEY(id),
                CONSTRAINT FK_from_user_id FOREIGN KEY(from_user_id) REFERENCES user(id),
                CONSTRAINT FK_to_user_id FOREIGN KEY(to_user_id) REFERENCES user(id)
            );
        ";
        return $schema;
    }

    public function down() : string{
        $SQL = 'DROP TABLE IF EXISTS message';
        return $SQL;
    }

};