<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends CommonController
{
    private function get_live_time()
    {
        return 3600*12;
    }

    private function check_verify($code, $id = '')
    {
        $config = array(
            'reset' => false // 验证成功后是否重置，这里才是有效的。
        );
        $verify = new \Think\Verify($config);
        return $verify->check($code, $id);
    }

    private function encrypt($str)
    {
        return md5(md5('fuck' . $str));
    }


    public function index()
    {
        if (session('user_id')) {
            $this->display();
        } else {
            $this->redirect('Index/index');
        }
    }


    public function doLogin()
    {
        $user = M('user');

        $res = '';
        $password = I('password');
        $email = I('email');
        if ($password != '')
        {
            $password = $this->encrypt($password);
        }

        if($password != '' && $email != '')
        {
            $res = $user->where("password='%s' and email='%s'", array($password,$email))->select();
        }

        if(!empty($res))
        {
            $data['last_login_time'] = time();
            $data['last_login_ip'] = get_client_ip();
            $map['password'] = $password;
            $map['email'] = $email;
            $user->where($map)->save($data);

            // set cookie and session
            cookie('user_id',$res[0]['id'], $this->get_live_time());
            cookie('user_name', $res[0]['name'], $this->get_live_time());
            cookie('user_email', $res[0]['email'], $this->get_live_time());
            cookie('user_avator_path', $res[0]['avator'], $this->get_live_time());
            cookie('user_login_time', $res[0]['last_login_time'], $this->get_live_time());
            cookie('user_login_ip', $res[0]['last_login_ip'], $this->get_live_time());
            session('user_id',$res[0]['id'], $this->get_live_time());
            $this->ajaxReturn(1);
        } else {
            // login failed
            $this->ajaxReturn('login failed');
        }
    }

    public function doReg()
    {
        $user = M('user');

        $password = I('password');
        $email = I('email');
        $verify_code = I('verify_code');
        if(!($this->check_verify($verify_code))){;
            $this->ajaxReturn(2);//验证码错误
        }
        else if ($password != '')
        {
            $md5Password = $this->encrypt($password);
        }

        // check the exist email
        $res = $user->where("email='%s'", array($email))->select();

        if($email != '' && $password != '' && empty($res))
        {
            $data['password'] = $md5Password;
            $data['email'] = $email;
            // $data['status'] = 1; //数据库已经设置默认值
            $data['last_login_time'] = time();
            $data['last_login_ip'] = get_client_ip();
            $t_res = $user->data($data)->add();
            // dump($t_res);
            if ($t_res) {
                $this->send_mail($email);
            }

            // set cookie and session
            cookie('user_id', $t_res['id'], $this->get_live_time());
            cookie('user_name', $t_res['name'], $this->get_live_time());
            cookie('user_email', $t_res['email'], $this->get_live_time());
            cookie('user_avator_path', $t_res['avator'], $this->get_live_time());
            cookie('user_login_ip', $t_res['last_login_ip'], $this->get_live_time());
            cookie('user_login_time', $t_res['last_login_time'], $this->get_live_time());
            session('user_id', $t_res, $this->get_live_time());

            // 1 => Register Success and Login auto
            $this->success('register success', 'index');
        } else {
            // 0 => Register Failed
            $this->error('register failed');
        }
    }

//设置用户名
    public function setNickName()
    {
        $data['name'] = I('nickname');
        $map['id'] = $_SESSION['user_id'];
        $m = M('User');
        if ($m->where($map)->save($data)) {
            $this->ajaxReturn(1);//设置成功
        } else {
            $this->ajaxReturn(0);//设置失败
        }
    }

    //设置个人描述
    public function setIntro()
    {
        $data['application'] = I('intro');
        $map['id'] = $_SESSION['user_id'];
        $m = M('User');
        if ($m->where($map)->save($data)) {
            $this->ajaxReturn(1);//设置成功
        } else {
            $this->ajaxReturn(0);//设置失败
        }
    }

    public function accountInfoAvator()
    {

        $user = M('user');

        $avator_path = I("path");

        if ($avator_path != '')
        {
            $data['avator'] = $avator_path;
            $map['id'] = session('user_id');
            $user->where($map)->save($data);

            cookie('user_avator_path', $avator_path, $this->get_live_time());

            $this->success('get accountInfo success', 'User/index');
        } else {
            $this->error('get accountInfo failed');
        }
    }

    public function accountInfoName()
    {
        $user = M('user');

        $user_name = I("username");

        if ($user_name != '')
        {
            $data['name'] = $user_name;
            $map['id'] = session('user_id');
            $user->where($map)->save($data);

            cookie('user_name', $user_name, $this->get_live_time());

            $this->success('get accountInfo success', 'User/index');
        } else {
            $this->error('get accountInfo failed');
        }
    }

    //取得用户信息
    public function getAccountInfo()
    {
        $user = M('user');
        $map['id'] = session('user_id');
        $info = $user->where($map)->select();
        $this->ajaxReturn($info, 'JSON');
    }

    public function quit()
    {
        cookie(null, 'user_');
        session(null);
        $this->index();
    }

    public function modifyPassword()
    {
        $user = M('user');

        $res = '';
        $old_password = I('oldpassword');
        $new_password = I('newpassword');

        if ($old_password != '' && $new_password != '')
        {
            $old_password = $this->encrypt($old_password);
            $new_password = $this->encrypt($new_password);
            $res = $user->where("password='%s' and id='%d'", array($old_password, session('user_id')))->select();
        }

        if (!empty($res))
        {
            $data['password'] = $new_password;
            $map['password'] = $old_password;
            $map['id'] = session('user_id');
            $res = $user->where($map)->save($data);
            $this->ajaxReturn(1);
            //$this->success('modifyPassword success', 'User/index');
        } else {
            $this->error('modifyPassword failed');
        }
    }

    public function getbackAccount()
    {
        $user = M('user');
    }

    public function userWebsite()
    {
        $m = D('UserWebsiteView');
        $map['uid'] = $_SESSION['user_id'];
        $list = $m->where($map)->select();
        $this->assign('list', $list);
        $this->display();
    }

    function send_mail($email)
    {
        $url = 'http://sendcloud.sohu.com/webapi/mail.send.json';
        $API_USER = 'snsume';
        $API_KEY = 'Q9vNQ9dQwWQTWkEb';

        //不同于登录SendCloud站点的帐号，您需要登录后台创建发信子帐号，使用子帐号和密码才可以进行邮件的发送。
        $param = array(
            'api_user' => $API_USER,
            'api_key' => $API_KEY,
            'from' => 'postmaster@snsu.me',
            'fromname' => '向素团队',
            'to' => $email,
            'subject' => '来自向素的第一封邮件！',
            'html' => '你太棒了！注册成功！接下来快登录前台去完善账户信息吧！',
            'resp_email_id' => 'true');

        $data = http_build_query($param);

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $data
            ));

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }

    //重置密码
    function resetPasswordIndex(){
        $user = M('user');

        $secret = I('secret');

        $this -> assign('secret',$secret);
        if($secret==''){
            $this->error('非法操作');
        }
        $where['secret'] = $secret;
        $is = $user -> where($where) ->find();
        if(!$is){
            $this ->error('请先申请重置密码');
        }
        $this->display();
    }

    function resetPassword(){
        $user = M('User');

        $password = I('password');
        $repassword = I('repassword');
        $secret = I('secret');

        if($password!=$repassword){
            $this->error('密码不一致');
        }
        $data['password'] = $password;

        dump($secret);

        $where['secret'] = $secret;
        $userinfo = $user -> where($where) ->find();
        $map['id'] = $userinfo['id'];

        $is = $user -> where($where) -> save($data);
        if($is){
            $reSecret['secret'] = '';
            $user -> where($map) -> save($reSecret);
            $this ->success('重置成功');
        }else{
            $this->error('重置失败');
        }
    }

    function  reset_email($email){
        $user = M('User');

        $temp = 'qbw@djcbq%s^d&nljqw*bejbq'.$email.'xzkjcmab$jgqw%jbdskadq;jwdkjsah%qmbas3$$%@#wd';
        $data['secret'] = md5(md5($temp));

        $is=$user -> where('email='.$email) -> save($data);
        if(!$is){
            $this->error('生成链接失败');
        }

        $url = 'http://sendcloud.sohu.com/webapi/mail.send.json';
        $API_USER = 'snsume';
        $API_KEY = 'Q9vNQ9dQwWQTWkEb';

        //不同于登录SendCloud站点的帐号，您需要登录后台创建发信子帐号，使用子帐号和密码才可以进行邮件的发送。
        $param = array(
            'api_user' => $API_USER,
            'api_key' => $API_KEY,
            'from' => 'postmaster@snsu.me',
            'fromname' => '重置密码',
            'to' => $email,
            'subject' => '来自向素的第一封邮件！',
            'html' => '点击链接重新设置密码 !<a href="http://w.snsu.me/User/resetPasswordIndex/secret/'.$data['secret'].'>测试</a>',
            'resp_email_id' => 'true');

        $data = http_build_query($param);

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $data
            ));

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }
}