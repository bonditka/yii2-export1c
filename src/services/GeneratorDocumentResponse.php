<?php

namespace bonditka\export1c\services;

use yii\helpers;

class GeneratorDocumentResponse
{
    protected $errors;
    protected $params;

    public function __construct($params = null)
    {
        $this->params = $params;
    }

    public function setErrors($errors, $level = 'main')
    {
        if(empty($level)){
            $level = 'main';
        }
        $this->errors[$level][] = $errors;
    }

    /**
     * @param string $level
     * @return mixed|array|null
     */
    public function getErrors($level = 'main')
    {
        if(empty($level)){
            $level = 'main';
        }
        return $this->errors[$level];
    }

    public function createResponseFromArray(array $array = [])
    {
        return $this->params = $array;
    }

    public function addResponseFromArray(array $array = [])
    {
        return $this->params = helpers\ArrayHelper::merge($this->params, $array);
    }


    /**
     * @return string json
     */
    public function getResponse()
    {
        return helpers\Json::encode($this->params);
    }

    public function hasError(){
        return (count($this->errors) > 0);
    }

    public function getLastError($level = 'main'){
        if($this->hasError()){
            $errors = $this->getErrors($level);
            return array_pop($errors);
        }
        return '';
    }


}