<?php
namespace bonditka\export1c\models;

use bonditka\export1c\components;
use bonditka\export1c\services\dto\AdmissionDto;

class GeneratorAdmission extends GeneratorDocument implements components\GeneratorDocumentInterface
{
    protected $typeOperation = 'ПокупкаУПоставщика';

    protected function addDocumentStore($dto)
    {
        $this->addStartNode('Склад');
            $this->addNode('Наименование', $dto->store->name);
            $this->addNode('ТипСклада', $dto->store->typeStore);
        $this->addEndNode('Склад');

        return $this;
    }

    protected function addDocumentNumber(AdmissionDto $dto)
    {
        $this->addNode('ДатаВходящегоДокумента', $dto->incomingNumber);
        $this->addNode('НомерВходящегоДокумента', $dto->incomingDate);
        return $this;
    }

    public function addDocument($dto)
    {
        $this->addStartNode('Документ.ПоступлениеТоваровУслуг');
            $this
                ->addDocumentHeader($dto)
                ->addDocumentTable($dto->tableItems);
        $this->addEndNode('Документ.ПоступлениеТоваровУслуг');
    }

    public function addDocumentHeader($dto)
    {
        try{
            $this->addDocumentMainProperty($dto)//ключевые свойства
                    ->addDocumentResponsible($dto)//ответственный
                    ->addDocumentCurrency($dto)//валюта
                    ->addDocumentTypeOperation($dto)//тип операции
                    ->addDocumentAmountInfo($dto)//информация по сумме документа и НДС
                    ->addDocumentStore($dto)//склады
                    ->addDocumentCounterparty($dto)//контрагенты
                    ->addDocumentNumber($dto); //номер и дата документа
            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }
}