<?php
/*
 *create by roy
 */
namespace admin\Controller;
use admin\Common\YuController;
class ContactController extends YuController {

	public function Contact($con_id=0){
		$ContactModel = D("Contact");
		$Contact = $ContactModel->where("con_id=".$con_id)->find();
		$this->assign('ContactSaveUrl',U('ContactSave', '', ''));
		$this->assign('Contact',$Contact);

		$this->display("contact_list");
	}

	public function ContactSave(){
		$post = $_POST;
		if($post){
			$con_id = $post["con_id"];
			unset($post["submit"]);
			unset($post["con_id"]);
			$ContactModel = D("Contact");
			if($id=$ContactModel->where("con_id = ".$con_id)->save($post)){
				$type = "success";
				$infomation = "修改成功!";
			}
			else{
				$type = "error";
				$infomation = "修改失败!";
			}
			$json["url"] = U('Contact/con_id/1', '', '');

			$json["info"] = $this->getInfomation($type, $infomation);
			echo $this->ajaxReturn($json);
		}
	}
}
