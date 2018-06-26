<?php

namespace app\models;

use yii\db\ActiveRecord;

class Page extends ActiveRecord
{
    public static function tableName()
    {
        return 'page';
    }
}