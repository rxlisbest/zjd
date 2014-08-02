<?php
/*
 *create by roy
 */
namespace home\Controller;
use Think\Controller;
use home\Common\HomeController;
class CultureController extends HomeController {
	public function index(){
		$PageModel = D("Page");
		$page = $PageModel->where("pc_id = 1")->select();

		$this->assign('page',$page);

		$this->assign('cur', 5); //当前高亮导航
		$this->display("culture");
	}
	
	public function detail($id=0){
		$PageModel = D("Page");
		$page = $PageModel->where('p_id='.$id)->find();
		$this->assign('page',$page);

		$this->assign('cur', 5); //当前高亮导航
		$this->display("culture_detail");
	}
}
