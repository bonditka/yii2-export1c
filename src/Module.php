<?php

namespace bonditka\export1c;

class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public static $messagesCategory = 'bonditka/export1c';

    public $user = 'user';

    /**
     * @return \yii\web\User
     */
    public function getUser()
    {
        return \Yii::$app->{$this->user};
    }
}
