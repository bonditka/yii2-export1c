<?php

use bonditka\export1c\tests;

use bonditka\export1c\services\dto\Dto;

class DtoTest extends \Codeception\Test\Unit
{
    /**
     * @var \Codeception\Module\Yii2
     */
    protected $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'dto' => [
                'class' => tests\unit\_fixtures\DtoFixture::class
            ],
        ]);
    }

    public function testCreateFromRequest()
    {
        $dto = $this->tester->grabFixture('dto');
        $obDto = Dto::createFromRequest($dto['request']);
        $this->assertTrue($obDto instanceof Dto && $obDto->additionalProperty === $dto['request']['additionalProperty']);
    }

    public function test__construct()
    {
        $dto = $this->tester->grabFixture('dto');

        $obDto = new Dto($dto['__construct']);
        $this->assertTrue($obDto instanceof Dto && $obDto->additionalProperty === $dto['__construct']['additionalProperty']);
    }
}
