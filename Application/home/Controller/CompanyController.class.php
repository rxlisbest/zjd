<?php
/*
 *create by roy
 */
namespace home\Controller;
use Think\Controller;
use home\Common\HomeController;
class CompanyController extends HomeController {
	public function about($id=0){
		$this->assign('cur', 1); //当前高亮导航
		$this->display("company_intro");
	}
		
	public function designer($id=0){
		$this->assign('cur', 1); //当前高亮导航
		$this->display("designer_intro");
	}
		
	public function service($id=0){
		$this->assign('cur', 1); //当前高亮导航
		$this->display("service");
	}
}
