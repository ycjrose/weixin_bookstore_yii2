<?php

namespace app\models\brand;

use Yii;

/**
 * This is the model class for table "brand_setting".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $address
 * @property string $mobile
 * @property string $logo
 * @property string $updated_time
 * @property string $created_time
 */
class BrandSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['updated_time', 'created_time'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 2000],
            [['address', 'logo'], 'string', 'max' => 200],
            [['mobile'], 'string', 'max' => 11],
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
            'description' => 'Description',
            'address' => 'Address',
            'mobile' => 'Mobile',
            'logo' => 'Logo',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
