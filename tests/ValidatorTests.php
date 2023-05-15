<?php

use PHPUnit\Framework\TestCase;
use AdsJob\Validators\Validator;

class ValidatorTest extends TestCase{

    public function testValidateFormWithValidData(): void{
        $validator = new Validator([
            'name' => ['required', 'min' => 3],
            'email' => ['required', 'email'],
        ]);

        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ];

        $isValid = $validator->validateForm($data);

        $this->assertTrue($isValid);
        $this->assertEmpty($validator->getErrors());
    }

    public function testValidateFormWithInvalidData(): void{
        $validator = new Validator([
            'name' => ['required', 'min' => 3],
            'email' => ['required', 'email'],
        ]);

        $data = [
            'name' => '',
            'email' => 'invalidemail',
        ];

        $isValid = $validator->validateForm($data);

        $this->assertFalse($isValid);
        $errors = $validator->getErrors();
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('email', $errors);
        $this->assertCount(2, $errors['name']);
        $this->assertCount(1, $errors['email']);
    }
}
