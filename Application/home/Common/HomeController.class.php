<?php
namespace home\Common;

use Think\Controller;

class HomeController extends Controller
{
	function _initialize(){
		$caseclass_model = D("Caseclass");
		$caseclass = $caseclass_model->where("c_id <> 1")->select();
		$this->assign('caseclass',$caseclass);
	}
}
