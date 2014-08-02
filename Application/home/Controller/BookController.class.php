<?php
/*
 *create by roy
 */
namespace home\Controller;
use Think\Controller;
use home\Common\HomeController;
class BookController extends HomeController {
	public function index($id=0){
		$case_model = D("Case");
		$case = $case_model->where("c_id=1")->join("LEFT JOIN admin_image ON admin_case.cs_cover = admin_image.img_id")->select();
		for($i=0;$i<ceil(count($case)/2);$i++){
			$new_case[] = array_slice($case, $i * 2 ,2);
		}
		//var_dump($case);
		$this->assign('case', $new_case);
		$this->assign('id', $id);
		$this->assign('cur', 3); //当前高亮导航
		$this->display("case_group");
	}
		
	public function detail($id=0){
		$case_model = D("Case");
		$image_model = D("Image");
		$case = $case_model->where("cs_id = ".$id)->find();
		$images = $image_model->where("img_id in (".$case["cs_image"].")")->select();
		$this->assign('images', $images);
		$this->assign('case', $case);
		$this->assign('cur', 2); //当前高亮导航
		$this->display("case_detail");
	}
}
