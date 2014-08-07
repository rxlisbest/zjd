<?php
/*
 *create by roy
 */
namespace admin\Controller;
use admin\Common\YuController;
class AboutController extends YuController {

	public function About($a_id=0){
		$AboutModel = D("About");
		$About = $AboutModel->where("a_id=".$a_id)->find();
		$this->assign('AboutSaveUrl',U('AboutSave', '', ''));
		$this->assign('About',$About);

		$this->display();
	}

	public function AboutSave(){
		$post = $_POST;
		if($post){
			$a_id = $post["a_id"];
			unset($post["submit"]);
			unset($post["a_id"]);
			$AboutModel = D("About");
			if($id=$AboutModel->where("a_id = ".$a_id)->save($post)){
				$type = "success";
				$infomation = "修改成功!";
			}
			else{
				$type = "error";
				$infomation = "修改失败!";
			}
			$json["url"] = U('About/a_id/1', '', '');

			$json["info"] = $this->getInfomation($type, $infomation);
			echo $this->ajaxReturn($json);
		}
	}
}
