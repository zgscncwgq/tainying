<?php

/**
 * 验证码处理
 */
namespace Api\Controller;

use Think\Controller;

class TbproductController extends Controller{

	protected $appkey="23491756";
	protected $secret="fcc0d6a8ae5bf736a24a4d5f3f73868c";

    public function receiveProduct(){
		$data['status']=0;
		$data['msg']="非法数据";
        if(IS_POST){
           $data['status']=1;
		   $data['data']=implode(',',$_POST);
		   $dataPost=explode(',', $data['data']) ;
		   	   foreach ($dataPost as $ky => $vl) {
			   	   	try{
			   	   		$parema['proid']=$vl;
			   	   		$parema['crtime']=time();			   	   		
			   	   		$sul=M('taobaoproduct')->add($parema);

			   	   	}catch(Exception $e){
						
					}
		   	   }
        }

		$this->ajaxreturn($data);
    }

    public function userpro(){
    	$userid=I("userid");
    	$result['status']=0;
    	$result['msg']="数据异常";
    	if(!$userid){
    		$this->ajaxreturn($result);
    		exit();	
    	}
    	$where['up.userid']=$userid;
        $data=M('taobaoproduct')->alias("td")->join('__USERPRO__ as up on up.proid=td.proid','left')->where($where)->limit(40)->select();
        if($data){
        	$sul=M("users")->where("id=".I("userid"))->find();
        	session('user',$sul);
        	$result['msg']="加载成功";
        	$result['status']=1;
        	$result['data']=$data;
        }
    	$this->ajaxreturn($result);
    }

    public function proyun(){
    	$data['status']=0;
		$data['msg']="非法数据";
		//if(IS_POST){
			$type=$_POST['type'];
			$type=1;
			if($type==1){
				$sul=M("taobaoproduct")->limit(100)->select();
			}else{
				$sul=M("usertapro")->where("userid=%d",intval($type))->limit(100)->select();
			}
			$data['data']=$sul;
		//}

		$this->ajaxreturn($data);
    }
    public function pingzi(){
    	$data=M('taobaoproduct')->limit(80)->select();
    	$this->ajaxreturn($data);
    }

    public function getTbproduct(){
    	include SITE_PATH."simplewind/Core/Library/Taobao/TopSdk.php";
        $sul=M('taobaoproduct')->where("num_iid=''")->limit(30)->getfield('proid',true);
       
        $numIids=implode(',', $sul);
        
    	$c = new \TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;

		$req = new \TbkItemInfoGetRequest;
		$req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,nick,seller_id,volume,tk_rate");
		
		$req->setPlatform("1");
		$req->setNumIids($numIids);
		$resp = $c->execute($req);
		$suldata=json_encode($resp);
		$tdata=json_decode($suldata,true);
		if($suldata){
			foreach ($tdata["results"]["n_tbk_item"] as $key => $vl) {

				$paremat["num_iid"]=$vl["num_iid"];//商品ID
				$paremat["item_url"]=$vl["item_url"];//商品地址

				$paremat["pict_url"]=$vl["pict_url"];//商品主图
				$paremat["provcity"]=$vl["provcity"];//宝贝所在地
				$paremat["reserve_price"]=$vl["reserve_price"];//	

				$paremat["title"]=$vl["title"];//商品标题
				$paremat["user_type"]=$vl["user_type"];//卖家类型，0表示集市，1表示商城
				$paremat["zk_final_price"]=$vl["zk_final_price"];//商品折扣价格
				$paremat["user_type"]=$vl["user_type"];//商品折扣价格


				$paremat['nick']=$vl['nick'];//卖家昵称
				$paremat['seller_id']=$vl['seller_id'];//卖家id

				$paremat['volume']=$vl['volume'];//30天销量
				$paremat['tk_rate']=$vl['tk_rate'];

				$paremat['uptime']=time();
				M('taobaoproduct')->where("proid=%s",$vl["num_iid"])->save($paremat);
			}
			
		}
		dump($tdata);

    }
}

