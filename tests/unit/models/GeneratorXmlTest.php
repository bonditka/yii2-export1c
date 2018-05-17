<?php

namespace bonditka\export1c\tests\unit\models;

use Codeception\Test\Unit;

use bonditka\export1c\tests as tests;
use bonditka\export1c\models\GeneratorXml;

class GeneratorXmlTest extends Unit
{
    /**
     * @var \Codeception\Module\Yii2
     */
    protected $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'xml' => [
                'class' => tests\_fixtures\GeneratorXmlFixture::class
            ],
            'param' => [
                'class' => tests\_fixtures\GeneratorParamFixture::class
            ],
        ]);
    }

    protected static function debug($message, $exit = false)
    {
        print_r("\n");
        //print_r($message);
        $output = new \Codeception\Lib\Console\Output([]);
        $output->debug($message);
        print_r("\n");

        if ($exit) {
            exit();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function parseXml($xmlString, $needWrap = true)
    {
        if ($needWrap) {
            $xmlString =
                <<<XML
<?xml version="1.0"?>
<doc>
    $xmlString
</doc>
XML;
        }

        $result = simplexml_load_string($xmlString);
        if ($result === false) {
            $errors = libxml_get_errors();
            $latestError = array_pop($errors);
            self::debug($latestError);
            return [];
        }

        return $result;
    }

    public static function array_diff_assoc_recursive($array1, $array2)
    {
        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($array2[$key])) {
                    $difference[$key] = $value;
                } elseif (!is_array($array2[$key])) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = self::array_diff_assoc_recursive($value, $array2[$key]);
                    if ($new_diff != FALSE) {
                        $difference[$key] = $new_diff;
                    }
                }
            } elseif (!isset($array2[$key]) || $array2[$key] != $value) {
                $difference[$key] = $value;
            }
        }
        return !isset($difference) ? 0 : $difference;
    }

    public function testInit()
    {
        $generatorXml = new GeneratorXml();
        $param = $this->tester->grabFixture('param');
        $this->assertNotFalse($generatorXml->setParam($param->data));

        $this->assertTrue(is_file($generatorXml->outputFileTmp));
    }

    public function testAddNode()
    {
        $generatorXml = new GeneratorXml();
        $param = $this->tester->grabFixture('param');
        $this->assertNotFalse($generatorXml->setParam($param->data));

        $xmlOriginal = $this->tester->grabFixture('xml');
        $generatorXml->addNodeFromArray($xmlOriginal['singleNode']);
        $xmlString = file_get_contents($generatorXml->outputFileTmp);
        $xml = self::parseXml($xmlString);
        $xmlFromFile = json_decode(json_encode($xml), true);
        $result = array_diff($xmlOriginal['singleNode'], $xmlFromFile);
        $this->assertTrue(empty($result));
    }

    public function testAddNodeFromArray()
    {
        $generatorXml = new GeneratorXml();
        $param = $this->tester->grabFixture('param');
        $this->assertNotFalse($generatorXml->setParam($param->data));

        $xmlOriginal = $this->tester->grabFixture('xml');
        $generatorXml->addNodeFromArray($xmlOriginal['arrayNode']);
        $xmlString = file_get_contents($generatorXml->outputFileTmp);
        $xml = self::parseXml($xmlString);
        //convert into associative array
        $xmlFromFile = json_decode(json_encode($xml), true);

        $result = self::array_diff_assoc_recursive($xmlOriginal['arrayNode'], $xmlFromFile);
        $this->assertTrue(empty($result));
    }
}
