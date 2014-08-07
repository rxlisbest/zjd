<?php
/*
 *create by roy
 */
namespace home\Controller;
use Think\Controller;
use home\Common\HomeController;
use admin\Model\AboutModel;
use admin\Model\DesignerModel;
class CompanyController extends HomeController {
	public function about($id=0){
		$AboutModel = new AboutModel();
		$about = $AboutModel->find();
		$this->assign('about', $about); //当前高亮导航
		$this->assign('cur', 1); //当前高亮导航
		$this->display("company_intro");
	}
		
	public function designer($id=0){
		$DesignerModel = new DesignerModel();
		$designer = $DesignerModel->order("d_sort ASC")->select();
		$this->assign('designer', $designer); //当前高亮导航
		$this->assign('cur', 1); //当前高亮导航
		$this->display("designer_intro");
	}
		
	public function service($id=0){
		$this->assign('cur', 1); //当前高亮导航
		$this->display("service");
	}
}
