<?php
/*
 *      _______ _     _       _     _____ __  __ ______
 *     |__   __| |   (_)     | |   / ____|  \/  |  ____|
 *        | |  | |__  _ _ __ | | _| |    | \  / | |__
 *        | |  | '_ \| | '_ \| |/ / |    | |\/| |  __|
 *        | |  | | | | | | | |   <| |____| |  | | |
 *        |_|  |_| |_|_|_| |_|_|\_\\_____|_|  |_|_|
 */
/*
 *     _________  ___  ___  ___  ________   ___  __    ________  _____ ______   ________
 *    |\___   ___\\  \|\  \|\  \|\   ___  \|\  \|\  \ |\   ____\|\   _ \  _   \|\  _____\
 *    \|___ \  \_\ \  \\\  \ \  \ \  \\ \  \ \  \/  /|\ \  \___|\ \  \\\__\ \  \ \  \__/
 *         \ \  \ \ \   __  \ \  \ \  \\ \  \ \   ___  \ \  \    \ \  \\|__| \  \ \   __\
 *          \ \  \ \ \  \ \  \ \  \ \  \\ \  \ \  \\ \  \ \  \____\ \  \    \ \  \ \  \_|
 *           \ \__\ \ \__\ \__\ \__\ \__\\ \__\ \__\\ \__\ \_______\ \__\    \ \__\ \__\
 *            \|__|  \|__|\|__|\|__|\|__| \|__|\|__| \|__|\|_______|\|__|     \|__|\|__|
 */
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace Portal\Controller;
use Common\Controller\HomebaseController; 
/**
 * 首页
 */
class IndexController extends HomebaseController {
	
    //首页 小夏是老猫除外最帅的男人了
	public function index() {
        $data=M('taobaoproduct')->limit(40)->select();
        if(session("user")['id']){
            $where['userid']=session("user")['id'];
            $count=M("userpro")->where($where)->count();
        }
        $this->assign('count',$count);
        $this->assign('list',$data);
    	$this->display(":index");
    }
    public function xplib(){
        if(!session("user")['id']){
           $this->redirect("user/login/index");
        }
       
        $map['userid']=session("user")['id'];
        $count=M("userpro")->where($map)->count();

        $this->assign('count',$count);
        $where['up.userid']=session("user")['id'];
        $data=M('taobaoproduct')->alias("td")->join('__USERPRO__ as up on up.proid=td.proid','left')->where($where)->limit(40)->select();
        $this->assign('list',$data);
        $this->display(":xplib");
    }

    public function ajaxlibdel(){
        $user=session("user");
        $return['status']=0;
        $return['msg']="发生错误";

        if(!$user){
            $return['status']=2;
            $return['msg']="请先登入";
            $this->ajaxreturn($return);
            exit();
        }
        $proid=I("proid");
        if(!$proid){
            $return['status']=2;
            $return['msg']="请选择产品";
            $this->ajaxreturn($return);
            exit();
        }

        $userid=$user['id'];
        $where['userid']=$userid;
        $where['proid']=I("proid");
        $count=M("userpro")->where($where)->count();
 
        if($count==0){
            $return['status']=3;
            $return['msg']="选品库未找到产品";
            $this->ajaxreturn($return);
            exit();
        }

        $sul=M("userpro")->where($where)->delete();

        if($sul){
             $return['status']=1;
             $return['msg']="删除成功";
        }
        $this->ajaxreturn($return);
    }
    public function ajaxlib(){
        $user=session("user");
        $return['status']=0;
        $return['msg']="发生错误";

        if(!$user){
            $return['status']=2;
            $return['msg']="请先登入";
            $this->ajaxreturn($return);
            exit();
        }
        $proid=I("proid");
        if(!$proid){
            $return['status']=2;
            $return['msg']="请选择产品";
            $this->ajaxreturn($return);
            exit();
        }

        $userid=$user['id'];
        $where['userid']=$userid;
        $where['proid']=I("proid");
        $count=M("userpro")->where($where)->count();
 
        if($count>0){
            $return['status']=3;
            $return['msg']="已加入产品库";
            $this->ajaxreturn($return);
            exit();
        }

        $data['createtime']=time();
        $data['status']=1;
        $data['proid']=I("proid");
        $data['userid']=$userid;
        $sul=M("userpro")->add($data);

        if($sul){
             $return['status']=1;
             $return['msg']="增加成功";
        }
        $this->ajaxreturn($return);
    }

    public function test()
    {
        // $posts= sp_sql_posts_paged('field:posts.post_title;cid:1;',1);
        // print_r($posts);
        // $terms= sp_get_all_child_terms(2);
        // print_r($terms);
        // $terms = sp_get_child_terms(3);
        // print_r($terms);
        // $term = sp_get_term(1);
        // print_r($term);
        // $terms=sp_get_terms('field:term_id,path;order:path asc;',array('term_id'=>array('gt',2)));
        // print_r($terms);
        // $page=sp_sql_page(9);
        // print_r($page);
        // $pages=sp_sql_pages('field:post_title;where:`id`=1;',array());
        // $post=sp_sql_post(11, 'field:post_title');
        // print_r($post);
//         $posts=sp_sql_posts('field:posts.post_title,posts.id as post_id;where:posts.post_status=1;',array('posts.id'=>10));
//         print_r($posts);
//         $posts=sp_sql_posts_bycatid(1,'field:posts.post_title;',array('posts.id'=>array('gt',2)));
//         print_r($posts);
//         $posts=sp_sql_posts_paged_bycatid(1,'field:posts.post_title;',1);
//         print_r($posts);
//         $posts=sp_sql_posts_paged_bykeyword("delete from cmf_posts where 1;'\n",'field:posts.post_title;',1);
//         print_r($posts);
        $breadcrumd=sp_get_breadcrumb(3);
        print_r($breadcrumd);
        // $post=M('posts')
        // ->alias('posts')
        // ->join('__USERS__ as users on posts.post_author = users.id')
        // ->find();
        // print_r($post);
    }

}


