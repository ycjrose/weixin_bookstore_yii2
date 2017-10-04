<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "queue_list".
 *
 * @property string $id
 * @property string $queue_name
 * @property string $data
 * @property integer $status
 * @property string $created_time
 */
class QueueList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'queue_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['queue_name'], 'string', 'max' => 30],
            [['data'], 'string', 'max' => 500],
            [['created_time'], 'string', 'max' => 24],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'queue_name' => 'Queue Name',
            'data' => 'Data',
            'status' => 'Status',
            'created_time' => 'Created Time',
        ];
    }
}
