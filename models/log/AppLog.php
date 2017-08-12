<?php

namespace app\models\log;

use Yii;

/**
 * This is the model class for table "app_log".
 *
 * @property string $id
 * @property string $app_name
 * @property string $err_name
 * @property integer $http_code
 * @property integer $err_code
 * @property string $ip
 * @property string $ua
 * @property string $content
 * @property string $created_time
 */
class AppLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['http_code', 'err_code'], 'integer'],
            [['ip', 'ua', 'content'], 'required'],
            [['content'], 'string'],
            [['created_time'], 'safe'],
            [['app_name'], 'string', 'max' => 30],
            [['err_name'], 'string', 'max' => 50],
            [['ip'], 'string', 'max' => 20],
            [['ua'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_name' => 'App Name',
            'err_name' => 'Err Name',
            'http_code' => 'Http Code',
            'err_code' => 'Err Code',
            'ip' => 'Ip',
            'ua' => 'Ua',
            'content' => 'Content',
            'created_time' => 'Created Time',
        ];
    }
}
