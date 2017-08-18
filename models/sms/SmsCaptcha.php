<?php

namespace app\models\sms;

use Yii;

/**
 * This is the model class for table "sms_captcha".
 *
 * @property string $id
 * @property string $mobile
 * @property string $captcha
 * @property string $ip
 * @property string $expires_at
 * @property integer $status
 * @property string $created_time
 */
class SmsCaptcha extends \yii\db\ActiveRecord
{
    public function checkSmsCaptcha($mobile,$captcha){
        $info = self::find()->where(['mobile' => $mobile,'captcha' => $captcha])->one();

        if($info && strtotime($info['expires_at']) >= time()){
            $info->expires_at = date('Y-m-d H:i:s',time()-1);
            $info->status = 1;
            $info->update(0);
            return true;
        }
        return false;
    }
    //生成手机验证码：1.写入数据库。2.接入短信接口发短信
    public function buildSmsCaptcha($mobile,$ip = '',$sign = '',$channel = ''){
        $all_data = [
            'mobile' => $mobile,
            'ip' => $ip,
            'captcha' => rand(1000,9999),
            'expires_at' => date('Y-m-d H:i:s',time() + 60 * 5),
            'created_time' => date('Y-m-d H:i:s'),
            'status' => 0,
        ];
        $this->setAttributes($all_data);
        return $this->save(0);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_captcha';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expires_at', 'created_time'], 'safe'],
            [['status'], 'required'],
            [['status'], 'integer'],
            [['mobile', 'ip'], 'string', 'max' => 20],
            [['captcha'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => 'Mobile',
            'captcha' => 'Captcha',
            'ip' => 'Ip',
            'expires_at' => 'Expires At',
            'status' => 'Status',
            'created_time' => 'Created Time',
        ];
    }
}
