<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wx_share_history".
 *
 * @property string $id
 * @property integer $member_id
 * @property string $share_url
 * @property string $created_time
 */
class WxShareHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_share_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'integer'],
            [['created_time'], 'safe'],
            [['share_url'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'share_url' => 'Share Url',
            'created_time' => 'Created Time',
        ];
    }
}
