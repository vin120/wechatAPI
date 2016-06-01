<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "token".
 *
 * @property integer $id
 * @property string $token
 * @property string $curr_time
 */
class Token extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['curr_time'], 'safe'],
            [['access_token'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'access_token' => Yii::t('app', 'Access Token'),
            'curr_time' => Yii::t('app', 'Curr Time'),
        ];
    }
}
