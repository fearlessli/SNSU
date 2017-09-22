<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
    function _initialize(){

            C('DEFAULT_THEME','default');

    }

    public function index()
    {
        $this->show("test");
    }

    public function uploadImg(){  
  
        $upload = new \Think\Upload();// 实例化上传类  
        $upload->maxSize   =     5242880 ;// 设置附件上传大小  
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型  
        $upload->rootPath = './Public/Uploads/'; // 设置附件上传根目录
        $upload->savePath  =      ''; // 设置附件上传（子）目录  
          
        // 上传文件   
        $info   =   $upload->uploadOne($_FILES['upload_image']);  
        if(!$info) {// 上传错误提示错误信息  
            //$this->error($upload->getError());  
            echo 0;
        } else {// 上传成功 获取上传文件信息

            //生成缩略图
            $image = new \Think\Image();
            $image->open('/home/wwwroot/lamp53/domain/xiangsu.ujn.me/web/Public/Uploads/' . $info['savepath'] . $info['savename']);
            $image->thumb(100, 100, \Think\Image::IMAGE_THUMB_FILLED)->save('/home/wwwroot/lamp53/domain/xiangsu.ujn.me/web/Public/Uploads/' . $info['savepath'] . 'thum_' . $info['savename']);
            //$this->display('templateList');
            echo "/Public/Uploads/" . $info['savepath'] . $info['savename'];
        }

    }
     public function simditorUploadImg(){

         $file = $_FILES['fileData'];//得到传输的数据
//得到文件名称
         $name = $file['name'];
         $type = strtolower(substr($name, strrpos($name, '.') + 1)); //得到文件类型，并且都转化成小写
         $allow_type = array('jpg', 'jpeg', 'gif', 'png'); //定义允许上传的类型
//判断文件类型是否被允许上传
         if (!in_array($type, $allow_type)) {
             //如果不被允许，则直接停止程序运行
             return;
         }
//判断是否是通过HTTP POST上传的
         if (!is_uploaded_file($file['tmp_name'])) {
             //如果不是通过HTTP POST上传的
             return;
         }
         $upload_path = "/home/wwwroot/lamp53/domain/xiangsu.ujn.me/web/Public/BlogUploads/"; //上传文件的存放路径
         $upload_path2 = "/Public/BlogUploads/"; //上传文件的存放路径
//开始移动文件到相应的文件夹
         if (move_uploaded_file($file['tmp_name'], $upload_path . md5($file['name']) . '.' . $type)) {
             $data['success'] = true;
             $data['msg'] = '上传成功';
             $data['file_path'] = $upload_path2 . md5($file['name']) . '.' . $type;
             $this->ajaxReturn($data, 'JSON');
         } else {
             $data['success'] = false;
             $data['msg'] = '上传失败';
             $data['file_path'] = $upload_path2 . md5($file['name']) . '.' . $type;
             $this->ajaxReturn($data, 'JSON');
         }
//         header('Content-type: application/json');
//
//         $img_content = I('fileDataFileName'); // 图片内容
//         $data['success'] = true;
//         $data['msg'] = '上传成功';
//         $data['file_path'] = $img_content;
//         $msg = '{"success":true,"file_path":' . $img_content . '}';
//         return $msg;
//         $this->ajaxReturn($img_content);
//         if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_content, $result)) {
//             $type = $result[2];
//             $new_file = "/home/wwwroot/lamp53/domain/xiangsu.ujn.me/web/Public/BlogUploads/" . md5($img_content) . $type;
//             if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $img_content)))) {
//                 $data['success'] = true;
//                 $data['msg'] = '上传成功';
//                 $data['file_path'] = "/Public/BlogUploads/" . md5($img_content) . $type;
//                 $this->ajaxReturn($data, 'JSON');
//             } else {
//                 echo 11;
//             }
//         }
    }

    function createVerify(){
        $config = array(
            'length' => 3,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
            'imageW'      =>    144,
            'imageH'      =>    46,
            'codeSet'     =>    '0123456789',
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }

    //申请注册时发的邮件
    function apply_mail()
    {
        $email = I('email');
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
            'html' => '尊敬的用户，已收到您的注册申请，请等待管理员审核。',
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

        $this->ajaxReturn($result, 'JSON');
    }

    //注册的申请得到批准之后发送的邮件
    function apply_success_mail()
    {
        $email = I('email');
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
            'subject' => '来自向素的邮件！',
            'html' => '你太棒了！注册成功！接下来快登录前台去体验吧！',
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
//        dump($result);
        $this->ajaxReturn($result, 'JSON');

    }


    //申请失败
    function apply_fail_mail()
    {
        //很抱歉，由于产品仍处于阶段，功能和体验不太完善，【理由】。公开注册的时候，我们会第一时间以邮件形式邀请您体验我们的产品和服务。非常感谢您的关注！
        $email = I('email');
        $reason = I('reason');
        if ($reason == '') {
            $reason = "您的的个人描述不足以完成注册申请，为了给更多的用户提供优质的服务，我们决定暂时驳回您的注册请求。";
        }
        $url = 'http://sendcloud.sohu.com/webapi/mail.send_template.json';
        $API_USER = 'snsume';
        $API_KEY = 'Q9vNQ9dQwWQTWkEb';

        $vars = json_encode(array("to" => array($email),
                "sub" => array("%reason%" => array($reason))
            )
        );

        //不同于登录SendCloud站点的帐号，您需要登录后台创建发信子帐号，使用子帐号和密码才可以进行邮件的发送。
        $param = array(
            'api_user' => $API_USER,
            'api_key' => $API_KEY,
            'from' => 'postmaster@snsu.me',
            'fromname' => '向素团队',
            'substitution_vars' => $vars,
            'template_invoke_name' => 'apply_fail',
            'subject' => '来自向素的邮件！',
            'resp_email_id' => 'true');


        $data = http_build_query($param);

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $data
            ));
        $context = stream_context_create($options);
        $result = file_get_contents($url, FILE_TEXT, $context);
//        dump($result);
        $this->ajaxReturn($result, 'JSON');

    }

    //删除图片通知
    public function delete_pic_mail()
    {
        $email = I('email');
        $username = I('username');
        $picname = I('picname');
        $url = 'http://sendcloud.sohu.com/webapi/mail.send_template.json';

        $vars = json_encode(array("to" => array($email),
                "sub" => array("%name%" => array($username), "%pic_name%" => array($picname),)
            )
        );

        $API_USER = 'snsume';
        $API_KEY = 'Q9vNQ9dQwWQTWkEb';
        $param = array(
            'api_user' => $API_USER, # 使用api_user和api_key进行验证
            'api_key' => $API_KEY,
            'from' => 'postmaster@snsu.me', # 发信人，用正确邮件地址替代
            'fromname' => '向素团队',
            'substitution_vars' => $vars,
            'template_invoke_name' => 'delete_pic',
            'subject' => '图片删除通知-来自向素的邮件',
            'resp_email_id' => 'true'
        );


        $data = http_build_query($param);

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $data
            ));
        $context = stream_context_create($options);
        $result = file_get_contents($url, FILE_TEXT, $context);
        dump($result);
//        $this->ajaxReturn($result, 'JSON');
    }

}
