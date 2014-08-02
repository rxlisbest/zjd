<?php
/*
 *create by roy
 */
namespace admin\Controller;
use admin\Common\YuController;
class FlashController extends YuController {

	public function flashlist($page=1, $f_id=0){
		$flash_model = D("Flash");
		$url = U('Flash/flashlist',array('page'=>$page),'');

		$rowNum = $flash_model->count();
		$pageSize = 15;
		$pages = ceil($rowNum/$pageSize);
		if($page > $pages)
			$page = $pages;
		if($page < 1)
			$page = 1;
		$curPage = $page ?: 1;
		$offset = ($curPage-1)*$pageSize;
		$flash_list = $flash_model->order("f_sort")->limit($offset, $pageSize)->select();
		$pagination = $this->getPagination($curPage, $pages, $url);
		$this->assign('page',$page);
		$this->assign('flashclass',$flashclass);
		if($f_id){
			$flash = $flash_model->where("f_id = ".$f_id)->find();
			$this->assign('flash',$flash);
		}
		$this->assign('flash_list',$flash_list);
		$this->assign('pagination',$pagination);

		$this->assign('flashlistUrl',U('Flash/flashlist',array('page'=>$page),''));
		$this->assign('flash_saveUrl',U('Flash/flash_save',array('page'=>$page),''));
		$this->assign('flash_deleteUrl',U('Flash/flash_delete',array('page'=>$page),''));
		$this->assign('flash_batchUrl',U('Flash/flash_batch',array('page'=>$page),''));
		$content = $this->fetch("flash_list");
		$this->show($content);
		//$this->display("login");
	}

	public function flash_save($page=1){
		$post = $_POST;
		$old_img = $post["f_img_old"];
		unset($post["f_img_old"]);
		if($post["f_img"]==""){
			$type = "error";
			$infomation = "图片没上传，添加失败!";
			$json["info"] = $this->getInfomation($type, $infomation);
			echo json_encode($json);
			return ;
		} 
		if($post){
			$f_id = $post["f_id"];
			unset($post["submit"]);
			unset($post["f_id"]);
			$post["f_time"] = date("Y-m-d H:i:s");
			$flash_model = D("Flash");
			if($old_img != $post["f_img"]){
				unlink(".".$old_img);
			}
			if($f_id){
				if($id=$flash_model->where("f_id = ".$f_id)->save($post)){
					$type = "success";
					$infomation = "修改成功!";
				}
				else{
					$type = "error";
					$infomation = "修改失败!";
				}
			}
			else{
				if($id=$flash_model->data($post)->add()){
					$type = "success";
					$infomation = "添加成功!";
				}
				else{
					$type = "error";
					$infomation = "添加失败!";
				}
			}
			
			$json["info"] = $this->getInfomation($type, $infomation);
			$json["url"] = U('Flash/flashlist',array('page'=>$page),'');
			$json["path"] = "101011";
			echo json_encode($json);
		}
	}

	public function flash_delete($page=1, $f_id = 0){
		$flash_model = D("Flash");
		//$nav = $naf_model->where("n_id = ".$n_id)->find();
		//if($naf_model->where("n_path like '".$nav["n_path"]."%'")->delete()){
		if($flash_model->where("f_id = ".$f_id)->delete()){
			$type = "success";
			$infomation = "删除成功!";
		}
		else{
			$type = "error";
			$infomation = "删除失败!";
		}
		$json["info"] = $this->getInfomation($type, $infomation);
		$json["url"] = U('Flash/flashlist',array('page'=>$page),'');
		$json["path"] = "101011";
		echo json_encode($json);
	}

	public function flash_batch($page=1){
		$post = $_POST;
		$flash_model = D("Flash");
		if($post["choose"]=='delete'){
			unset($post["choose"]);
			foreach($post as $key=>$value){
				$f_ids[] = $key;
			}
			$delete_num = count($f_ids);
			if($flash = $flash_model->where("f_id in (".implode(",",$f_ids).")")->delete()){
				$type = "success";
				$infomation = "删除成功".$delete_num."条!";
			}
			else{
				$type = "error";
				$infomation = "删除失败!";
			}
		}
		$json["info"] = $this->getInfomation($type, $infomation);
		$json["url"] = U('Flash/flashlist',array('page'=>$page),'');
		$json["path"] = "101011";
		echo json_encode($json);
	}
	
	public function upload(){
		$time = date("YmdHms");
		$file = $_FILES["image"];
		$path = "./Public/uploads/images/flash/";
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
