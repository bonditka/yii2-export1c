<?php

namespace bonditka\export1c\services\dto;


class TableItemsDto extends Dto
{
    public $tableItems;

    public function __construct($dtoItems)
    {
        parent::__construct();

        foreach ($dtoItems as $key => $item) {
            $this->tableItems[$key] = new TableItemDto($item);
        }
    }
}