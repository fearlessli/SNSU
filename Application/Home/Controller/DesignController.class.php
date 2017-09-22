<?php
namespace Home\Controller;
use Think\Controller;

//注：edit开头的控制器可增可改，功能合二为一
class DesignController extends CommonController {
    public function _initialize()
    {
        if (!$_SESSION['user_id']) {
            //$this->error("请先登录！", '/Home/Index/index');
        }

        $test = 1;
    }


    public function index()
    {

        $wid = I('wid');
        $m = M('UserWebsite');
        $map['wid'] = $wid;
        $map['uid'] = $_SESSION['user_id'];

        if ($m->where($map)->find()) {
            if ($wid) {
                session('cur_wid', $wid);
            }

            $this->display();

        } else {
            $this->error('操作非法');
        }



    }

    public function designHeader()
    {
        //dump($wid);

        $this->display();
    }

    public function designIndex()

    {   
        //首页图片
        $c_map['wid'] = $_SESSION['cur_wid'];
        $p = M('IndexPic');
        $list = $p->where($c_map)->order('id desc')->select();
        $this->assign('list', $list);
        //首页导航栏
        $c = M('Column');
        $column = $c->where($c_map)->select();
        $this->assign('column', $column);
        $this->display();

    }


    public function designIndexDesign()
    {

        //首页图片
        $c_map['wid'] = $_SESSION['cur_wid'];
        $p = M('IndexPic');
        $list = $p->where($c_map)->order('id desc')->select();
        $this->assign('list', $list);
        //首页导航栏
        $c = M('Column');
        $column = $c->where($c_map)->select();
        $this->assign('column', $column);
        $this->display();
    }

    public function designGallery()
    {
        //首页导航栏
        $c_map['wid'] = $_SESSION['cur_wid'];
        $c = M('Column');
        $column = $c->where($c_map)->select();
        $this->assign('column', $column);

        $map['gid'] = I('cid');
        $m = M('pic');
        $list = $m->where($map)->order('id desc')->select();
        $this->assign('list', $list);
        $this->display();
    }

    public function designNavGallery()
    {
        $map['gid'] = I('cid');
        $m = M('pic');
        $list = $m->where($map)->order('id desc')->select();
        $this->assign('list', $list);
        $this->display();
    }

    public function designNav()
    {
        $map['wid'] = $_SESSION['cur_wid'];
        $map['status'] = 1;
        $m = M('Column');
        $list = $m->where($map)->select();
        $this->assign('column', $list);
        $this->display();
    }


    public function designIndexData()
    {   
        //首页图片
        $c_map['wid'] = $_SESSION['cur_wid'];
        $p = M('IndexPic');
        $list = $p->where($c_map)->order('id desc')->select();
        $this->assign('list', $list);
        //首页导航栏
        $c = M('Column');
        $column = $c->where($c_map)->select();
        $this->assign('column', $column);

        $map['id'] = $_SESSION['cur_wid'];
        $m = M('website');
        $total_count = $m->where($map)->getField('visit_num');
        $this->assign('total_count', $total_count);

        $visit_log = M('visit_log');
        $map2['wid'] = $_SESSION['cur_wid'];
        $map2['time'] = date("Y-m-d");
        $today_count = $visit_log->where($map2)->getField('number');
        $this->assign('today_count', $today_count);


        $this->display();
    }



    public function designIndexSetting()
    {   
        //首页图片
        $c_map['wid'] = $_SESSION['cur_wid'];
        $p = M('IndexPic');
        $list = $p->where($c_map)->order('id desc')->select();
        $this->assign('list', $list);
        //首页导航栏
        $c = M('Column');
        $column = $c->where($c_map)->select();
        $this->assign('column', $column);

        $map['id'] = $_SESSION['cur_wid'];
        $m = M('Website');
        $password = $m->where($map)->getField('password');
        $this->assign('password', $password);

        $domain = $m->where($map)->getField('domain');
        $this->assign('domain', $domain);

        $this->display();
    }

    //新建站点
    public function addNewSite()
    {
        $website_m = M('Website');
        $website_data['domain'] = I('domain');
        //正则判断域名是否合法
        if (preg_match('/^[0-9a-zA-Z]{2,14}$/', $website_data['domain'])) {
            //查重
            $map['domain'] = I('domain');
            if ($website_m->where($map)->find()) {
                $this->ajaxReturn(2);//重复
            } else {
                $website_data['name'] = I('name');
                $website_data['templete_id'] = I('templete_id');
                //写入站点详细信息表
                if ($wid = $website_m->add($website_data)) {
                    //创建默认栏目 画廊 和 博客
                    $column_m = M('Column');
                    $column_data['name'] = "画廊";
                    $column_data['uid'] = $_SESSION['user_id'];
                    $column_data['type'] = 1;
                    $column_data['visible'] = 1;
                    $column_data['default'] = 1;
                    $column_data['wid'] = $wid;
                    $column_data['time'] = time();

                    $column_data2['name'] = "博客";
                    $column_data2['uid'] = $_SESSION['user_id'];
                    $column_data2['type'] = 2;
                    $column_data2['visible'] = 1;
                    $column_data2['default'] = 1;
                    $column_data2['wid'] = $wid;
                    $column_data2['time'] = time();
                    //写入中间表
                    $user_site_m = M('user_website');
                    $user_site_data['wid'] = $wid;
                    $user_site_data['uid'] = $_SESSION['user_id'];
                    $user_site_m->add($user_site_data);

                    if ($column_m->add($column_data) && $column_m->add($column_data2)) {
                        $this->ajaxReturn(1);//新加站点成功
                    }
                } else {
                    $this->ajaxReturn(0);//失败
                }
            }
        } else {
            $this->ajaxReturn(3);//域名不合法
        }
    }

    //删除站点
    public function deleteSite()
    {
        $m = M('User');
        $map['id'] = $_SESSION['user_id'];
        $password = I('password');

        $m2 = M('Website');
        $map2['id'] = I('wid');

        $m3 = M('UserWebsite');
        $map3['wid'] = I('wid');

        if ($m->where($map)->getField('password') == md5(md5('fuck' . $password))) {
            //删除操作
            $m2->where($map2)->delete();
            $m3->where($map3)->delete();
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }

    }

    //增加栏目 type 1 画廊 2 博客
    public function addColumn()
    {
        $column_m = M('Column');
        $data['type'] = I('type');
        $data['name'] = "未命名";
        $data['time'] = time();
        $data['wid'] = $_SESSION['cur_wid'];
        $data['uid'] = $_SESSION['user_id'];
/*        if ($data['type'] == 2) {//如果是博客的话，接收封面数据
            $data['cover'] = I('cover');
        }*/
        $data['default'] = 0;
        if ($column_m->add($data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //ajax获取当前网站的所有栏目
    public function ajaxGetAllColumn()
    {
        $column_m = M('Column');
        $map['wid'] = $_SESSION['cur_wid'];
        $list = $column_m->where($map)->select();
        $this->ajaxReturn($list, 'JSON');
    }

    //===============照片相关===============

    //首页增加BANNER图片
    public function addIndexPic()
    {
        $m = M('IndexPic');
        $data['path'] = I('path');
        $data['wid'] = $_SESSION['cur_wid'];
        $data['time'] = time();
        $data['name'] = I('name');
        $data['description'] = I('description');
        $data['link'] = I('link');
        $map['wid'] = $data['wid'];
        $website_map['id'] = $data['wid'];
        //判断当前张数
        if ($m->where($map)->count() >= 6) {
            $this->ajaxReturn(2);//超过了六张
        } else {
            if ($m->add($data)) {

                $info['status'] = 1;
                $info['latest_banner'] = $data['path'];
                $info['site_info']['name'] = M('website')->where($website_map)->getField('name');
                $info['site_info']['des'] = M('website')->where($website_map)->getField('sub_title');
                $map2['wid'] = $_SESSION['cur_wid'];
                $map2['visible'] = 1;
                $map2['status'] = 1;
                $info['nav'] = M('Column')->where($map2)->field('name')->select();
                $this->ajaxReturn($info);
//                $this->ajaxReturn(1);//增加成功
            } else {
                $this->ajaxReturn(0);//增加失败
            }
        }

    }

    //画廊上传图片
    public function addGalleryPic()
    {
        $data['gid'] = I('gid');//画廊的id
        $data['path'] = I('path');//图片路径
        $data['name'] = I('name');
        $data['description'] = I('description');//描述
        $data['link'] = I('link');//跳转链接
        $data['time'] = time();
        $pic_m = M('pic');
        if ($pic_m->add($data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //画廊图片修改
    public function editGalleryPic()
    {
        $data['id'] = I('pid');//图片的id
        $data['name'] = I('name');//图片的名称
        $data['description'] = I('description');//描述
        $data['link'] = I('link');//跳转链接
        $data['time'] = time();
        $pic_m = M('pic');
        $pic_map['id'] = I('pid');
        if ($pic_m->where($pic_map)->save($data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //删除单个照片
    public function deleteOnePic()
    {
        $map['id'] = I('pid');
        $m = M('pic');
        if ($m->where($map)->delete()) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //删除首页单个照片
    public function deleteIndexOnePic()
    {
        $map['id'] = I('pid');
        $m = M('index_pic');
        if ($m->where($map)->delete()) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //遍历首页照片
    public function designNavIndex()
    {
        $m = M('IndexPic');
        $map['wid'] = $_SESSION['cur_wid'];
        $list = $m->where($map)->order('id desc')->select();
        $this->assign('list', $list);
        $this->display();
    }
    //遍历网站首页里面的某个图片的信息
    public function getOneIndexPicInfo()
    {
        $pic_m = M('IndexPic');
        $pic_map['id'] = I('id');//pic 数据库中的id
        $info = $pic_m->where($pic_map)->select();
        $this->ajaxReturn($info, 'JSON');
    }

    //首页增加&修改内容
    public function addIndexItem()
    {
        $pic_m = M('IndexPic');
        $pic_map['id'] = I('pid');
        //$map['wid'] = $_SESSION['cur_wid'];
        $data['id'] = I('pid');//图片的id
        $data['name'] = I('name');//图片的名称
        $data['description'] = I('description');//描述
        $data['link'] = I('link');//跳转链接
        $data['time'] = time();
        if ($pic_m->where($pic_map)->save($data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //ajax获取首页的照片
    public function ajaxGetIndexPic()
    {
        $m = M('IndexPic');
        $map['wid'] = $_SESSION['cur_wid'];
        $list = $m->where($map)->select();
        $this->ajaxReturn($list, 'JSON');
    }

    public function ajaxGetNewestIndexPic()
    {
        $m = M('IndexPic');
        $map['wid'] = $_SESSION['cur_wid'];
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



    //============博客相关=======================
    //增加一篇博客
    public function addBlog()
    {
        $blog_m = M('Blog');
        $data['uid'] = $_SESSION['user_id'];
        $data['title'] = I('title');
        $data['sub_title'] = I('sub_title');
        $data['content'] = I('content');
        $data['column_id'] = I('column_id');
        $data['cover'] = I('cover');
        $data['time'] = time();
        if ($blog_m->add($data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }
    //编辑一篇博客
    public function editBlog()
    {
        $blog_m = M('Blog');
        $blog_map['id'] = I('bid');
        $data['uid'] = $_SESSION['user_id'];
        $data['title'] = I('title');
        $data['sub_title'] = I('sub_title');
        $data['content'] = I('content');
        $data['column_id'] = I('column_id');
        $data['cover'] = I('cover');
        $data['time'] = time();
        if ($blog_m->where($blog_map)->save($data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }


    //删除一篇博客
    public function deleteOneBlog()
    {
        $m = M('blog');
        $map['id'] = I('id');//博客的id
        if ($m->where($map)->delete()) {
            $this->ajaxReturn(1);//删除成功
        } else {
            $this->ajaxReturn(0);
        }

    }
    //添加博客封面
    public function editBlogCover()
    {
        $blog_m = M('Blog');
        $blog_map['id'] = I('bid');
        $data['cover'] = I('path');
        $data['time'] = time();
        if ($blog_m->where($blog_map)->save($data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //获取某个博客栏目的里面的所有的博客
   public function designBlog()
    {   
        //首页导航栏
        $c_map['wid'] = $_SESSION['cur_wid'];
        $c = M('Column');
        $column = $c->where($c_map)->select();
        $this->assign('column', $column);
        
        $blog_m = M('Blog');
        $blog_map['column_id'] = I('cid');//栏目id
        $list = $blog_m->where($blog_map)->order('id desc')->select();
        $list_length = count($list);
        for ($i = 0; $i < $list_length; $i++) {
            $list[$i]['content'] = htmlspecialchars_decode($list[$i]['content']);
            $list[$i]['sub_title'] = htmlspecialchars_decode($list[$i]['sub_title']);

        }

        $this->assign('list', $list);
        $this->display();
    }

    //获取某个博客栏目的最新的文章
    public function getLatestBlog()
    {
        $m = M('blog');
        $map['column_id'] = I('cid');//栏目的id
        $blog = $m->where($map)->order('id desc')->limit(1)->select();
        $blog[0]['content'] = htmlspecialchars_decode($blog[0]['content']);
        $blog[0]['sub_title'] = htmlspecialchars_decode($blog[0]['sub_title']);
        $this->ajaxReturn($blog, 'JSON');
    }


    public function ajaxGetOneBlog()
    {
        $blog_m = M('Blog');
        //$blog_map['uid'] = $_SESSION['uid'];
        $blog_map['column_id'] = I('column_id');
				$column_id=I('column_id');
        $blog_map['id'] = I('id');
        $blog_map['btn'] = I('btn');

        //$blog_map2['uid'] = $_SESSION['uid'];
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
        $info[0]['content'] = htmlspecialchars_decode($info[0]['content']);
        $info[0]['sub_title'] = htmlspecialchars_decode($info[0]['sub_title']);

        $this->ajaxReturn($info, 'JSON');


//        $list = $blog_m->where($blog_map)->select();
//        $this->assign('list', $list);

    }

    //网站设计部分
    //网站logo
    public function editLogo()
    {
        $website_m = M('website');
        $website_map['id'] = $_SESSION['cur_wid'];
        $website_data['logo_path'] = I('logo_path');
        if ($website_m->where($website_map)->save($website_data)) {
//            $this->ajaxReturn(1);
            $info['status'] = 1;
            $info['site_logo'] = $website_data['logo_path'];
            $info['site_info']['name'] = $website_m->where($website_map)->getField('name');
            $info['site_info']['des'] = $website_m->where($website_map)->getField('sub_title');
            $map2['wid'] = $_SESSION['cur_wid'];
            $map2['visible'] = 1;
            $map2['status'] = 1;
            $info['nav'] = M('Column')->where($map2)->field('name')->select();
            $this->ajaxReturn($info);
        } else {
            $this->ajaxReturn(0);
        }

    }

    //修改一级标题
    public function editTitle()
    {

        $website_m = M('website');
        $website_map['id'] = $_SESSION['cur_wid'];
        $website_data['name'] = I('title');
        if ($website_m->where($website_map)->save($website_data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //修改二级标题
    public function editSubTitle()
    {
        $website_m = M('website');
        $website_map['id'] = $_SESSION['cur_wid'];
        $website_data['sub_title'] = I('sub_title');
        if ($website_m->where($website_map)->save($website_data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //修改背景颜色
    public function editBackground()
    {
        $website_m = M('website');
        $website_map['id'] = $_SESSION['cur_wid'];
        $website_data['background'] = I('background');
        if ($website_m->where($website_map)->save($website_data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //修改字体大小
    public function editFontSize()
    {
        $website_m = M('website');
        $website_map['id'] = $_SESSION['cur_wid'];
        $website_data['fontsize'] = I('fontsize');
        if ($website_m->where($website_map)->save($website_data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //修改文字间距
    public function editFontSpacing()
    {
        $website_m = M('website');
        $website_map['id'] = $_SESSION['cur_wid'];
        $website_data['fontspacing'] = I('fontspacing');
        if ($website_m->where($website_map)->save($website_data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //获取当前网站信息
    public function getCurWebsiteInfo()
    {
        $map['id'] = $_SESSION['cur_wid'];
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

    //获取某个画廊栏目的属性
    public function ajaxGetOneColumnInfo()
    {
        $column = M('Column');
        $column_map['id'] = I('gid');
        $info = $column->where($column_map)->select();
        $this->ajaxReturn($info, 'JSON');

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

    //ajax遍历某个画廊里面的所有图片信息
    public function ajaxGetGalleryList()
    {

        $pic_m = M('pic');
        $pic_map['gid'] = I('gid');//所属画廊的id
        //合法性检测
        $gallery_map['uid'] = $_SESSION['uid'];
        $gallery_map['gid'] = I('gid');
        if (M('Gallery')->where($gallery_map)->find()) {
            $list = $pic_m->where($pic_map)->order('id desc')->select();
            $this->ajaxReturn($list, 'JSON');
        } else {
            $this->ajaxReturn('操作非法', 'JSON');
        }

    }
	

    //遍历某个画廊里面的某个图片的信息
    public function getOneGalleryInfo()
    {
        $pic_m = M('Pic');
        $pic_map['id'] = I('id');//pic 数据库中的id
        $info = $pic_m->where($pic_map)->select();
        $this->ajaxReturn($info, 'JSON');
    }


    //发布网站--绑定域名
    public function bindingDomain()
    {

        $data['domain'] = I('domain');//域名
        $m = M('Website');
        $map['domain'] = I('domain');
        $map2['id'] = I('wid');//网站的id
        //查重
        if ($m->where($map)->find()) {
            $this->ajaxReturn(2);//2表示域名已被占用
        } else {
            if ($m->where($map2)->save($data)) {
                $this->ajaxReturn(1);//成功
            } else {
                $this->ajaxReturn(0);//失败
            }
        }


    }

    //设置
    //安全密码
    public function setWebsitePassword()
    {
        $map['id'] = $_SESSION['cur_wid'];
        $data['password'] = I('password');
        $m = M('Website');
        if ($m->where($map)->save($data)) {
            $this->ajaxReturn(1);//设置成功
        } else {
            $this->ajaxReturn(0);//设置失败
        }
    }

    public function checkDomain($domain)
    {
        $map['domain'] = $domain;
        $m = M('website');
        if ($m->where($map)->find()) {
            return 1;
        } else {
            return 0;
        }
    }

    //设置域名
    public function setDomain()
    {
        $map['id'] = $_SESSION['cur_wid'];
        $data['domain'] = I('domain');
        $m = M('Website');

        if (preg_match('/^[0-9a-zA-Z]{2,14}$/', $data['domain'])) {
            if ($this->checkDomain($data['domain']) == 1) {
                $this->ajaxReturn(2);//有重复
            } else {
                if ($m->where($map)->save($data)) {
                    $this->ajaxReturn(1);//设置成功
                } else {
                    $this->ajaxReturn(0);//设置失败
                }
            }
        } else {
            $this->ajaxReturn(3);//域名不符合规则
        }


    }
    public function ajaxGetOneColumn()
    {
        $cid = I('cid');
        $m = M('column');
        $map['id'] = $cid;
        $list = $m->where($map)->select();
        $this->ajaxReturn($list, 'JSON');
    }
    //设置是否可见
    public function setVisible()
    {
        $cid = I('cid');
        $m = M('column');
        $map['id'] = $cid;
        $cur_visible = $m->where($map)->getField('visible');
        if ($cur_visible == 1) {
            $data['visible'] = 0;
        } else {
            $data['visible'] = 1;
        }
        if ($m->where($map)->save($data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //设置当前画廊里面的图片的名称是否可见
    public function setPicNameVisible()
    {
        $m = M('column');
        $map['id'] = I('cid');//栏目的id
        $cur_visible = $m->where($map)->getField('pic_name_visible');
        if ($cur_visible == 1) {
            $data['pic_name_visible'] = 0;
        } else {
            $data['pic_name_visible'] = 1;
        }
        if ($m->where($map)->save($data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //设置当前画廊里面的图片的描述是否可见
    public function setPicDesVisible()
    {
        $m = M('column');
        $map['id'] = I('cid');//栏目的id
        $cur_visible = $m->where($map)->getField('pic_des_visible');
        if ($cur_visible == 1) {
            $data['pic_des_visible'] = 0;
        } else {
            $data['pic_des_visible'] = 1;
        }
        if ($m->where($map)->save($data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }


    //删除画廊
    public function deleteGallery()
    {
        $m = M('Column');
        $map['id'] = I('cid');
        $data['status'] = 0;
        if ($m->where($map)->save($data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //修改栏目名称
    public function setColumnName()
    {
        $m = M('Column');
        $map['id'] = I('cid');
        $data['name'] = I('name');
        if ($m->where($map)->save($data)) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }


    function  reset_email()
    {
        $user = M('User');



        $url = 'http://sendcloud.sohu.com/webapi/mail.send.json';
        $API_USER = 'snsume';
        $API_KEY = 'Q9vNQ9dQwWQTWkEb';

        //不同于登录SendCloud站点的帐号，您需要登录后台创建发信子帐号，使用子帐号和密码才可以进行邮件的发送。
        $param = array(
            'api_user' => $API_USER,
            'api_key' => $API_KEY,
            'from' => 'postmaster@snsu.me',
            'fromname' => '重置密码',
            'to' => '397484689@qq.com',
            'subject' => '重置密码！',
            'html' => '你太棒了！你已成功的从SendCloud发送了一封测试邮件，接下来快登录前台去完善账户信息吧！<a href="http://w.snsu.me/User/resetPasswordIndex/secret/">测试</a>',
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

    //获取访问密码的方法
    public function getAccessPassword()
    {
        $map['id'] = $_SESSION['cur_wid'];
        $password = M('website')->where($map)->getField('password');
        $this->ajaxReturn($password, 'JSON');

    }

    //保存快照
    public function saveSnapshot()
    {
        $m = M('website');
        $binary_img = I('snapshot');//图片的二进制
        $file_name = md5($binary_img);//md5产生文件名

        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $binary_img, $result)) {
            $type = $result[2];
            $new_file = "/home/wwwroot/lamp53/domain/xiangsu.ujn.me/web/Public/Snapshot/" . $file_name . '.' . $type;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $binary_img)))) {
                $map['id'] = $_SESSION['cur_wid'];//网站的id
                $data['snapshot'] = '/Public/Snapshot/' . $file_name . '.' . $type;//快照的路径
//                $data['snapshots'] = I('snapshot');
                if ($m->where($map)->save($data)) {
                    $this->ajaxReturn(1);//更新快照成功
                } else {
                    $this->ajaxReturn(0);//更新快照失败
                }

            }
        }

//        $newFilePath = '/home/wwwroot/lamp53/domain/xiangsu.ujn.me/web/Public/Snapshot/' . $file_name . '.png';
//        $newFile = fopen($newFilePath, "w");//打开文件准备写入
//        fwrite($newFile, $binary_img);//写入二进制流到文件
//        fclose($newFile);//关闭文件
//        $map['id'] = $_SESSION['cur_wid'];//网站的id
//        $data['snapshot'] = '/Public/Snapshot/' . $file_name . '.png';//快照的路径
//        $data['snapshots'] = I('snapshot');
//        if ($m->where($map)->save($data)) {
//            $this->ajaxReturn(1);//更新快照成功
//        } else {
//            $this->ajaxReturn(0);//更新快照失败
//        }
    }






}