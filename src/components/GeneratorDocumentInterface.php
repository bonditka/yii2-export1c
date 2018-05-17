<?php

namespace bonditka\export1c\components;

interface GeneratorDocumentInterface
{
    public function addDocument($dto);

    public function addDocumentHeader($dto);

    public function addDocumentTable($dto);

    public function addDocumentTableItem($dto);

}