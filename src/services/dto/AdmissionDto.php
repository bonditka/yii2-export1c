<?php

namespace bonditka\export1c\services\dto;


class AdmissionDto extends DocumentDto
{
    public $store;
    public $incomingNumber;
    public $incomingDate;

    public function setStore(array $data)
    {
        $this->store = new StoreDto($data);
    }
}