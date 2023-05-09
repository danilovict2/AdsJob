<?php declare(strict_types = 1);

use AdsJob\Database\Migration;

return new class implements Migration{

    public function up() : string{
        $schema = "
            CREATE TABLE IF NOT EXISTS message(
                id INT NOT NULL AUTO_INCREMENT,
                user_id INT NOT NULL,
                chat_room_id INT NOT NULL,
                message TEXT NOT NULL,
                seen TINYINT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                CONSTRAINT PK_message PRIMARY KEY(id),
                CONSTRAINT FK_user_message FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
                CONSTRAINT FK_chat_room_message FOREIGN KEY (chat_room_id) REFERENCES chat_room(id) ON DELETE CASCADE
            );
        ";
        return $schema;
    }

    public function down() : string{
        $SQL = 'DROP TABLE IF EXISTS message';
        return $SQL;
    }

};