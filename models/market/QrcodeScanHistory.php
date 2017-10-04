<?php

namespace app\models\market;

use Yii;

/**
 * This is the model class for table "qrcode_scan_history".
 *
 * @property string $id
 * @property string $openid
 * @property integer $qrcode_id
 * @property string $created_time
 */
class QrcodeScanHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qrcode_scan_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qrcode_id'], 'integer'],
            [['created_time'], 'safe'],
            [['openid'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'Openid',
            'qrcode_id' => 'Qrcode ID',
            'created_time' => 'Created Time',
        ];
    }
}
