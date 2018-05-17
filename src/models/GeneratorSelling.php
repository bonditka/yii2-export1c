<?php
namespace bonditka\export1c\models;

use bonditka\export1c\components;

class GeneratorSelling extends GeneratorDocument implements components\GeneratorDocumentInterface
{
    protected $typeOperation = 'РеализацияКлиенту';


    protected function addDocumentCompanyInfo($dto)
    {
        $this->addDocumentBoss($dto)
                ->addDocumentAccountant($dto)
                ->addDocumentCompanyBank($dto);

        return $this;
    }

    protected function addDocumentBoss($dto)
    {
        $this->addStartNode('Руководитель');
            $this->addNode('ФИО', $dto->companyHead->fio);
            $this->addNode('ДатаРождения', $dto->companyHead->birthDay);
            $this->addNode('ИНН', $dto->companyHead->inn);
        $this->addEndNode('Руководитель');

        return $this;
    }

    protected function addDocumentAccountant($dto)
    {
        $this->addStartNode('ГлавныйБухгалтер');
            $this->addNode('ФИО', $dto->companyAccountant->fio);
            $this->addNode('ДатаРождения', $dto->companyAccountant->birthDay);
            $this->addNode('ИНН', $dto->companyAccountant->inn);
        $this->addEndNode('ГлавныйБухгалтер');

        return $this;
    }

    protected function addDocumentCompanyBank($dto)
    {
        $arNode = [
            'БанковскийСчетОрганизации' => [
                'НомерСчета' => $dto->accountNumber,
                'Банк' => [
                    'БИК' => $dto->companyBank->bik,
                    'КоррСчет' => $dto->companyBank->korrAccount,
                    'Наименование' => $dto->companyBank->name,
                ],
                'Владелец' => [
                    'ОрганизацииСсылка' => [
                        'Наименование' => $dto->companyInfo->name,
                        'НаименованиеСокращенное' => $dto->companyInfo->nameShort,
                        'НаименованиеПолное' => $dto->companyInfo->nameFull,
                        'ИНН' => $dto->companyInfo->inn,
                        'КПП' => $dto->companyInfo->kpp,
                        'ЮридическоеФизическоеЛицо' => $dto->companyInfo->formOrganization
                    ]
                ]
            ]
        ];

        $this->addNodeFromArray($arNode);
        return $this;
    }

    protected function addDocumentVacationProduced($dto)
    {
        $this->addStartNode('ОтпускПроизвел');
            $this->addNode('ФИО', $dto->releaseProduced->fio);
            $this->addNode('ДатаРождения', $dto->releaseProduced->birthDay);
            $this->addNode('ИНН', $dto->releaseProduced->inn);
        $this->addEndNode('ОтпускПроизвел');

        return $this;
    }

    protected function addDocumentDelivery($dto)
    {
        $this->addNode('АдресДоставки', $dto->deliveryAddress);
        $this->addNode('ВидЭД', $dto->egType);

        return $this;
    }

    public function addDocument($dto)
    {
        $this->addStartNode('Документ.РеализацияТоваровУслуг');
        $this->addDocumentHeader($dto)
            ->addDocumentTable($dto->tableItems);

        $this->addEndNode('Документ.РеализацияТоваровУслуг');
    }

    public function addDocumentHeader($dto)
    {
        $this->addDocumentMainProperty($dto) //ключевые свойства
                ->addDocumentResponsible($dto) //ответственный
                ->addDocumentCurrency($dto) //валюта
                ->addDocumentTypeOperation($dto) //тип операции
                ->addDocumentAmountInfo($dto) //информация по сумме документа и НДС
                ->addDocumentCounterparty($dto) //контрагенты
                ->addDocumentCompanyInfo($dto) //ген.дир/главбух
                ->addDocumentVacationProduced($dto) //отпуск произвел
                ->addDocumentDelivery($dto); //адрес доставки, ВидЭД

        $this->addNode('Налогообложение', 'ПродажаОблагаетсяНДС');
        return $this;
    }
}