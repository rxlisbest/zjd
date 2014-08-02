<?php
/*
 *create by roy
 */
namespace home\Controller;
use Think\Controller;
use home\Common\HomeController;
class IndexController extends HomeController {
	public function index(){
		$flash_model = D("Flash");
		$flash = $flash_model->order("f_sort ASC")->select();
		//var_dump($flash);exit;

		$this->assign('flash', $flash); //当前高亮导航
		$this->assign('cur', 0); //当前高亮导航
		$this->display("index");
	}
}
