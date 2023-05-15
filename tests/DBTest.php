<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdsJob\Database\DB;

/**
 * @uses DB
 * @covers All DB functions
 */
class DBTest extends TestCase{

    public function setUp() : void{
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }

    public function testExistsMethodReturnsTrueWhenRecordExists(): void{
        DB::connect();
        DB::rawQuery("CREATE TABLE test_table (id INT PRIMARY KEY, name VARCHAR(255))");
        DB::rawQuery("INSERT INTO test_table (id, name) VALUES (1, 'John Doe')");
        $this->assertTrue(DB::exists('test_table', 'id', '1'));
        DB::rawQuery("DROP TABLE IF EXISTS test_table");
    }

    public function testExistsMethodReturnsFalseWhenRecordDoesNotExist(): void{
        DB::connect();
        DB::rawQuery("CREATE TABLE test_table (id INT PRIMARY KEY, name VARCHAR(255))");
        $this->assertFalse(DB::exists('test_table', 'id', '1'));
        DB::rawQuery("DROP TABLE IF EXISTS test_table");
    }

    public function testRawQueryMethodExecutesQueryAndReturnsStatement(): void{
        DB::connect();
        $result = DB::rawQuery("SELECT 1");
        $this->assertInstanceOf(PDOStatement::class, $result);
        $this->assertSame(1, $result->fetchColumn());
    }

    public function testLastInsertIdMethodReturnsLastInsertedId(): void{
        DB::connect();
        DB::rawQuery("CREATE TABLE test_table (id INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(255))");
        DB::rawQuery("INSERT INTO test_table (name) VALUES ('John Doe')");
        $lastInsertId = DB::lastInsertId();
        $this->assertIsString($lastInsertId);
        $this->assertNotEmpty($lastInsertId);
        DB::rawQuery("DROP TABLE IF EXISTS test_table");
    }

    public function testCreateMigrationsTableMethodCreatesTable(): void{
        DB::connect();
        DB::createMigrationsTable();

        $statement = DB::rawQuery("SHOW TABLES LIKE 'migrations'");
        $this->assertSame(1, $statement->rowCount());
    }

    public function testGetAppliedMigrationsMethodReturnsArrayOfAppliedMigrations(): void{
        DB::connect();
        DB::rawQuery("CREATE TABLE IF NOT EXISTS migrations (id INT PRIMARY KEY AUTO_INCREMENT, migration VARCHAR(255))");
        DB::rawQuery("INSERT INTO migrations (migration) VALUES ('migration1'), ('migration2')");

        $appliedMigrations = DB::getAppliedMigrations();

        // Assert that the applied migrations array contains the expected values
        $expectedMigrations = ['migration1', 'migration2'];
        $this->assertEquals($expectedMigrations, $appliedMigrations);

        DB::rawQuery("DROP TABLE IF EXISTS migrations");
    }

    public function testSaveMigrationsMethodSavesMigrations(): void{
        DB::connect();
        DB::rawQuery("CREATE TABLE IF NOT EXISTS migrations (id INT PRIMARY KEY AUTO_INCREMENT, migration VARCHAR(255))");

        $migrations = ['migration1', 'migration2'];
        DB::rawQuery("INSERT INTO migrations(migration) VALUES ('migration1'), ('migration2')");

        $statement = DB::rawQuery("SELECT migration FROM migrations");
        $savedMigrations = $statement->fetchAll(PDO::FETCH_COLUMN);
    
        $this->assertEquals($migrations, $savedMigrations);
        DB::rawQuery("DROP TABLE IF EXISTS migrations");
    }
}
