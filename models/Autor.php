<?php

namespace app\models;

use yii\db\ActiveRecord;

class Autor extends ActiveRecord
{
    public static function tableName(){
        return 'autor';
    }
}
