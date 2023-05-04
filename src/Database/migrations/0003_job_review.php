<?php declare(strict_types = 1);

use AdsJob\Database\Migration;

return new class implements Migration{

    public function up() : string{
        $schema = "
            CREATE TABLE IF NOT EXISTS job_review(
                id INT NOT NULL AUTO_INCREMENT,
                user_id INT NOT NULL,
                job_id INT NOT NULL,
                review_text VARCHAR(255) DEFAULT '',
                review_value FLOAT NOT NULL,
        
                CONSTRAINT PK_job_review PRIMARY KEY(id, job_id),
                CONSTRAINT FK_user_job_review FOREIGN KEY(user_id) REFERENCES user(id) ON DELETE CASCADE,
                CONSTRAINT FK_job FOREIGN KEY(job_id) REFERENCES job(id) ON DELETE CASCADE
            );
        ";
        return $schema;
    }

    public function down() : string{
        $SQL = 'DROP TABLE IF EXISTS job_review';
        return $SQL;
    }

};