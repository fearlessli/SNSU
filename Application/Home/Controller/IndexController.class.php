<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
        //$this->createVerify();
        //dump($_SESSION['d2d977c58444271d9c780187e93f80e5']['verity_code']);
        if (session('user_id')) {
            $this->redirect('User/index');
        } else {
            $this->display();
        }

    }
}