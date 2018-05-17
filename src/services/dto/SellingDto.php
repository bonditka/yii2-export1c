<?php

namespace bonditka\export1c\services\dto;


class SellingDto extends DocumentDto
{
    public $companyHead;
    public $companyAccountant;
    public $accountNumber;
    public $companyBank;
    public $releaseProduced;
    public $deliveryAddress;
    public $egType;

    public function setCompanyHead(array $data)
    {
        $this->companyHead = new CompanyHeadDto($data);
    }

    public function setCompanyAccountant(array $data)
    {
        $this->companyAccountant = new CompanyAccountantDto($data);
    }

    public function setReleaseProduced(array $data)
    {
        $this->releaseProduced = new ReleaseProducedDto($data);
    }

    public function setCompanyBank(array $data)
    {
        $this->companyBank = new CompanyBankDto($data);
    }
}