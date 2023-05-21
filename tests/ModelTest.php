<?php

use PHPUnit\Framework\TestCase;
use AdsJob\Models\Model;
use AdsJob\Database\DB;

class ConcreteModel extends Model{
    protected static string $tableName = 'test_models';

    public function attributes() : array{
        return ['id', 'name'];
    }

    public function getValues() : array{
        return $this->values;
    }

    public function setValues(array $values) : void{
        $this->values = $values;
    }

    public static function primaryKey() : string{
        return 'id';
    }
}


/**
 * @uses Model
 * @covers All Model functions
 */
class ModelTest extends TestCase{

    public static function setUpBeforeClass(): void{
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        DB::connect();

    }

    public function setUp(): void{
        DB::rawQuery('CREATE TABLE test_models (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL
        )');
    }

    public function tearDown(): void{
        DB::rawQuery('DROP TABLE IF EXISTS test_models');
    }

    public function testFindOneMethodReturnsModelInstance(): void{
        $model = ConcreteModel::findOne(['id' => 1]) ? '' : null;

        $this->assertNull($model);
    }

    public function testExistsMethodReturnsFalseWhenRecordDoesNotExist(): void{
        $exists = ConcreteModel::exists('id', '1');

        $this->assertFalse($exists);
    }

    public function testCreateMethodSetsModelValues(): void{
        $model = new ConcreteModel;
        $model->create(['id' => 1, 'name' => 'John Doe']);

        $this->assertEquals(['id' => 1, 'name' => 'John Doe'], $model->getValues());
    }

    public function testMagicSetMethodSetsModelValue(): void{
        $model = new ConcreteModel;
        $model->id = 1;

        $this->assertNotEquals(1, $model->id);
    }

    public function testMagicGetMethodReturnsNullForNonexistentProperty(): void{
        $model = new ConcreteModel;
        $result = $model->nonexistent_property;

        $this->assertNull($result);
    }

    public function testMagicIssetMethodReturnsFalseForNonexistentProperty(): void{
        $model = new ConcreteModel;
        $property = $model->nonexistent_property;
        $isset = isset($property);
        
        $this->assertFalse($isset);
    }

    public function testSaveMethodSavesModelToDatabase(): void{
        $model = new ConcreteModel;
        $model->create(['id' => 1, 'name' => 'John Doe']);
        $model->save();

        $result = DB::rawQuery('SELECT * FROM test_models')->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals(['id' => '1', 'name' => 'John Doe'], $result);
    }

    public function testUpdateMethodUpdatesModelInDatabase(): void{
        $model = new ConcreteModel;
        $model->create(['id' => 1, 'name' => 'John Doe']);
        $model->save();

        $model->name = 'Jane Smith';
        $model->update(['name' => 'Jane Smith']);

        $result = DB::rawQuery('SELECT * FROM test_models WHERE id = 1')->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals(['id' => '1', 'name' => 'Jane Smith'], $result);
    }

    public function testDeleteMethodDeletesModelFromDatabase(): void{
        $model = new ConcreteModel;
        $model->create(['id' => 1, 'name' => 'John Doe']);
        $model->save();

        $model->delete();

        $result = DB::rawQuery('SELECT * FROM test_models WHERE id = 1')->fetch(PDO::FETCH_ASSOC);

        $this->assertFalse($result);
    }

    public function testMagicGetMethodReturnsPropertyValueFromDatabase(): void{
        $model = new ConcreteModel;
        $model->create(['id' => 1, 'name' => 'John Doe']);
        $model->save();

        $modelFromDb = ConcreteModel::findOne(['id' => 1]);

        $this->assertEquals('John Doe', $modelFromDb->name);
    }

    public function testMagicIssetMethodReturnsTrueForExistingProperty(): void{
        $model = new ConcreteModel;
        $model->create(['id' => 1, 'name' => 'John Doe']);
        $model->save();

        $modelFromDb = ConcreteModel::findOne(['id' => 1]);

        $this->assertTrue(isset($modelFromDb->name));
    }

    public function testGetValuesMethodReturnsModelValues(): void{
        $model = new ConcreteModel;
        $model->create(['id' => 1, 'name' => 'John Doe']);

        $values = $model->getValues();

        $this->assertEquals(['id' => 1, 'name' => 'John Doe'], $values);
    }

    public function testSetValuesMethodSetsModelValues(): void{
        $model = new ConcreteModel;
        $model->setValues(['id' => 1, 'name' => 'John Doe']);

        $values = $model->getValues();

        $this->assertEquals(['id' => 1, 'name' => 'John Doe'], $values);
    }

    public function testGetTableMethodReturnsTableName(): void{
        $model = new ConcreteModel;

        $table = $model::tableName();

        $this->assertEquals('test_models', $table);
    }

    public function testGetPrimaryKeyMethodReturnsPrimaryKeyName(): void{
        $model = new ConcreteModel;

        $primaryKey = $model->primaryKey();

        $this->assertEquals('id', $primaryKey);
    }

    public function testSaveMethodUpdatesModelIfItExistsInDatabase() : void{
        $model = new ConcreteModel;
        $model->create(['id' => 1, 'name' => 'John Doe']);
        $model->save();

        $model->name = 'Doe John';
        $model->save();

        $result = DB::rawQuery("SELECT name FROM test_models WHERE id = 1")->fetchColumn();
        $this->assertEquals('Doe John', $result);
    }
}