<?php

namespace app\models\book;

use Yii;

/**
 * This is the model class for table "book_cat".
 *
 * @property string $id
 * @property string $name
 * @property integer $weight
 * @property integer $status
 * @property string $updated_time
 * @property string $created_time
 */
class BookCat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'book_cat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['weight', 'status'], 'integer'],
            [['updated_time', 'created_time'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'weight' => 'Weight',
            'status' => 'Status',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
