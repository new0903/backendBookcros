<?php

namespace app\models;

use yii\db\ActiveRecord;

class Towns extends ActiveRecord
{
    public static function tableName(){
        return 'towns';
    }
}
