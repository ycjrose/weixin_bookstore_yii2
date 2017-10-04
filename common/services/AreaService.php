<?php
namespace app\common\services;
use app\common\services\BaseService;
use app\models\City;
/**
* 地区级联服务
*/
class AreaService extends BaseService{
	//获取省份信息
	public static function getProvinces(){
		
		$provices = City::find(['id','provice'])->where(['city_id' => 0])->orderBy(['id' => SORT_ASC])->indexBy('id')->asArray()->all();
		return $provices;
	}
	//根据省份id获取城区的信息
	public static function getCityTree($province_id){
		//直辖市的id
		$zhixiashi_city_id = [110000,120000,310000,500000];
		$citys = City::find()->where(['province_id' => $province_id])->orderBy(['id' => SORT_ASC])->asArray()->all();

		//构建前台所需数组
		$city_tree = [
			'city' => [],
			'district' => [],
		];
		foreach ($citys as $_item) {
			if( in_array($province_id, $zhixiashi_city_id) ){
				//遍历到省份的记录
				if( $_item['city_id'] == 0 ){
					$city_tree['city'][] = [
						'id' => $_item['id'],
						'name' => $_item['name'],
					];
				}else{
					$city_tree['district'][ $province_id ][] = [
						'id' => $_item['id'],
						'name' => $_item['name'],
					];
				}
			}else{//不是直辖市
				if( $_item['city_id'] == 0 ){
					continue;
				}
				if( $_item['area_id'] == 0 ){
					$city_tree['city'][] = [
						'id' => $_item['id'],
						'name' => $_item['name'],
					];
				}else{

					if( !isset( $city_tree['district'][ $_item['city_id'] ] ) ){
					    $city_tree['district'][ $_item['city_id'] ] = [];
					}
					//按城市号进行分类
					$city_tree['district'][ $_item['city_id'] ][] = [
					    'id' => $_item['id'],
					    'name' => $_item['name'],
					];
				}
			}
		}
		return $city_tree;
	} 
}