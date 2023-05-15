<?php

use PHPUnit\Framework\TestCase;
use AdsJob\Sessions\Session;

/**
 * @uses Session
 * @covers All Session functions
 */
class SessionTest extends TestCase{

    public function testSetAndGetFlashMessage(): void{
        $session = new Session();

        $session->setFlash('success', 'Success message');
        $session->setFlash('error', 'Error message');

        $this->assertEquals('Success message', $session->getFlash('success'));
        $this->assertEquals('Error message', $session->getFlash('error'));
        $this->assertFalse($session->getFlash('info'));
    }

    public function testSetAndGetSessionValue(): void{
        $session = new Session();

        $session->set('name', 'John Doe');
        $session->set('age', 25);

        $this->assertEquals('John Doe', $session->get('name'));
        $this->assertEquals(25, $session->get('age'));
        $this->assertFalse($session->get('gender'));
    }

    public function testRemoveSessionValue(): void{
        $session = new Session();

        $session->set('name', 'John Doe');
        $session->remove('name');

        $this->assertFalse($session->get('name'));
    }

    public function testCSRFTokenGeneration(): void{
        $session = new Session();

        ob_start();
        $session->csrf_token();
        $tokenInput = ob_get_clean();

        $this->assertStringContainsString('<input type=\'text\' value=\'', $tokenInput);
        $this->assertStringContainsString('\' style=\'display: none\' name=\'csrf_token\'>', $tokenInput);
    }

    public function testValidateTokenWithValidToken(): void{
        $session = new Session();

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;

        $isValid = $session->validateToken($token);

        $this->assertTrue($isValid);
        $this->assertFalse(isset($_SESSION['csrf_token']));
    }

    public function testValidateTokenWithInvalidToken(): void{
        $session = new Session();

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;

        $isValid = $session->validateToken('invalid_token');

        $this->assertFalse($isValid);
        $this->assertTrue(isset($_SESSION['csrf_token']));
    }
}
