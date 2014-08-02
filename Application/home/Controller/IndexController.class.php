<?php
/*
 *create by roy
 */
namespace home\Controller;
use Think\Controller;
use home\Common\HomeController;
class IndexController extends HomeController {
	public function index(){
		$this->assign('cur', 0); //当前高亮导航
		$this->display("index");
	}
}
