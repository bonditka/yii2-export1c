<?php

use bonditka\export1c\tests;
use bonditka\export1c\services\dto;
use bonditka\export1c\services\dto\AdmissionDto;

class AdmissionDtoTest extends \Codeception\Test\Unit
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

    public function testSetStore()
    {
        $dto = $this->tester->grabFixture('dto');
        $obDto = new AdmissionDto();

        $obDto->setStore($dto['admissionDocument']['store']);
        $this->assertTrue($obDto->store instanceof dto\StoreDto);
        $this->assertTrue($obDto->store->typeStore === $dto['admissionDocument']['store']['typeStore']);
    }
}
