<?php

namespace bonditka\export1c\components;

interface GeneratorXmlInterface
{
    public function addHeader();

    public function addFooter();

    /**
     * @param string $node
     * @param string $value
     */
    public function addNode($node, $value);

    public function addNodeFromArray(array $data);
}