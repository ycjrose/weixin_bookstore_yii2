<?php

namespace app\models\pay;

use Yii;

/**
 * This is the model class for table "pay_order".
 *
 * @property string $id
 * @property string $order_sn
 * @property string $member_id
 * @property integer $target_type
 * @property integer $pay_type
 * @property integer $pay_source
 * @property string $total_price
 * @property string $discount
 * @property string $pay_price
 * @property string $pay_in_money
 * @property double $ratio
 * @property string $pay_sn
 * @property string $note
 * @property integer $status
 * @property integer $express_status
 * @property integer $express_address_id
 * @property string $express_info
 * @property integer $comment_status
 * @property string $pay_time
 * @property string $updated_time
 * @property string $created_time
 */
class PayOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'target_type', 'pay_type', 'pay_source', 'status', 'express_status', 'express_address_id', 'comment_status'], 'integer'],
            [['total_price', 'discount', 'pay_price', 'pay_in_money', 'ratio'], 'number'],
            [['note'], 'required'],
            [['note'], 'string'],
            [['pay_time', 'updated_time', 'created_time'], 'safe'],
            [['order_sn'], 'string', 'max' => 40],
            [['pay_sn'], 'string', 'max' => 128],
            [['express_info'], 'string', 'max' => 100],
            [['order_sn'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_sn' => 'Order Sn',
            'member_id' => 'Member ID',
            'target_type' => 'Target Type',
            'pay_type' => 'Pay Type',
            'pay_source' => 'Pay Source',
            'total_price' => 'Total Price',
            'discount' => 'Discount',
            'pay_price' => 'Pay Price',
            'pay_in_money' => 'Pay In Money',
            'ratio' => 'Ratio',
            'pay_sn' => 'Pay Sn',
            'note' => 'Note',
            'status' => 'Status',
            'express_status' => 'Express Status',
            'express_address_id' => 'Express Address ID',
            'express_info' => 'Express Info',
            'comment_status' => 'Comment Status',
            'pay_time' => 'Pay Time',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
