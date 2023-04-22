<?php declare(strict_types = 1);


return new class{

    public function up() : string{
        $schema = "
            CREATE TABLE IF NOT EXISTS job_image(
                id INT NOT NULL AUTO_INCREMENT,
                job_id INT NOT NULL,
                imagePath VARCHAR(100),

                CONSTRAINT PK_job_review PRIMARY KEY (id, job_id),
                CONSTRAINT FK_job_image FOREIGN KEY(job_id) REFERENCES job(id) ON DELETE CASCADE
            );
        ";
        return $schema;
    }

    public function down() : string{
        $SQL = 'DROP TABLE IF EXISTS job_image';
        return $SQL;
    }

};