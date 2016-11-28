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
       if(IS_POST){
       	   $suldel=M('taobaoproduct')->execute("DELETE FROM ty_taobaoproduct");
       	   //$tin="524136656574:30.3-10,539100290148:30-30,537736048477:30.5-5,542023650188:30.5-60,540362231157:65-10,520237346984:40-5,526237716321:30-10,540261248782:30-20,534680941569:38.3-40,541001347057:40-50,540128988803:30.5-40,534029788510:43.7-20,537645481161:30.3-100,538621872542:30.5-30,538277253475:30-10,41199329143 :39.4-10,521068608018:42.5-20,537222010639:39.9-20,528585397824:30-5,540576762114:38-60,527916914747:20.4-30,538683371998:40.5-10,540517419790:31-20,530520962380:40-5,542090311374:40-40,536508160022:40-80,45424135901:30-3,535435018804:20-10,41626689214:30.5-15,537429606358:30.5-10,520549919709:31.4-3,538674746724:40.5-20,521437929090:30.5-30,537345418343:25.5-20,539902169675:30.5-40,537903139776:20.5-80,528262036114:40-3,538208312552:25-40,533898805794:50-10,527115612898:30-20,537945294936:20.5-80,40362266643:40-10,536493786765:30-10,538538385385:30.5-20,539043312055:30.5-40,539609623834:50-10,529730563956:30-10,537480562507:42.8-30,530574942597:36.8-60,536760546299:40.5-200,539700391911:30.5-15,541280956975:30-10,540671028958:40.5-10,522559943856:21-20,539673695221:40.3-40,523233199865:50-3,540510309332:40.5-20,538542539234:35-100,537016351300:47.5-10,521233834543:30-10,537220720971:20-5,537989837271:30-60,527039772568:30.5-10,537113614731:30-5,526060264341:30-40,36790374725:30-3,520271282593:40-10,541050076484:34.5-100,539520387577:30-5,542155604179:40.5-5,534080378247:30.3-3,37917389849 :21-20,43454942991:30-3,16907601665:57.5-50,38702743736:40.5-5,527558485759:40.5-5,539696955672:30.5-40,530778046285:41-100,532932993865:30-20,532689385921:30-10,536104092690:38.5-10,539143337414:30.5-30,525039783463:30.5-15,525229598176:39.4-20,531131618593:30.5-80,534745730431:30.5-20,525491078001:42.6-30,530822254549:39-10,542138643735:40.5-1,523153350874:20.5-15,530817415012:31.5-5,537159280310:35-5,537032556037:30-5,541967923783:41.7-30,540910515653:25.01-20,38552488960 :30.5-10,539147912414:35.5-10,529546768467:30-50,40950537235:40.5-20";
       	   $tin=I("post.productlist");
       	   
           $data['status']=1;
		   $data['data']=implode(',',$tin);

		   $dataPost=explode(',',$tin);
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
			   	   		$sul=M('taobaoproduct')->add($parema);
			   	   		$savedata[$yh[0]]=$parema;
			   	   	}catch(Exception $e){
						
					}
		   	}

		   	$proinfo=array();
		   	if($savedata){
		   		$num=count($numid);
		   		if($num>0){
		   			for ($i=0; $i <ceil($num/40); $i++) { 
		   				$subary=array_slice($numid,$i*40,40);
		   				
		   				$url="http://www.tianying.com/index.php?g=Api&m=Tbproduct&a=getTbproduct";
		   				$data['name']=$i;
		   				$data=file_get_contents($url);
		   				//$aryday=json_encode($data,true);
		   				
		   				//$data=$this->curlget($url);
		   				/*
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
			   				var_dump($parema);
			   				//exit();
		   				}
		   				*/
		   			}
		   		}
		   	}
        }

		$this->ajaxreturn($numid);
    }
    //得到用户自选产品
    public function userpro(){
    	$userid=I("post.userid");
    	$result['status']=0;
    	$result['msg']="数据异常";
    	if(!$userid){
    		$this->ajaxreturn($result);
    		exit();	
    	}
    	$where['up.userid']=$userid;
        $data=M('taobaoproduct')->alias("td")->join('__USERPRO__ as up on up.proid=td.proid','left')->where($where)->limit(40)->select();
        if($data){
        	$sul=M("users")->where("id=".$userid)->find();
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
		if($sul){
			$data['status']=1;
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
        $sul=M('taobaoproduct')->where("num_iid=''")->limit(40)->getfield('proid',true);
       
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

    public function curlget($url){
    	$ch = curl_init();
	　　curl_setopt($ch, CURLOPT_URL, $url);
	　　curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	　　curl_setopt($ch, CURLOPT_HEADER, 0);
		$output=curl_exec($ch);
		curl_close($ch);
		return $output;
    }

}

