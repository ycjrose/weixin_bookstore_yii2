<?php

namespace app\models\member;

use Yii;

/**
 * This is the model class for table "oauth_access_token".
 *
 * @property string $id
 * @property string $access_token
 * @property string $expired_time
 * @property string $created_time
 */
class OauthAccessToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oauth_access_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expired_time', 'created_time'], 'safe'],
            [['access_token'], 'string', 'max' => 600],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'access_token' => 'Access Token',
            'expired_time' => 'Expired Time',
            'created_time' => 'Created Time',
        ];
    }
}
