<?php
namespace Home\Model;

use Think\Model;
use Think\Model\ViewModel;

/**
* Admin info
*/
class  UserWebsiteRelationViewModel extends ViewModel
{
    public $viewFields = array(
        'User' => array('id', 'email', 'password', 'name', 'status', 'last_login_time', 'last_login_ip', 'avator', 'secret'),
        'UserWebsite' => array('id' => 'uwid', 'uid', 'wid', '_on' => 'User.id = UserWebsite.uid'),
        'Website' => array('id' => 'wid', 'name' => 'wname', 'domain', 'password' => 'wpassword', 'templete_id', 'status' => 'wstatus', 'visit_num', 'logo_path', 'title', 'sub_title', 'background', 'fontsize', 'preview', 'secret' => 'wsecret', '_on' => 'Website.id=UserWebsite.wid')


    );
}