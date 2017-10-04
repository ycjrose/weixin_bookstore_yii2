<?php

namespace app\modules\weixin\controllers;
use app\modules\weixin\common\BaseController;
use app\common\services\UrlService;
use app\models\book\Book;
use yii\log\FileTarget;
use app\common\components\HttpClient;
use app\common\services\weixin\TemplateService;
use app\commands\QueueListController;
use app\models\market\MarketQrcode;
use app\models\market\QrcodeScanHistory;

class MsgController extends BaseController{

      public function actionTest(){
           //$vipSay = '南京天气';
           // $res = TemplateService::bindNotice(19);
           // if(!$res){
           //      var_dump(TemplateService::getErrMsg()) ;
           // }
         // $queue_list = new QueueListController();   
         // $queue_list->actionBind_list();
      }


    public function actionIndex(){  

        if($this->checkSignature() && $_GET['echostr']){
            //用于微信第一次认证
        	return $_GET['echostr'];
        }else{

            return $this->SendMsg();
        }
           
       
    }
    //接受推送并回复
    public function SendMsg(){

        $postStr = file_get_contents('php://input');
        
        //$this->record_log('[xml:]'.$postStr);
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

        //如果是订阅事件
        if(strtolower($postObj->MsgType) == 'event'){
            //关注事件
            if(strtolower($postObj->Event) == 'subscribe'){
                //如果通过扫码关注
                if(isset($postObj->EventKey)){
                    $event_key = $postObj->EventKey;
                    $qrcode_key = str_replace('qrscene_', '', $event_key);
                    $qrcode_info = MarketQrcode::findOne(['id' => $qrcode_key]);
                    if($qrcode_info){
                        //操作数据库
                        $qrcode_info->total_scan_count += 1;
                        $qrcode_info->updated_time = date('Y-m-d H:i:s');
                        $qrcode_info->update(0);

                        $model_scan_history = new QrcodeScanHistory();
                        $model_scan_history->openid = $postObj->FromUserName;
                        $model_scan_history->qrcode_id = $qrcode_info['id'];
                        $model_scan_history->created_time = date('Y-m-d H:i:s');
                        $model_scan_history->save(0);
                    }
                }
                //回复消息
                $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
                $toUser = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time = time();
                $msgType = 'text';
                $content = "欢迎关注ycj的公众号！\n回复#后面跟图书名或标签名可查询图书";
                $send_info = sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
                
                return $send_info;
            }
        }
        //如果是用户发来的消息
        if(strtolower($postObj->MsgType) == 'text'){
            $vipSay = trim($postObj->Content);

            if(mb_strpos($vipSay,'#') === 0){
                //查询图书信息然后发图文
                $vipSay = mb_substr($vipSay, 1);
                $res = $this->search($vipSay);
                if(!$res){
                    $content = '该图书不存在';
                    return $this->sendTextMsg($postObj,$content);
                }
                $imageMsgs = [];
                foreach ($res as $value) {
                    $imageMsgs[] = [
                        'title' => $value['name'],
                        'description' => mb_substr( strip_tags( $value['summary'] ),0,20,"utf-8" ),
                        'picUrl' => \Yii::$app->params['domain'].UrlService::buildPicUrl( "book",$value['main_image'] ),
                        'url' => \Yii::$app->params['domain'].UrlService::buildMUrl( "/product/info",[ 'id' => $value['id'] ] ),
                    ];
                }

               return $this->sendImageMsg($postObj,$imageMsgs);

            }
            else{

                    // switch ($vipSay) {
                    //     case '客服电话':
                    //         $content = '客服ycj:13255287051';
                    //         break;
                    //     case '博客':
                    //         $content = '<a href="http://www.ycjblog.top:8086/">点击博客地址</a>';
                    //         break;
                    //     default:
                    //         $content = '请输入正确的内容';
                    //         break;
                    // }
                //接入图灵机器人
                $tuling_url = 'http://www.tuling123.com/openapi/api';
                $key = \Yii::$app->params['tuling_key'];
                $array_data = [
                    'key' => $key,
                    'info' => $vipSay,
                    'userid' => $postObj->FromUserName,
                ];
                $json_data = json_encode($array_data,JSON_UNESCAPED_UNICODE);
                HttpClient::setHeader(['Content-Type: application/json; charset=UTF-8']);
                $res = HttpClient::post($tuling_url,$json_data);
                $res = @json_decode($res,true);
                if(!$res){

                    $content = '机器人小姐故障啦~~';

                }elseif(isset($res['url'])){//有url的回答

                        $content = $res['text']."\n".$res['url'];
                    
                }elseif(isset($res['list'])){//多条复杂信息则发送图文消息（例如想看今天新闻）

                    $imageMsgs = [];
                    foreach ($res['list'] as $_item) {
                        if(isset($_item['article'])){
                            $title = $_item['article'];
                        }
                        if(isset($_item['name'])){
                            $title = $_item['name'];
                        }
                        $imageMsgs[] = [
                            'title' => isset($title) ? $title : '默认标题',
                            'description' => '内容请点进链接细看',
                            'picUrl' => $_item['icon'],
                            'url' => $_item['detailurl'],
                        ];
                    }
                    return $this->sendImageMsg($postObj,$imageMsgs);

                }else{//只有文本消息的回答
                    $content = $res['text'];
                }  
                return $this->sendTextMsg($postObj,$content);

            }

            
        }
        

       
    }
    //从数据库查询图书信息
    private function search( $kw ){
        $query = Book::find()->where([ 'status' => 1 ]);
        $where_name = [ 'LIKE','name','%'. $kw .'%', false ];
        $where_tag = [ 'LIKE','tags','%'. $kw .'%', false ];
        $query->andWhere([ 'OR',$where_name,$where_tag ]);
        $res = $query->orderBy([ 'id' => SORT_DESC ])->limit( 3 )->all();
        return $res;
    }
    //第一次微信认证
    public function checkSignature(){
    	$signature = trim($this->get('signature'));
    	$timestamp = trim($this->get('timestamp'));
    	$nonce = trim($this->get('nonce'));
    	$tmpArr = array(\Yii::$app->params['weixin']['token'],$timestamp,$nonce);
    	sort($tmpArr);
    	$tmpStr = implode($tmpArr);
    	$tmpStr = sha1($tmpStr);

    	if($tmpStr == $signature){
    		return true;
    	}else{
    		return false;
    	}
    }
    //记录错误日志，方便调试
    public function record_log($msg){
        $log = new FileTarget();
        $log->logFile = \Yii::$app->getRuntimePath() . "/logs/weixin_msg_".date("Ymd").".log";
        $request_uri = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'';
        $log->messages[] = [
            "[url:{$request_uri}][post:".http_build_query($_POST)."] [msg:{$msg}]",
            1,
            'application',
            microtime(true)
        ];
        $log->export();
    }
}
