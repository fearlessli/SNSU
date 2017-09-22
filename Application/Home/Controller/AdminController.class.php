<?php
namespace Home\Controller;
use Think\Controller;
class AdminController extends CommonController
{
    private function check_access()
    {
        $user = M('user');
        $map['id'] = session('user_id');
        $map['status'] = 2;
        $admin = $user->where($map)->find();
        if ($admin) {
            return true;
        } else {
            return false;
        }
    }

    public function index()
    {
        if ($this->check_access()) {
            $this->display('index');
        } else {
            $this->display("login");
        }
    }

    public function login()
    {
        $user = M('user');
        $email = I('email');
        $password = I('password');
        $map['email'] = $email;
        $map['status'] = 2;
        $map['password'] = md5(md5('fuck' . $password));
        $admin = $user->where($map)->find();
        if ($admin) {
            session('user_id', $admin['id']);
            $this->display('index');
        } else {
            $this->error('登录失败', './index', 5);
        }
    }

    public function quit()
    {
        cookie(null, 'user_');
        session(null);
        $this->index();
    }

    /* 
        get info to admin
        {picture, blog, user_website, user, last_login_id, last_login_time}
    */
    public function getInfo()
    {
        $res['status'] = '0';      // 0 => failed

        if ($this->check_access()) {        
            $start = I('start');
            $items = I('items');

            $user = D('UserWebsiteRelationView');      // table user
            $map['status'] = 1;     // checked user
            $userInfo = M('user')->order('last_login_time desc')->limit($start, $items)->where($map)->select();//all user info
            $userNumber = count($userInfo);

            $info = $user->group('id')->select();//all info form model

            // get data of user
            for ($j = 0; $j < $userNumber; $j++) {

                $res['user_map'][$j]['user_id'] = $userInfo[$j]['id'];//user id
                $res['user_map'][$j]['email'] = $userInfo[$j]['email'];
                $res['user_map'][$j]['name'] = $userInfo[$j]['name'];
                $res['user_map'][$j]['status'] = $userInfo[$j]['status'];
                $res['user_map'][$j]['last_login_time'] = $userInfo[$j]['last_login_time'];
                $res['user_map'][$j]['last_login_ip'] = $userInfo[$j]['last_login_ip'];
                $res['user_map'][$j]['avator'] = $userInfo[$j]['avator'];
                $res['user_map'][$j]['secret'] = $userInfo[$j]['secret'];
                $map2['uid'] = $userInfo[$j]['id'];
                $res['user_map'][$j]['user_website'] = $user->where($map2)->field('wid,wname,domain,wpassword,templete_id,wstatus,visit_num,logo_path,title,sub_title,background,fontsize,preview,wsecret')->select();

            }
           $res['status'] = '1';
           $res['user_count'] = M('user')->where($map)->count();
           $res['blog_count'] = M('blog')->count();
           $res['pic_count'] = M('pic')->count();
           $res['user_website_count'] = M('user_website')->count();
        }

        $this->ajaxReturn($res);

    }

    // 未审核用户列表
    public function getUncheckedUser()
    {
        if ($this->check_access()) {
            $start = I('start');
            $items = I('items');
            $user = M('user');
            $map['status'] = 3;     // unchecked user
            $unchecked_user['user_count'] = $user->where($map)->count();
            $unchecked_user['user_map'] = $user->where($map)->order('last_login_time desc')->limit($start, $items)->select();
        }

        $this->ajaxReturn($unchecked_user);
    }

    // 用户审核功能
    public function checkUser()
    {
        if ($this->check_access()) {
            $map['email'] = I('email');
            $data['status'] = I('status');
            $user = M('user')->where($map)->save($data);
            $this->success('success');
        }
        $this->error('failed');
    }

    // get feedback     {email, content, time}
    public function getFeedback()
    {
        if ($this->check_access()) {
            $user = D('UserWebsiteRelationView');      // table user
            $start = I('start');
            $items = I('items');
            $feed_back = M('feedback');
            $res['user_count'] = $feed_back->count();
            $res['user_map'] = $feed_back->order('time desc')->field('email,content,time')->limit($start, $items)->select();
            $this->ajaxReturn($res);
        }
        $this->error('get feedback failed');
    }

    public function getPicture()
    {
        if ($this->check_access()) {
            $start = I('start');
            $items = I('items');
            $user = M('pic');
            $map['status'] = 1;
            $res['user_count'] = $user->count();
            $res['user_map'] = $user->order('time desc')->where($map)->limit($start, $items)->select();
            $this->ajaxReturn($res);
        }
        $this->error('get picture failed');
    }

    public function deletePicture()
    {
        if ($this->check_access()) {

            $map['path'] = I('path');
            $data['status'] = 2;
            $pic = M('pic')->where($map)->save($data);
            //图片所属栏目的id
            $cid = M('pic')->where($map)->getField('gid');
            $column_map['id'] = $cid;
            //获取用户id
            $uid = M('column')->where($column_map)->getField('uid');
            $user_map['id'] = $uid;
            //获取用户的详细信息
            $userInfo = M('User')->where($user_map)->select();
            $this->ajaxReturn($userInfo);
        }
        $this->error('delete failed');
    }
}