<?php declare(strict_types = 1);

use AdsJob\Database\Migration;

return new class implements Migration{

    public function up() : string{
        $schema = "
            CREATE TABLE IF NOT EXISTS message(
                id INT NOT NULL AUTO_INCREMENT,
                chat_room_id INT NOT NULL,
                user_id INT NOT NULL,
                content TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                CONSTRAINT PK_message PRIMARY KEY (id),
                CONSTRAINT FK_chat_room FOREIGN KEY (chat_room_id) REFERENCES chat_room(id),
                CONSTRAINT FK_user_message FOREIGN KEY (user_id) REFERENCES user(id)
            );
        ";
        return $schema;
    }

    public function down() : string{
        $SQL = 'DROP TABLE IF EXISTS job_image';
        return $SQL;
    }

};