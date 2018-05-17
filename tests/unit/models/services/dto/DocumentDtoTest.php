<?php

use bonditka\export1c\tests;
use \bonditka\export1c\services\dto;
use bonditka\export1c\services\dto\DocumentDto;

class DocumentDtoTest extends \Codeception\Test\Unit
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

    public function testSetStoreInfo()
    {
        $dto = $this->tester->grabFixture('dto');
        $obDto = new DocumentDto();

        $obDto->setCompanyInfo($dto['document']['companyInfo']);
        $this->assertTrue($obDto->companyInfo instanceof dto\CompanyInfoDto);
        $this->assertTrue($obDto->companyInfo->name === $dto['document']['companyInfo']['name']);
    }

    public function testSetCompanyInfo()
    {
        $dto = $this->tester->grabFixture('dto');
        $obDto = new DocumentDto();

        $obDto->setCompanyInfo($dto['document']['companyInfo']);
        $this->assertTrue($obDto->companyInfo instanceof dto\CompanyInfoDto);
        $this->assertTrue($obDto->companyInfo->name === $dto['document']['companyInfo']['name']);
    }

    public function testSetPartnerInfo()
    {
        $dto = $this->tester->grabFixture('dto');
        $obDto = new DocumentDto();

        $obDto->setPartnerInfo($dto['document']['partnerInfo']);
        $this->assertTrue($obDto->partnerInfo instanceof dto\PartnerInfoDto);
        $this->assertTrue($obDto->partnerInfo->name === $dto['document']['partnerInfo']['name']);
    }

    public function testSetCounterparty()
    {
        $dto = $this->tester->grabFixture('dto');
        $obDto = new DocumentDto();

        $obDto->setCounterparty($dto['document']['counterparty']);
        $this->assertTrue($obDto->counterparty instanceof dto\CounterpartyDto);
        $this->assertTrue($obDto->counterparty->name === $dto['document']['counterparty']['name']);
    }
}
