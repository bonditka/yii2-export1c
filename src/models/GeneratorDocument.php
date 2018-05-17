<?php
namespace bonditka\export1c\models;

class GeneratorDocument extends GeneratorXml
{
    protected $typeOperation;
    protected $params;

    public function addDocumentTable($dto)
    {
        if (empty($dto)) {
            return $this;
        }

        $this->addStartNode('Товары');

        foreach ($dto->tableItems as $product) {
            $this->addDocumentTableItem($product);
        }
        $this->addEndNode('Товары');

        return $this;
    }

    public function addDocumentTableItem($dtoItem)
    {
        $arItem = [
            'Строка' => [
                'ДанныеНоменклатуры' => [
                    'Номенклатура' => [
                        'Наименование' => $dtoItem->name,
                        'НаименованиеПолное' => $dtoItem->name,
                        'КодВПрограмме' => $dtoItem->code
                    ]
                ],
                'ЕдиницаИзмерения' => [
                    'Код' => $dtoItem->unitCode
                ],
                'Количество' => $dtoItem->quantity,
                'Сумма' => $dtoItem->amount,
                'Цена' => $dtoItem->price,
                'СтавкаНДС' => $dtoItem->vat,
                'СуммаНДС' => $dtoItem->vatAmount,
                'ТипЗапасов' => 'СобственныеТовары'
            ]
        ];
        $this->addNodeFromArray($arItem);
        return $this;
    }

    protected function addDocumentMainProperty($dto) //КлючевыеСвойства
    {
        $arNode = [
        'КлючевыеСвойства' => [
            'Дата' => $dto->date,
            'Номер' => $dto->number,
            'Организация' => [
                'Наименование' => $dto->companyInfo->name,
                'НаименованиеСокращенное' => $dto->companyInfo->nameShort,
                'НаименованиеПолное' => $dto->companyInfo->nameFull,
                'ИНН' => $dto->companyInfo->inn,
                'КПП' => $dto->companyInfo->kpp,
                'ЮридическоеФизическоеЛицо' => $dto->companyInfo->formOrganization
            ]
        ]
        ];

        $this->addNodeFromArray($arNode);
        return $this;
    }

    protected function addDocumentResponsible($dto)
    {
        $this->addStartNode('Ответственный');
            $this->addNode('Наименование', $dto->responsible);
        $this->addEndNode('Ответственный');
        return $this;
    }

    protected function addDocumentCurrency($dto)
    {
        $this->addStartNode('Валюта');
            $this->addNode('Код', $dto->currencyCode);
            $this->addNode('Наименование', $dto->currencyName);
        $this->addEndNode('Валюта');
        return $this;
    }

    protected function addDocumentCurrencyPayment($dto)
    {
        $this->addStartNode('ВалютаВзаиморасчетов');
            $this->addNode('Код', $dto->currencyCode);
            $this->addNode('Наименование', $dto->currencyName);
        $this->addEndNode('ВалютаВзаиморасчетов');
        return $this;
    }

    protected function addDocumentTypeOperation($dto)
    {
        $this->addNode('ВидОперации', isset($dto->typeOperation)?$dto->typeOperation:$this->typeOperation);
        return $this;
    }

    protected function addDocumentAmountInfo($dto)
    {
        $this->addNode('Сумма', $dto->amount);
        $this->addNode('СуммаВключаетНДС', $dto->isVatInclude);
        $this->addNode('Налогообложение', $dto->taxation);
        return $this;
    }

    protected function addDocumentCounterparty($dto)
    {
        $this->addStartNode('Контрагент');
            $this->addNode('Наименование', $dto->counterparty->nameShort);
            $this->addNode('НаименованиеПолное', $dto->counterparty->name);
            $this->addNode('ИНН', $dto->counterparty->inn);
            $this->addNode('КПП', $dto->counterparty->kpp);
            $this->addNode('ЮридическоеФизическоеЛицо', $dto->counterparty->formOrganization);
        $this->addEndNode('Контрагент');
        return $this;
    }

    protected function addDocumentMutualSettlementData($dto)
    {
        $this->addStartNode('ДанныеВзаиморасчетов');
            $this->addStartNode('Договор');
                $this->addNode('ВидДоговора', 'СПоставщиком');
                    $this->addStartNode('Организация');
                        $this->addNode('Наименование', $dto->companyInfo->name);
                        $this->addNode('НаименованиеСокращенное', $dto->companyInfo->nameShort);
                        $this->addNode('НаименованиеПолное', $dto->companyInfo->nameFull);
                        $this->addNode('ИНН', $dto->companyInfo->inn);
                        $this->addNode('КПП', $dto->companyInfo->kpp);
                        $this->addNode('ЮридическоеФизическоеЛицо', $dto->companyInfo->formOrganization);
                    $this->addEndNode('Организация');
                    $this->addDocumentCounterparty($dto);
                    $this->addDocumentCurrencyPayment($dto);
                    $this->addNode('РасчетыВУсловныхЕдиницах', 'false');
                    $this->addNode('Наименование', $dto->name);
                    $this->addNode('Дата', $dto->date);
                    $this->addNode('Номер', $dto->number);
            $this->addEndNode('Договор');
            $this->addDocumentCurrencyPayment($dto);
            $this->addNode('КурсВзаиморасчетов', '1');
            $this->addNode('КратностьВзаиморасчетов', '1');
            $this->addNode('РасчетыВУсловныхЕдиницах', 'false');
        $this->addEndNode('ДанныеВзаиморасчетов');
        return $this;
    }
}