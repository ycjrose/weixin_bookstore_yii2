<?php

namespace app\models\book;

use Yii;

/**
 * This is the model class for table "book_sale_change_log".
 *
 * @property string $id
 * @property integer $book_id
 * @property integer $quantity
 * @property string $price
 * @property integer $member_id
 * @property string $created_time
 */
class BookSaleChangeLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'book_sale_change_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['book_id', 'quantity', 'member_id'], 'integer'],
            [['price'], 'number'],
            [['created_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Book ID',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'member_id' => 'Member ID',
            'created_time' => 'Created Time',
        ];
    }
}
