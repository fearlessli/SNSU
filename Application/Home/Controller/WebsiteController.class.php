<?php
namespace Home\Controller;

use Think\Controller;

//个人网站展示页面
class WebsiteController extends CommonController
{
    public function _initialize()
    {
        $domain = $_SERVER['SERVER_NAME'];
        $length = strlen($domain);
        //dump($length);
        $domain = substr($domain, -$length, -8);




        $m = M('website');
        $map['domain'] = $domain;
        //网站的id
        $wid = $m->where($map)->getField('id');

        if($m->where($map)->find()){



        $password = $m->where($map)->getField('password');
        if ($password == '') {
            // $this->redirect('');//跳转到网站展示页
        } else {
            //$this->redirect('');//跳转到输入安全密码的页面
        }
        } else {
            $this->error('域名不存在');
        }
    }



    public function getDomain()
    {
        $domain = $_SERVER['SERVER_NAME'];
        $length = strlen($domain);
        $domain = substr($domain, -$length, -8);
        return $domain;
    }

    //获取某个画廊栏目的属性
    public function ajaxGetOneColumnInfo()
    {
        $column = M('Column');
        $column_map['id'] = I('gid');
        $info = $column->where($column_map)->select();
        $this->ajaxReturn($info, 'JSON');

    }

    public function index()
    {

        $m = M('website');
        $map['domain'] = $this->getDomain();
        $wid = $m->where($map)->getField('id');
        //当前总访问量
        $cur_visit_num = $m->where($map)->getField('visit_num');
        $data['visit_num'] = $cur_visit_num + 1;
        $m->where($map)->save($data);

        //今日访问量
        $visit_log = M('visit_log');
        $visit_log_map['wid'] = $wid;
        $visit_log_map['time'] = date("Y-m-d");
        if ($visit_log->where($visit_log_map)->find()) {
            $cur_today_visit_num = $visit_log->where($visit_log_map)->getField('number');
            $visit_log_data['number'] = $cur_today_visit_num + 1;
            $visit_log->where($visit_log_map)->save($visit_log_data);

        } else {
            $visit_log_data['wid'] = $wid;
            $visit_log_data['time'] = date("Y-m-d");
            $visit_log_data['number'] = 1;
            $visit_log->add($visit_log_data);

        }


//        $name = $m->where($map)->getField('name');
        $this->display();

    }

    public function getWidByDomain()
    {
        $m = M('Website');
        $map['domain'] = $this->getDomain();
        $wid = $m->where($map)->getField('id');
        return $wid;
    }

    //ajax获取当前网站的所有栏目
    public function ajaxGetAllColumn()
    {
        $column_m = M('Column');
        $map['wid'] = $this->getWidByDomain();
        $list = $column_m->where($map)->select();
        $this->ajaxReturn($list, 'JSON');
    }

    //ajax获取首页的照片
    public function ajaxGetIndexPic()
    {
        $m = M('IndexPic');
        $map['wid'] = $this->getWidByDomain();
        $list = $m->where($map)->select();
        $this->ajaxReturn($list, 'JSON');
    }

    public function ajaxGetNewestIndexPic()
    {
        $m = M('IndexPic');
        $map['wid'] = $this->getWidByDomain();
        $list = $m->where($map)->order('id desc')->limit(1)->select();
        $this->ajaxReturn($list, 'JSON');
    }

    //ajax获取某个画廊里面的某个图片
    public function ajaxGetOnePic()
    {
        $pic_m = M('Pic');
        //$blog_map['uid'] = $_SESSION['uid'];
        $pic_map['gid'] = I('column_id');
        $pic_map['id'] = I('id');
        $pic_map['btn'] = I('btn');

        //$blog_map2['uid'] = $_SESSION['uid'];
        $pic_map2['gid'] = I('column_id');
        if ($pic_map['btn'] == -1) {
            $pic_map2['id'] = array('lt', $pic_map['id']);
            $pic_map['id'] = $pic_m->where($pic_map2)->order('id desc')->limit(1)->getField('id');
        } else if ($pic_map['btn'] == 1) {
            $pic_map2['id'] = array('gt', $pic_map['id']);
            $pic_map['id'] = $pic_m->where($pic_map2)->order('id')->limit(1)->getField('id');
        } else {
            $pic_map['id'] = I('id');
        }
        $info = $pic_m->where($pic_map)->select();
        $this->ajaxReturn($info, 'JSON');
    }


    //获取一篇日志
    //如果不发送日志id，则默认发送过来最新的日志
    public function ajaxGetOneBlog()
    {
        $blog_m = M('Blog');
        //$blog_map['uid'] = $_SESSION['uid'];
        $blog_map['column_id'] = I('column_id');
		$bid=I('column_id');
        $blog_map['id'] = I('id');
			$bid=I('id');
        $blog_map['btn'] = I('btn');


        if ( $bid == '') {
            $map['column_id'] = I('column_id');
            $info = $blog_m->where($map)->order('time desc')->limit(1)->select();
        } else {
            $blog_map2['column_id'] = I('column_id');
            if ($blog_map['btn'] == -1) {
                $blog_map2['id'] = array('lt', $blog_map['id']);
                $blog_map['id'] = $blog_m->where($blog_map2)->order('id desc')->limit(1)->getField('id');
            } else if ($blog_map['btn'] == 1) {
                $blog_map2['id'] = array('gt', $blog_map['id']);
                $blog_map['id'] = $blog_m->where($blog_map2)->order('id')->limit(1)->getField('id');
            } else {
                $blog_map['id'] = I('id');
            }
            $info = $blog_m->where($blog_map)->select();
        }

        //$blog_map2['uid'] = $_SESSION['uid'];

        $info[0]['content'] = htmlspecialchars_decode($info[0]['content']);
        $info[0]['sub_title'] = htmlspecialchars_decode($info[0]['sub_title']);

        $this->ajaxReturn($info, 'JSON');


//        $list = $blog_m->where($blog_map)->select();
//        $this->assign('list', $list);

    }

    //ajax遍历某个画廊里面的所有图片信息
    public function ajaxGetGalleryList()
    {

        $pic_m = M('pic');
        $pic_map['gid'] = I('gid');//所属画廊的id
        //合法性检测
//        $gallery_map['uid'] = $_SESSION['uid'];
        $gallery_map['gid'] = I('gid');
//        if (M('Gallery')->where($gallery_map)->find()) {
            $list = $pic_m->where($pic_map)->order('id desc')->select();
            $this->ajaxReturn($list, 'JSON');
//        } else {
//            $this->ajaxReturn('操作非法', 'JSON');
//        }

    }

    public function ajaxGetOneColumn()
    {
        $cid = I('cid');
        $m = M('column');
        $map['id'] = $cid;
        $list = $m->where($map)->select();
        $this->ajaxReturn($list, 'JSON');
    }

    //获取当前网站信息
    public function getCurWebsiteInfo()
    {
        $map['id'] = $this->getWidByDomain();
        $m = M('Website');
        $info = $m->where($map)->select();
        $this->ajaxReturn($info, 'JSON');
    }


    //遍历画廊栏目信息：当前用户下 当前网站下 所有的画廊类型的栏目的信息

    public function getGalleryColumn()
    {
        $GalleryColumn_m = M('Column');
        $GalleryColumn_map['uid'] = $_SESSION['uid'];
        $GalleryColumn_map['type'] = 1;
        $GalleryColumn_map['status'] = 1;
        $GalleryColumn_map['wid'] = $_SESSION['wid'];//当前网站的id
        $list = $GalleryColumn_m->where($GalleryColumn_map)->select();
        $this->assign('list', $list);

    }
	
	public function ajaxGetGalleryColumn()
    {
        $GalleryColumn_m = M('Column');
        $GalleryColumn_map['uid'] = $_SESSION['uid'];
        $GalleryColumn_map['type'] = 1;
        $GalleryColumn_map['status'] = 1;
        $GalleryColumn_map['wid'] = $_SESSION['wid'];//当前网站的id
        $list = $GalleryColumn_m->where($GalleryColumn_map)->select();
        $this->ajaxReturn($list, 'JSON');

    }


    //遍历某个画廊里面的所有图片信息
    public function getGalleryList()
    {

        $pic_m = M('pic');
        $pic_map['gid'] = I('gid');//所属画廊的id
        //合法性检测
        $gallery_map['uid'] = $_SESSION['uid'];
        $gallery_map['gid'] = I('gid');

        if (M('Gallery')->where($gallery_map)->find()) {
            $list = $pic_m->where($pic_map)->order('id desc')->select();
            $this->assign('list', $list);
        } else {
            $this->error('非法操作');
        }

    }

    //获取访问密码的方法
    public function getAccessPassword()
    {
        $map['id'] = $this->getWidByDomain();
        $password = M('website')->where($map)->getField('password');
        $this->ajaxReturn($password, 'JSON');

    }


}