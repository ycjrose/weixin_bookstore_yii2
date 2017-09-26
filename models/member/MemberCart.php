<?php

namespace app\models\member;

use Yii;

/**
 * This is the model class for table "member_cart".
 *
 * @property string $id
 * @property string $member_id
 * @property integer $book_id
 * @property integer $quantity
 * @property string $updated_time
 * @property string $created_time
 */
class MemberCart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member_cart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'book_id', 'quantity'], 'integer'],
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
            'member_id' => 'Member ID',
            'book_id' => 'Book ID',
            'quantity' => 'Quantity',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
