<?php

namespace bonditka\export1c\services;

use bonditka\export1c\models\GeneratorAdmission;
use bonditka\export1c\models\GeneratorSelling;
use bonditka\export1c\services\dto;


class GeneratorDocumentService
{
    protected $dto;
    protected $response;

    public function __construct(dto\DocumentDto $dto, GeneratorDocumentResponse $response = null)
    {
        $this->dto = $dto;

        if (is_null($response)) {
            $this->response = new GeneratorDocumentResponse();
        } else {
            $this->response = $response;
        }
    }

    public function run($action, $param = [])
    {
        try {
            if ($this->dto instanceof dto\AdmissionDto) {
                $generator = new GeneratorAdmission();
            } elseif ($this->dto instanceof dto\SellingDto) {
                $generator = new GeneratorSelling();
            } else {
                throw new \InvalidArgumentException('Invalid dto model');
            }
            if (!method_exists($generator, $action)) {
                throw new \InvalidArgumentException('There is no method: ' . $action);
            }

            $generator->setParam($param);
            $generator->{$action}($this->dto);
            $this->response->addResponseFromArray(['result' => 'ok']);

            return $this->response;

        } catch (\Exception $e) {
            $this->response->createResponseFromArray(['result' => 'error']);
            $this->response->setErrors($e->getMessage());
            return $this->response;
        }

    }

    public function getActionFromParams($params)
    {
        //first call. create file and start action
        if (empty($params['action'])) {
            $action = 'createFile';
        } elseif (!in_array($params['action'], ['addDocument', 'addDocumentHeader', 'addDocumentTableData'])) {
            throw new \InvalidArgumentException('There is no valid action variable');
        } else {
            $action = $params['action'];
        }

        return $action;
    }
}