<?php
namespace admin\Controller;

use admin\Model\DesignerModel;
use admin\Common\YuController;

class DesignerController extends YuController {

	public function _initialize()
	{
		$this->Designer = new DesignerModel();
	}

	public function DesignerList($page=1)
	{
		$this->assign('DesignerAddUrl',U('DesignerAdd','',''));
		$this->assign('DesignerEditUrl',U('DesignerEdit','',''));
		$this->assign('DesignerDelUrl',U('DesignerDel','',''));

		$url = U('DesignerList',array('page'=>$page),'');

		$rowNum = $this->Designer->count();
		$pageSize = 15;
		$pages = ceil($rowNum/$pageSize);
		if($page > $pages)
			$page = $pages;
		if($page < 1)
			$page = 1;
		$curDesigner = $page ?: 1;
		$offset = ($curDesigner-1)*$pageSize;
		$DesignerLists= $this->Designer->order("d_id")->limit($offset, $pageSize)->select();

		$blank = $pageSize - count($DesignerLists);
		$this->assign('blank',$blank);
		$pagination = $this->getPagination($curDesigner, $pages, $url);

		$this->assign('pagination',$pagination);

		$this->assign('DesignerLists', $DesignerLists);
		$this->assign('batchUrl',U('page_batch',array('page'=>$page),''));
		$this->display();
	}

	public function DesignerAdd()
	{
		$this->assign('DesignerSaveUrl',U('DesignerSave','',''));
		$this->assign('DesignerLists',$this->Designer->select());
		$this->display("DesignerAdd");
	}

	public function DesignerEdit($id="")
	{
		$this->assign('DesignerSaveUrl',U('DesignerSave'));
		$this->assign('DesignerInfo',$this->Designer->find($id));
		$content = $this->fetch("DesignerAdd");
		$this->show($content);
	}

	public function DesignerDel($id)
	{
		if ($this->Designer->delete($id)) {
			$type = "success";
			$infomation = "删除成功!";
		}else{
			$type = "error";
			$infomation = "删除失败!";
		}
		$json["info"] = $this->getInfomation($type,$infomation);
		$json["url"]  = U('DesignerList');
		echo $this->ajaxReturn($json);
	}

	public function page_batch($page=1){
		$post = $_POST;
		if($post["choose"]=='delete'){
			unset($post["choose"]);
			foreach($post as $key=>$value){
				$d_ids[] = $key;
			}
			$delete_num = count($d_ids);
			if($this->Designer->where("d_id in (".implode(",",$d_ids).")")->delete()){
				$type = "success";
				$infomation = "删除成功".$delete_num."条!";
			}
			else{
				$type = "error";
				$infomation = "删除失败!";
			}
			$json["info"] = $this->getInfomation($type, $infomation);
			$json["url"] = U('DesignerList',array('page'=>$page),'');
			echo json_encode($json);
		}
	}

	public function DesignerSave()
	{
		if ($_POST) {
			$d_id = I('post.d_id');
			if($d_id){
				$data = $_POST;
				unset($data["d_id"]);
				if ($this->Designer->where("d_id=".$d_id)->save($data)){
					$type = "success";
					$infomation = "操作成功";
				}else{
					$type = "error";
					$infomation = "操作失败!";
				}
			}
			else{
				$data = $_POST;
				unset($data["d_id"]);

				if ($this->Designer->add($data,'',TRUE)){
					$type = "success";
					$infomation = "操作成功";
				}else{
					$type = "error";
					$infomation = "操作失败!";
				}
			}
			$json["info"] = $this->getInfomation($type,$infomation);
			$json["url"]  = U('DesignerList');
			echo $this->ajaxReturn($json);
		}
	}

	public function upload(){
		$time = date("YmdHms");
		$file = $_FILES["image"];
		$path = "./Public/uploads/images/designer/";
		if(!file_exists($path)){
			mkdir($path);
			chmod($path,0777);
		}
		$types = array("image/gif","image/pjpeg","image/jpeg","image/png");
		if(!in_array($file["type"],$types)){
			$type = "error";
			$infomation = "图片类型不正确!";
			$json["info"] = $this->getInfomation($type, $infomation);
			return json_encode($json);
		}
		$types = array("image/gif"=>".gif","image/pjpeg"=>".pjpeg","image/jpeg"=>".jpeg","image/png"=>".png"); 
		$image_type = $types[$file["type"]];
		$image_name = $path.$time.$image_type;
		move_uploaded_file($file["tmp_name"],$image_name);
		$image_url = str_replace("./","/",$image_name);
		echo '{error:"",msg:"'.$image_url.'"}';
	}
}
