<?php

/**
 * 验证码处理
 */
namespace Api\Controller;

use Think\Controller;

class TbproductController extends Controller{

	protected $appkey="23491756";
	protected $secret="fcc0d6a8ae5bf736a24a4d5f3f73868c";

	//采集数据
    public function receiveProduct1(){
		$data['status']=0;
		$data['msg']="非法数据";
        if(IS_POST){

           $data['status']=1;
		   $data['data']=implode(',',$_POST);
		   $dataPost=explode(',', $data['data']);
		   $savedata=array();
		   foreach ($dataPost as $ky => $vl) {
			   	   	try{
			   	   		$yh=explode('-',$vl);
			   	   		if($yh[1]){
			   	   			$parema['coupon']=$yh[1];	
			   	   		}

			   	   		$yh=explode(':',$vl);
			   	   		if($yh[1]){
			   	   			$parema['commission']=$yh[1];	
			   	   		}
			   	   		$parema['proid']=$yh[0];

			   	   		$sul=M('taobaoproduct')->add($parema);

			   	   	}catch(Exception $e){
						
					}
		   	}


        }

		$this->ajaxreturn($data);
    }
    //采集数据
    public function receiveProduct(){
		$data['status']=0;
		$data['msg']="非法数据";
       // if(IS_POST){
       	   $suldel=M('taobaoproduct')->execute("DELETE FROM ty_taobaoproduct");
       	 
		   $_POST[]="16092810656:35-3";
		   $_POST[]="18986314630:40.1-10";
           $data['status']=1;
		   $data['data']=implode(',',$_POST);
		   $dataPost=explode(',', $data['data']);
		   $savedata=array();
		   $numid=array();
		   foreach ($dataPost as $ky => $vl) {
			   	   	try{
			   	   		$yh=explode('-',$vl);
			   	   		if($yh[1]){
			   	   			$parema['coupon']=$yh[1];	
			   	   		}

			   	   		$yh=explode(':',$vl);
			   	   		if($yh[1]){

			   	   			$yc=explode('-',$yh[1]);
			   	   			$parema['commission']=$yc[0];	
			   	   		}
			   	   		$parema['proid']=$yh[0];
			   	   		$numid[$ky]=$yh[0];
			   	   		//$sul=M('taobaoproduct')->add($parema);
			   	   		$savedata[$yh[0]]=$parema;


			   	   	}catch(Exception $e){
						
					}
		   	}
		   	$proinfo=array();
		   	if($savedata){
		   		$num=count($numid);
		   		if($num>0){
		   			for ($i=0; $i <ceil($num/30); $i++) { 
		   				$subary=array_slice($numid,$i*30,30);
		   				$proinfo=$this->getTbproductdata($subary);
		   				if($proinfo){
		   					$parema=$proinfo['results']["n_tbk_item"] ;
		   					foreach ($parema as $keyt => $vlt) {
			   					$parema[$keyt]["commission"]=$savedata[$vlt['num_iid']]["commission"];
			   					$parema[$keyt]["coupon"]=$savedata[$vlt['num_iid']]["coupon"];
			   					$parema[$keyt]["proid"]=$vlt['num_iid'];
			   					$parema[$keyt]["crtime"]=time();
			   					unset($parema[$keyt]["small_images"]);
			   				}
			   				$sul=M('taobaoproduct')->addAll($parema);
		   				}
		   				
		   			}
		   		}
		   	}



     //   }

		$this->ajaxreturn($numid);
    }
    //得到用户自选产品
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
    //得到100个产品信息推荐产品
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
   	//得到80个产品信息
    public function pingzi(){
    	$data=M('taobaoproduct')->limit(80)->select();
    	$this->ajaxreturn($data);
    }
    //增加产品
    public function adddata(){
    	$data['status']=0;
		$data['msg']="非法数据";
        if(IS_POST){
           $data['status']=1;
		   $data['data']=implode(',',$_POST);
		   $dataPost=explode(',', $data['data']) ;
		   	   foreach ($dataPost as $ky => $vl) {
			   	   	try{
			   	   		$yh=explode('-',$vl);
			   	   		if($yh[1]){
			   	   			$parema['coupon']=$yh[1];	
			   	   		}

			   	   		$yh=explode(':',$vl);
			   	   		if($yh[1]){
			   	   			$parema['proid']=$yh[1];	
			   	   		}
			   	   		$parema['proid']=$yh[0];
			   	   		$parema['crtime']=time();			   	   		
			   	   		$sul=M('taobaoproduct')->add($parema);

			   	   	}catch(Exception $e){
						
					}
		   	   }
        }
    }
	//得到1个产品以内的产品详情
	public function addonelink(){
		$result['status']=0;
		$result['msg']="数据信息";
		if(!I("proid")){
			$this->ajaxreturn($result);
			exit();
		}
		include SITE_PATH."simplewind/Core/Library/Taobao/TopSdk.php";
		$numIids=I("proid");

		$c = new \TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$c->format="json";
		$req = new \TbkItemInfoGetRequest;
		$req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,nick,seller_id,volume,tk_rate");
		
		$req->setPlatform("1");
		$req->setNumIids($numIids);
		$resp = $c->execute($req);
		$suldata=json_encode($resp,true);
		$suldata=json_decode($suldata,true);
		if($suldata){	
				
		        $vl=$suldata["results"]["n_tbk_item"][0];	
		      
				if(!$vl){
					$this->ajaxreturn($result);
					exit();
				}
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
		}
		
		$paremat['proid']=I("proid");
		$paremat['crtime']=time();		
		$sul=M('taobaoproduct')->where("proid='%s'",$numIids)->count();
		if($sul<=0){
			$sul=M('taobaoproduct')->add($paremat);
			$paremat['status']=1;
			$this->ajaxreturn($paremat);
		}else{
			$paremat['status']=2;
			$this->ajaxreturn($paremat);
		}
	}
	//得到40个产品以内的产品详情
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
		$this->ajaxreturn($tdata);
    }


    //得到40个产品以内的产品详情
    //$sul一维数据
    public function getTbproductdata($sul){
    	include SITE_PATH."simplewind/Core/Library/Taobao/TopSdk.php";
      
       
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
		return $tdata;
    }
}

