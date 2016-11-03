<?php

/**
 * 验证码处理
 */
namespace Api\Controller;

use Think\Controller;

class TbproductController extends Controller{

    public function receiveProduct(){
		$data['status']=0;
        if(IS_POST){
           $data['status']=1;
           $data['status']=1;
		   $data['data']=implode(',',$_POST);
		   
        }
		$data['msg']="非法数据";
		
		$this->ajaxreturn($data);
    }
}

