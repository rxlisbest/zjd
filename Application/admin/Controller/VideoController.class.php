<?php
/*
 *create by roy
 */
namespace admin\Controller;
use admin\Common\YuController;
class VideoController extends YuController {

	public function videolist($page=1, $v_id=0){
		$video_model = D("Video");
		$url = U('Video/videolist',array('page'=>$page),'');

		$rowNum = $video_model->count();
		$pageSize = 15;
		$pages = ceil($rowNum/$pageSize);
		if($page > $pages)
			$page = $pages;
		if($page < 1)
			$page = 1;
		$curPage = $page ?: 1;
		$offset = ($curPage-1)*$pageSize;
		$video_list = $video_model->order("v_sort")->limit($offset, $pageSize)->select();
		$pagination = $this->getPagination($curPage, $pages, $url);
		$this->assign('page',$page);
		$this->assign('videoclass',$videoclass);
		if($v_id){
			$video = $video_model->where("v_id = ".$v_id)->find();
			$this->assign('video',$video);
		}
		$this->assign('video_list',$video_list);
		$this->assign('pagination',$pagination);

		$this->assign('videolistUrl',U('Video/videolist',array('page'=>$page),''));
		$this->assign('video_saveUrl',U('Video/video_save',array('page'=>$page),''));
		$this->assign('video_deleteUrl',U('Video/video_delete',array('page'=>$page),''));
		$this->assign('video_batchUrl',U('Video/video_batch',array('page'=>$page),''));
		$content = $this->fetch("video_list");
		$this->show($content);
		//$this->display("login");
	}

	public function video_save($page=1){
		$post = $_POST;
		$old_img = $post["v_img_old"];
		unset($post["v_img_old"]);
		if($post["v_img"]==""){
			$type = "error";
			$infomation = "图片没上传，添加失败!";
			$json["info"] = $this->getInfomation($type, $infomation);
			echo json_encode($json);
			return ;
		} 
		if($post){
			$v_id = $post["v_id"];
			unset($post["submit"]);
			unset($post["v_id"]);
			$post["v_time"] = date("Y-m-d H:i:s");
			$video_model = D("Video");
			if($old_img != $post["v_img"]){
				unlink(".".$old_img);
			}
			if($v_id){
				if($id=$video_model->where("v_id = ".$v_id)->save($post)){
					$type = "success";
					$infomation = "修改成功!";
				}
				else{
					$type = "error";
					$infomation = "修改失败!";
				}
			}
			else{
				if($id=$video_model->data($post)->add()){
					$type = "success";
					$infomation = "添加成功!";
				}
				else{
					$type = "error";
					$infomation = "添加失败!";
				}
			}
			
			$json["info"] = $this->getInfomation($type, $infomation);
			$json["url"] = U('Video/videolist',array('page'=>$page),'');
			$json["path"] = "101011";
			echo json_encode($json);
		}
	}

	public function video_delete($page=1, $v_id = 0){
		$video_model = D("Video");
		//$nav = $nav_model->where("n_id = ".$n_id)->find();
		//if($nav_model->where("n_path like '".$nav["n_path"]."%'")->delete()){
		if($video_model->where("v_id = ".$v_id)->delete()){
			$type = "success";
			$infomation = "删除成功!";
		}
		else{
			$type = "error";
			$infomation = "删除失败!";
		}
		$json["info"] = $this->getInfomation($type, $infomation);
		$json["url"] = U('Video/videolist',array('page'=>$page),'');
		$json["path"] = "101011";
		echo json_encode($json);
	}

	public function video_batch($page=1){
		$post = $_POST;
		$video_model = D("Video");
		if($post["choose"]=='delete'){
			unset($post["choose"]);
			foreach($post as $key=>$value){
				$v_ids[] = $key;
			}
			$delete_num = count($v_ids);
			if($video = $video_model->where("v_id in (".implode(",",$v_ids).")")->delete()){
				$type = "success";
				$infomation = "删除成功".$delete_num."条!";
			}
			else{
				$type = "error";
				$infomation = "删除失败!";
			}
		}
		$json["info"] = $this->getInfomation($type, $infomation);
		$json["url"] = U('Video/videolist',array('page'=>$page),'');
		$json["path"] = "101011";
		echo json_encode($json);
	}
	
	public function upload(){
		$time = date("YmdHms");
		$file = $_FILES["image"];
		$path = "./Public/uploads/images/video/";
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
