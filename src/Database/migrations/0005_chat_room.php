<?php declare(strict_types = 1);

use AdsJob\Database\Migration;

return new class implements Migration{

    public function up() : string{
        $schema = "
            CREATE TABLE IF NOT EXISTS chat_room(
                id INT NOT NULL AUTO_INCREMENT,
                user_1_id INT NOT NULL,
                user_2_id INT NOT NULL,
                job_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                CONSTRAINT PK_chat_room PRIMARY KEY(id),
                CONSTRAINT FK_user_1_id FOREIGN KEY (user_1_id) REFERENCES user(id) ON DELETE CASCADE,
                CONSTRAINT FK_user_2_id FOREIGN KEY (user_2_id) REFERENCES user(id) ON DELETE CASCADE,
                CONSTRAINT FK_job_id FOREIGN KEY (job_id) REFERENCES job(id) ON DELETE CASCADE
            );
        ";
        return $schema;
    }

    public function down() : string{
        $SQL = 'DROP TABLE IF EXISTS chat_room';
        return $SQL;
    }

};