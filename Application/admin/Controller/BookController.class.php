<?php
/*
 *create by roy
 */
namespace admin\Controller;
use admin\Common\YuController;
class BookController extends YuController {

	public function caselist($page=1, $cs_id=0){
		$case_model = D("Book");
		$url = U('Book/caselist',array('page'=>$page),'');

		$rowNum = $case_model->where("c_id=1")->count();
		$pageSize = 15;
		$pages = ceil($rowNum/$pageSize);
		if($page > $pages)
			$page = $pages;
		if($page < 1)
			$page = 1;
		$curPage = $page ?: 1;
		$offset = ($curPage-1)*$pageSize;
		$case_list = $case_model->where("c_id=1")->order("cs_sort")->limit($offset, $pageSize)->select();
		$pagination = $this->getPagination($curPage, $pages, $url);
		$this->assign('page',$page);
		$this->assign('caseclass',$caseclass);
		if($cs_id){
			$case = $case_model->where("cs_id = ".$cs_id)->find();
			$this->assign('case',$case);
			$image_model = D("Image");
			$img_id = $case["cs_image"];
			$images = $image_model->where("img_id in (".$img_id.")")->select();
		}
		else{
			$images = array(0=>array());
		}
		$this->assign('case_images',$images);
		$this->assign('case_list',$case_list);
		$this->assign('pagination',$pagination);

		$this->assign('caselistUrl',U('Book/caselist',array('page'=>$page),''));
		$this->assign('case_saveUrl',U('Book/case_save',array('page'=>$page),''));
		$this->assign('case_deleteUrl',U('Book/case_delete',array('page'=>$page),''));
		$this->assign('case_batchUrl',U('Book/case_batch',array('page'=>$page),''));
		$content = $this->fetch("book_list");
		$this->show($content);
		//$this->display("login");
	}

	public function case_save($page=1){
		$image_model = D("Image");
		$post = $_POST;
		$path = "./Public/uploads/images/case/";
		$img_types = array("image/gif","image/pjpeg","image/jpeg","image/png");
		if(!file_exists($path)){
			mkdir($path);
			chmod($path,0777);
		}	
		$error = array();
		$success = array();
		foreach($_POST["picDescribe"] as $key=>$value){
			if($post["img_id"][$key]){
				$img_edit["img_des"] = $post["picDescribe"][$key];
				$id=$image_model->where("img_id = ".$post["img_id"][$key])->save($img_edit);
			}
			if($_FILES["picFile"]["tmp_name"][$key] AND $post["img_id"][$key]==""){
				if(!in_array($_FILES["picFile"]["type"][$key],$img_types)){
					$error[$key] = "图片类型不正确!";
					continue;
				}
				if($_FILES["picFile"]["size"][$key] > 100000){
					$error[$key] = "图片太大!";
					continue;
				}
				$time = date("YmdHis");	
				$types = array("image/gif"=>".gif","image/pjpeg"=>".pjpeg","image/jpeg"=>".jpeg","image/png"=>".png"); 
				$image_type = $types[$_FILES["picFile"]["type"][$key]];
				$image_name = $path.$time."-".$key.$image_type;
				move_uploaded_file($_FILES["picFile"]["tmp_name"][$key],$image_name);
				$image_url = str_replace("./","/",$image_name);
				$image_info["img_src"] = $image_url;
				$image_info["img_des"] = $value;
				$img_id = $image_model->data($image_info)->add();
				if($img_id){
					$success[$key] = $img_id;
					$post["img_id"][] = $img_id;
				}
				else{
					$error[$key] = "图片保存失败!";
				}
			}
			if(!$_FILES["picFile"]["tmp_name"][$key] AND $post["img_id"][$key]==""){
				$error[$key] = "图片不能为空!";
				continue;
				
			}
			if($key==$post["cover_img"]){
				$post["cs_cover"] = $post["img_id"][$key] ?: $img_id;
				unset($post["cover_img"]);
			}
		}
		$data = array("success"=>$success,"error"=>$error);
		$data = json_encode($data);
		echo "<script>window.parent.show_upload_result(".$data.");</script>";
		if(empty($error)){
			$case_model = D("Book");
			unset($post["picDescribe"]);
			$post["img_id"] = array_filter($post["img_id"]);
			$post["cs_image"] = implode(",", $post["img_id"]);
			unset($post["img_id"]);
			$post["cs_time"] = date("Y-m-d H:m:s");

			$cs_id = $post["cs_id"];
			unset($post["submit"]);
			unset($post["cs_id"]);
			if($cs_id){
				if($id=$case_model->where("cs_id = ".$cs_id)->save($post)){
					$type = "success";
					$infomation = "修改成功!";
				}
				else{
					$type = "error";
					$infomation = "修改失败!";
				}
			}
			else{
				if($id=$case_model->data($post)->add()){
					$type = "success";
					$infomation = "添加成功!";
				}
				else{
					$type = "error";
					$infomation = "添加失败!";
				}
			}
			$json["info"] = $this->getInfomation($type, $infomation);
			$json["url"] = U('Book/caselist',array('page'=>$page),'');
			$json["path"] = "101011";
			echo "<script>window.parent.form_bridge(".json_encode($json).");</script>";
		}
		return ;
	}

	public function case_delete($page=1, $cs_id = 0){
		$case_model = D("Book");
		//$nav = $nav_model->where("n_id = ".$n_id)->find();
		//if($nav_model->where("n_path like '".$nav["n_path"]."%'")->delete()){
		if($case_model->where("cs_id = ".$cs_id)->delete()){
			$type = "success";
			$infomation = "删除成功!";
		}
		else{
			$type = "error";
			$infomation = "删除失败!";
		}
		$json["info"] = $this->getInfomation($type, $infomation);
		$json["url"] = U('Book/caselist',array('page'=>$page),'');
		$json["path"] = "101011";
		echo json_encode($json);
	}

	public function case_batch($page=1){
		$post = $_POST;
		$case_model = D("Book");
		if($post["choose"]=='delete'){
			unset($post["choose"]);
			foreach($post as $key=>$value){
				$cs_ids[] = $key;
			}
			$delete_num = count($cs_ids);
			if($case = $case_model->where("cs_id in (".implode(",",$cs_ids).")")->delete()){
				$type = "success";
				$infomation = "删除成功".$delete_num."条!";
			}
			else{
				$type = "error";
				$infomation = "删除失败!";
			}
		}
		$json["info"] = $this->getInfomation($type, $infomation);
		$json["url"] = U('Book/caselist',array('page'=>$page),'');
		$json["path"] = "101011";
		echo json_encode($json);
	}
	
	public function upload(){
		$time = date("YmdHms");
		$file = $_FILES["image"];
		$path = "./Public/uploads/images/case/";
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
