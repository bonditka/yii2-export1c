<?php

use bonditka\export1c\tests;
use bonditka\export1c\services\GeneratorDocumentResponse;

class GeneratorDocumentResponseTest extends \Codeception\Test\Unit
{
    /**
     * @var \Codeception\Module\Yii2
     */
    protected $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'generator' => [
                'class' => tests\unit\_fixtures\GeneratorFixture::class
            ],
        ]);
    }

    public function testErrors()
    {
        $generator = $this->tester->grabFixture('generator');

        $response = new GeneratorDocumentResponse();
        $response->setErrors($generator['errors']['main']);
        $response->setErrors($generator['errors']['second'], 'second');

        $errors = $response->getErrors();
        $this->assertTrue($errors[0] === $generator['errors']['main']);

        $errors = $response->getErrors('second');
        $this->assertTrue($errors[0] === $generator['errors']['second']);
    }
}
