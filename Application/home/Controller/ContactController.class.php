<?php
/*
 *create by roy
 */
namespace home\Controller;
use Think\Controller;
use home\Common\HomeController;
class ContactController extends HomeController {
	public function index(){
		$ContactModel = D("Contact");
		$Contact = $ContactModel->where('con_id = 1')->find();
		$this->assign('contact',$Contact);
		$this->assign('cur', 6); //当前高亮导航

		$this->display("contact");
	}
}
