<?php
/*
 *create by roy
 */
namespace home\Controller;
use Think\Controller;
use home\Common\HomeController;
class CaseController extends HomeController {
	public function group($id=0){
		$caseclass_model = D("Caseclass");
		$case_model = D("Case");
		$caseclass = $caseclass_model->where("c_id <> 1")->select();
		$case = $case_model->where("c_id <> 1")->join("LEFT JOIN admin_image ON admin_case.cs_cover = admin_image.img_id")->select();
		foreach($case as $value){
			$new_case[0][] = $value;
			$new_case[$value["c_id"]][] = $value;
		}
		$case = array();
		foreach($new_case as $key=>$value){
			for($i=0;$i<ceil(count($value)/3);$i++){
				$case[$key][] = array_slice($value, $i * 3 ,3);
			}
		}
		foreach($caseclass as $value){
			if(!isset($case[$value["c_id"]])){
				$case[$value["c_id"]] = array();
			}
		}
		//var_dump($case);
		$this->assign('caseclass', $caseclass);
		$this->assign('case', $case);
		$this->assign('id', $id);
		$this->assign('cur', 2); //当前高亮导航
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
