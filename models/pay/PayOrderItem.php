<?php

namespace app\models\pay;

use Yii;

/**
 * This is the model class for table "pay_order_item".
 *
 * @property string $id
 * @property integer $pay_order_id
 * @property string $member_id
 * @property integer $quantity
 * @property string $price
 * @property string $discount
 * @property integer $target_type
 * @property integer $target_id
 * @property string $note
 * @property integer $status
 * @property integer $comment_status
 * @property string $updated_time
 * @property string $created_time
 */
class PayOrderItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay_order_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pay_order_id', 'member_id', 'quantity', 'target_type', 'target_id', 'status', 'comment_status'], 'integer'],
            [['price', 'discount'], 'number'],
            [['note'], 'required'],
            [['note'], 'string'],
            [['updated_time', 'created_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pay_order_id' => 'Pay Order ID',
            'member_id' => 'Member ID',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'discount' => 'Discount',
            'target_type' => 'Target Type',
            'target_id' => 'Target ID',
            'note' => 'Note',
            'status' => 'Status',
            'comment_status' => 'Comment Status',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
