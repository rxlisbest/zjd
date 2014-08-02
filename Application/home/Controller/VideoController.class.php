<?php
/*
 *create by roy
 */
namespace home\Controller;
use Think\Controller;
use home\Common\HomeController;
class VideoController extends HomeController {
	public function index($id=0){
		$video_model = D("Video");
		$video = $video_model->order("v_sort ASC")->select();
		for($i=0;$i<ceil(count($video)/3);$i++){
			$new_video[] = array_slice($video, $i * 3 ,3);
		}
		//var_dump($new_video);exit;
		$this->assign('video', $new_video);
		$this->assign('id', $id);
		$this->assign('cur', 4); //当前高亮导航
		$this->display("video_list");
	}
}
