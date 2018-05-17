<?php

namespace bonditka\export1c\services\dto;


class DocumentDto extends Dto
{
    public $name;
    public $date;
    public $number;
    public $responsible;
    public $currencyCode;
    public $currencyName;
    public $typeOperation;
    public $amount;
    public $isVatInclude;
    public $taxation;
    public $companyInfo;
    public $counterparty;
    public $partnerInfo;
    public $contractName;
    public $contractNumber;
    public $contractDate;

    public $tableItems;


    public function setCompanyInfo(array $data)
    {
        $this->companyInfo = new CompanyInfoDto($data);
    }

    public function setPartnerInfo(array $data)
    {
        $this->partnerInfo = new PartnerInfoDto($data);
    }

    public function setCounterparty(array $data)
    {
        $this->counterparty = new CounterpartyDto($data);
    }

    public function setTableItems(array $data)
    {
        $this->tableItems = new TableItemsDto($data);
    }
}