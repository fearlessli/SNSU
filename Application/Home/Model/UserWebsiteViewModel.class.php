<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Home\Model;

use Think\Model;
use Think\Model\ViewModel;

/**
 * 文档基础模型
 */
class UserWebsiteViewModel extends ViewModel
{

    public $viewFields = array(
        'UserWebsite' => array('id', 'uid', 'wid'),
        'Website' => array('id' => 'wid', 'name', 'snapshot', 'domain', 'password', 'templete_id', 'status', 'visit_num', 'logo_path', 'title', 'sub_title', 'background', 'fontsize', 'preview', '_on' => 'UserWebsite.wid=Website.id'),

    );

}

