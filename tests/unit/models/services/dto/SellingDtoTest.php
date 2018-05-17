<?php

use bonditka\export1c\tests;
use bonditka\export1c\services\dto;
use bonditka\export1c\services\dto\SellingDto;

class SellingDtoTest extends \Codeception\Test\Unit
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

    public function testSetCompanyAccountant()
    {
        $dto = $this->tester->grabFixture('dto');
        $obDto = new SellingDto();

        $obDto->setCompanyAccountant($dto['sellingDocument']['companyAccountant']);
        $this->assertTrue($obDto->companyAccountant instanceof dto\CompanyAccountantDto);
        $this->assertTrue($obDto->companyAccountant->fio === $dto['sellingDocument']['companyAccountant']['fio']);
    }

    public function testSetCompanyBank()
    {
        $dto = $this->tester->grabFixture('dto');
        $obDto = new SellingDto();

        $obDto->setCompanyBank($dto['sellingDocument']['companyBank']);
        $this->assertTrue($obDto->companyBank instanceof dto\CompanyBankDto);
        $this->assertTrue($obDto->companyBank->name === $dto['sellingDocument']['companyBank']['name']);
    }

    public function testSetCompanyHead()
    {
        $dto = $this->tester->grabFixture('dto');
        $obDto = new SellingDto();

        $obDto->setCompanyHead($dto['sellingDocument']['companyHead']);
        $this->assertTrue($obDto->companyHead instanceof dto\CompanyHeadDto);
        $this->assertTrue($obDto->companyHead->fio === $dto['sellingDocument']['companyHead']['fio']);
    }
}
