<?php

use yii\helpers;

use bonditka\export1c\tests;
use bonditka\export1c\services\dto;
use bonditka\export1c\services\GeneratorDocumentService;
use \bonditka\export1c\models\GeneratorDocument;

class GeneratorDocumentServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var \Codeception\Module\Yii2
     */
    protected $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'dto' => [
                'class' => tests\unit\_fixtures\DtoFixture::class
            ],
            'generator' => [
                'class' => tests\unit\_fixtures\GeneratorFixture::class
            ],
            'param' => [
                'class' => tests\_fixtures\GeneratorParamFixture::class
            ],
        ]);
    }

    protected static function debug($message, $exit = false)
    {
        print_r("\n");
        $output = new \Codeception\Lib\Console\Output([]);
        $output->debug($message);
        print_r("\n");

        if ($exit) {
            exit();
        }
    }

    public function testRunUnsupportedDocument()
    {
        $dto = $this->tester->grabFixture('dto');
        $documentDto = new dto\DocumentDto($dto['document']);

        $generatorService = new GeneratorDocumentService($documentDto);
        $response = $generatorService->run('');

        $this->assertTrue($response->hasError());
        $this->assertTrue($response->getLastError() === 'Invalid dto model');
    }

    public function testRunUnsupportedMethod()
    {
        $dto = $this->tester->grabFixture('dto');
        $admissionDto = new dto\AdmissionDto(helpers\ArrayHelper::merge($dto['document'], $dto['admissionDocument']));

        $generatorService = new GeneratorDocumentService($admissionDto);
        $response = $generatorService->run('undefined');

        $this->assertTrue($response->hasError());
        $this->assertTrue($response->getLastError() === 'There is no method: undefined');
    }

    public function testRunAdmissionHeader()
    {
        $dto = $this->tester->grabFixture('dto');
        $param = $this->tester->grabFixture('param');

        $admissionDto = new dto\AdmissionDto(helpers\ArrayHelper::merge($dto['document'], $dto['admissionDocument']));

        $action = 'addDocumentHeader';

        $generatorService = new GeneratorDocumentService($admissionDto);
        $response = $generatorService->run($action, $param->data);

        if($response->hasError()){
            $this->debug($response->getErrors(), true);
        }

        $this->assertFalse($response->hasError());
    }

    public function testRunSellingHeader()
    {
        $dto = $this->tester->grabFixture('dto');
        $param = $this->tester->grabFixture('param');

        $sellingDto = new dto\SellingDto(helpers\ArrayHelper::merge($dto['document'], $dto['sellingDocument']));

        $action = 'addDocumentHeader';

        $generatorService = new GeneratorDocumentService($sellingDto);
        $response = $generatorService->run($action, $param->data);
        if($response->hasError()){
            $this->debug($response->getErrors(), true);
        }

        $this->assertFalse($response->hasError());
    }

    public function testAddDocumentTable()
    {
        $dto = $this->tester->grabFixture('dto');
        $param = $this->tester->grabFixture('param');

        $documentDto = new dto\DocumentDto($dto['document']);

        $generator = new GeneratorDocument();
        $generator->setParam($param->data);

        $this->assertNotFalse($generator->addDocumentTable($documentDto->tableItems));
    }

    public function testRunAdmission()
    {
        $dto = $this->tester->grabFixture('dto');
        $param = $this->tester->grabFixture('param');

        $admissionDto = new dto\AdmissionDto(helpers\ArrayHelper::merge($dto['document'], $dto['admissionDocument']));

        $action = 'addDocument';

        $generatorService = new GeneratorDocumentService($admissionDto);
        $response = $generatorService->run($action, $param->data);

        if($response->hasError()){
            $this->debug($response->getErrors(), true);
        }

        $this->assertFalse($response->hasError());
    }

    public function testRunSelling()
    {
        $dto = $this->tester->grabFixture('dto');
        $param = $this->tester->grabFixture('param');

        $sellingDto = new dto\SellingDto(helpers\ArrayHelper::merge($dto['document'], $dto['sellingDocument']));

        $action = 'addDocument';

        $generatorService = new GeneratorDocumentService($sellingDto);
        $response = $generatorService->run($action, $param->data);
        if($response->hasError()){
            $this->debug($response->getErrors(), true);
        }

        $this->assertFalse($response->hasError());
    }
}