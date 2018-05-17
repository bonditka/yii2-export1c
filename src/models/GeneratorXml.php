<?php

namespace bonditka\export1c\models;

use bonditka\export1c\components;
use yii\base;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class GeneratorXml extends Model implements components\GeneratorXmlInterface
{
    public $outputFile;
    public $outputFileTmp;

    protected $fileExists = null;

    /**
     * @param $filePath
     * @return bool
     */
    protected function createFile($filePath)
    {
        if ($this->fileExists) {
            $this->fileExists = true;
            return $filePath;
        }

        if(is_file($filePath)){
            file_put_contents($filePath, '');
            $this->fileExists = true;
            return $filePath;
        }

        if(!is_dir(dirname($filePath))){
            mkdir(dirname($filePath), 0777, true);
        }

        if (touch($filePath) === false) {
            return false;
        }

        $this->fileExists = true;
        return $filePath;
    }

    public function makeFile(array $param = [])
    {
        if(!empty($this->outputFileTmp) && !$this->createFile($this->outputFileTmp)){
            return false;
        }
        elseif(!empty($this->outputFileTmp)){
            return $this->outputFileTmp;
        }

        $filePathFromParam = ArrayHelper::getValue($param, 'filePath');
        if (empty($filePathFromParam) || !$this->createFile($filePathFromParam)) {
            return false;
        }

        $this->outputFileTmp = $filePathFromParam;
        return $this->outputFileTmp;
    }

    public function setParam(array $param = []){
        return $this->makeFile($param);
    }

    public function addToFile($fileContent)
    {
        if (!$this->fileExists && !is_file($this->outputFileTmp)) {
            $this->fileExists = false;
            throw new base\Exception('File doe\'s not exists');
        } else {
            $this->fileExists = true;
        }

        return file_put_contents($this->outputFileTmp, $fileContent, FILE_APPEND);
    }

    public function addHeader()
    {
        $xmlString = file_get_contents(__DIR__ . '/../views/helper/header.xml');
        $this->addToFile($xmlString);
        return $this;
    }

    public function addFooter()
    {
        $xmlString = file_get_contents(__DIR__ . '/../views/helper/footer.xml');
        $this->addToFile($xmlString);
        return $this;
    }

    public function addStartNode($node)
    {
        $this->addToFile('<' . $node . '>');

        return $this;
    }

    public function addEndNode($node)
    {
        $this->addToFile('</' . $node . '>');

        return $this;
    }

    /**
     * @param string $node
     * @param string $value
     * @return GeneratorXml
     * @throws base\Exception
     */
    public function addNode($node, $value)
    {
        if (is_array($value)) {
            throw new base\InvalidArgumentException('$value must be a string');
        }

        $fileContent = '<' . $node . '>' . $value . '</' . $node . '>';
        $this->addToFile($fileContent);

        return $this;
    }

    protected function generateFileContentFromArray(array $data)
    {
        $fileContent = '';
        foreach ($data as $node => $item) {
            $fileContent .= '<' . $node . '>';
            if (is_array($item)) {
                $fileContent .= $this->generateFileContentFromArray($item);
            } else {
                $fileContent .= $item;
            }
            $fileContent .= '</' . $node . '>';
        }
        return $fileContent;
    }

    public function addNodeFromArray(array $data)
    {
        try {
            if (empty($data)) {
                throw new base\InvalidArgumentException('array is empty');
            }

            $fileContent = $this->generateFileContentFromArray($data);

            if (empty($fileContent)) {
                throw new base\Exception('Something goes wrong');
            }

            $this->addToFile($fileContent);
        } catch (Exception $e) {
            throw $e;
        }
    }
}