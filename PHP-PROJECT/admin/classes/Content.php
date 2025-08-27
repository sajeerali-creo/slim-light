<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('../config.php');
Class Content extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function update(){
		extract($_POST);
		$content_file="../".$file.".html";
		$update = file_put_contents($content_file, $content);
		if($update){
			return json_encode(array("status"=>"success"));
			$this->settings->set_flashdata("success",ucfirst($file)." content is successfuly updated");
			exit;
		}
	}
	public function about_us(){
		extract($_POST);
		$data = "";
		// $skip_fields = [
		// 	'id', 'file','section_1_content','section_1_content','section_2_content','section_3_content',
		// 	'deleted_gallery_names', 'existing_gallery_2',
		// 	'gallery_2_titles'
		// ];
		$skip_fields = [
			'id','file','section_1_content','section_2_content','section_3_content',
			'deleted_gallery_names', 'existing_gallery'
		];
			foreach ($_POST as $k => $v) {
			// Skip known array or special fields
				if (in_array($k, $skip_fields)) continue;

				// Also skip arrays just in case
				if (is_array($v)) continue;

				if (!empty($data)) $data .= ", ";
				$data .= "`$k` = '" . $this->conn->real_escape_string($v) . "'";
			}

		// foreach($_POST as $k => $v){
		// 	if(!in_array($k,array('id','section_1_content','section_2_content','section_3_content'))){
		// 		if(!empty($data)) $data .= ", ";
		// 		$data .= "`$k` = '$v'";
		// 	}
		// }

		if(!empty($data)) $data .= ", ";

		$data .= "`section_1_content` = '".addslashes(htmlentities($section_1_content))."'";

		$data .= ",`section_2_content` = '".addslashes(htmlentities($section_2_content))."'";
		$data .= ",`section_3_content` = '".addslashes(htmlentities($section_3_content))."'";

		

		if(empty($id)){
			$sql ="INSERT INTO about_us set $data";
		}else{
			$sql ="UPDATE about_us set $data where id = {$id}";
		}
		
		$save = $this->conn->query($sql);
		$action = empty($id) ? "added":"updated";
		
		$last_id = empty($id) ? $this->conn->insert_id : $id;

		// Create directory if not exist
		$gallery_dir = base_app . "uploads/team_gallary/";
		if (!is_dir($gallery_dir)) mkdir($gallery_dir, 0777, true);

				/*  ------------ Start gallary 1 code -------------*/
				// Get current images from DB
				$existing_gallery = [];
				$q = $this->conn->query("SELECT gallery_images FROM about_us WHERE id = {$last_id}");
				if ($q && $q->num_rows > 0) {
					$existing_gallery = array_filter(explode(',', $q->fetch_assoc()['gallery_images']));
				}
				// Get deleted image names from POST
				$deleted_gallery = isset($_POST['deleted_gallery_names']) ? array_map('trim', explode(',', $_POST['deleted_gallery_names'])) : [];
				// Remove deleted images gallary 1
				foreach ($deleted_gallery as $img) {
					$img = basename($img);
					$img_path = $gallery_dir . $img;

					// 1. Delete the physical file
					if ($img && file_exists($img_path) && is_file($img_path)) {
						unlink($img_path);
					}

					// 2. Remove from $existing_gallery array
					$existing_gallery = array_diff($existing_gallery, [$img]);
				}
				// Upload new images
				if (!empty($_FILES['gallery_imgs']['tmp_name'][0])) {
					foreach ($_FILES['gallery_imgs']['tmp_name'] as $key => $tmp_name) {
						$new_name = uniqid('img_', true) . "_" . basename($_FILES['gallery_imgs']['name'][$key]);
						if (move_uploaded_file($tmp_name, $gallery_dir . $new_name)) {
							$existing_gallery[] = $new_name;
						}
					}
				}
				// Save updated image list to DB
				$gallery_field = implode(',', $existing_gallery);

		/*------------------ End gallary 1-----------------------*/

		
		$this->conn->query("UPDATE about_us SET gallery_images = '{$gallery_field}'  WHERE id = {$last_id}");


		$action = empty($id) ? "added":"updated";
		if($save){
			if(isset($move) && $move && !empty($old_file)){
				if(is_file(base_app.$old_file))
					unlink(base_app.$old_file);
			}
			$resp['status']='success';
			$resp['message']= " About Us Details successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
			
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
		exit;
	}
	function minify_html($html) {
    // Remove newlines, tabs, and unnecessary spaces
    $html = preg_replace('/\s+/', ' ', $html);         // Collapse multiple whitespaces
    $html = preg_replace('/>\s+</', '><', $html);       // Remove spaces between tags
    return trim($html);
	}
	public function admin_home(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','who_we_are','how_it_work','why_bLite','exciting_news'))){
				if(!empty($data)) $data .= ", ";
				$data .= "`$k` = '$v'";
			}
		}
		
		if(!empty($data)) $data .= ", ";

		$data .= "`who_we_are` = '".addslashes(htmlentities($who_we_are))."'";

		$data .= ",`how_it_work` = '".$this->conn->real_escape_string($how_it_work)."'";
		$data .= ",`why_bLite` = '".addslashes(htmlentities($why_bLite))."'";
		$data .= ",`exciting_news` = '".addslashes(htmlentities($exciting_news))."'";

		if(empty($id)){
			$sql ="INSERT INTO admin_home set $data";
		}else{
			$sql ="UPDATE admin_home set $data where id = {$id}";
		}
			
		$save = $this->conn->query($sql);
		$action = empty($id) ? "added":"updated";
		if($save){
			if(isset($move) && $move && !empty($old_file)){
				if(is_file(base_app.$old_file))
					unlink(base_app.$old_file);
			}
			$resp['status']='success';
			$resp['message']= " Home Content Details successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
			
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
		exit;
	}
	
	public function banner(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','message','old_file'))){
				if(!empty($data)) $data .= ", ";
				$data .= "`$k` = '$v'";
			}
		}

		//if(!empty($data)) $data .= ", ";
		if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])){
			$fname = 'uploads/'.time().'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],base_app.$fname);
			if($move){
				$data .=" , `file_path` = '{$fname}' ";
			}
		}
		
		if(empty($id)){
			$sql ="INSERT INTO banners set $data";
		}else{
			$sql ="UPDATE banners set $data where id = {$id}";
		}
	
		$save = $this->conn->query($sql);
		$action = empty($id) ? "added":"updated";
		if($save){
			if(isset($move) && $move && !empty($old_file)){
				if(is_file(base_app.$old_file))
					unlink(base_app.$old_file);
			}
			$resp['status']='success';
			$resp['message']= " Banner successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
			
		}else{
			$resp['status']='failed';
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
		exit;
	}

	public function banner_delete(){
		extract($_POST);
		$fpath = $this->conn->query("SELECT file_path FROM banners where id = $id")->fetch_array()['file_path'];
		$qry = $this->conn->query("DELETE FROM banners where id = $id");
		if($qry){
			if(is_file(base_app.$fpath))
					unlink(base_app.$fpath);
			$resp['status']='success';
			$resp['message']= " Testimony successfully deleted";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='success';
			$resp['error']= $this->conn->error;
			$resp['err_msg']= " Testimony has failed to delete";
		}
		return json_encode($resp);
	}
	public function meal_plan(){ 
		extract($_POST);
		$data = "";
		$skip_fields = [
			'id', 'description', 'old_file',
			'deleted_gallery_names', 'existing_gallery',
			'deleted_gallery_2_names', 'existing_gallery_2',
			'gallery_2_titles', 'gallery_titles'
		];
			foreach ($_POST as $k => $v) {
			// Skip known array or special fields
				if (in_array($k, $skip_fields)) continue;

				// Also skip arrays just in case
				if (is_array($v)) continue;

				if (!empty($data)) $data .= ", ";
				$data .= "`$k` = '" . $this->conn->real_escape_string($v) . "'";
			}
				if(!empty($data)) $data .= ", ";
				$data .= "`description` = '".addslashes(htmlentities($description))."'";
		if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])){
			$fname = 'uploads/'.time().'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],base_app.$fname);
			if($move){
				$data .=" , `file_path` = '{$fname}' ";
			}
		}
		
		if(empty($id)){
			$sql ="INSERT INTO meal_plans set $data";
		}else{
			$sql ="UPDATE meal_plans set $data where id = {$id}";
		}
		$save = $this->conn->query($sql);
		
		$last_id = empty($id) ? $this->conn->insert_id : $id;

		// Create directory if not exist
		$gallery_dir = base_app . "uploads/meal_gallery/";
		if (!is_dir($gallery_dir)) mkdir($gallery_dir, 0777, true);


		/*  ------------ Start gallary 1 code -------------*/
				// Get current images from DB
				$existing_gallery = [];
				$q = $this->conn->query("SELECT gallery_images FROM meal_plans WHERE id = {$last_id}");
				if ($q && $q->num_rows > 0) {
					$existing_gallery = array_filter(explode(',', $q->fetch_assoc()['gallery_images']));
				}
				// Get deleted image names from POST
				$deleted_gallery = isset($_POST['deleted_gallery_names']) ? array_map('trim', explode(',', $_POST['deleted_gallery_names'])) : [];
				// Remove deleted images gallary 1
				foreach ($deleted_gallery as $img) {
					$img = basename($img);
					$img_path = $gallery_dir . $img;

					// 1. Delete the physical file
					if ($img && file_exists($img_path) && is_file($img_path)) {
						unlink($img_path);
					}

					// 2. Remove from $existing_gallery array
					$existing_gallery = array_diff($existing_gallery, [$img]);
				}
				// Upload new images
				if (!empty($_FILES['gallery_imgs']['tmp_name'][0])) {
					foreach ($_FILES['gallery_imgs']['tmp_name'] as $key => $tmp_name) {
						$new_name = uniqid('img_', true) . "_" . basename($_FILES['gallery_imgs']['name'][$key]);
						if (move_uploaded_file($tmp_name, $gallery_dir . $new_name)) {
							$existing_gallery[] = $new_name;
						}
					}
				}
				// Save updated image list to DB
				$gallery_field = implode(',', $existing_gallery);

		/*------------------ End gallary 1-----------------------*/


		/*------------------ Start gallary 2-----------------------*/
				

			// Load existing gallery
			$existing_gallery_2 = [];
			$res = $this->conn->query("SELECT gallery_2_images FROM meal_plans WHERE id = {$last_id}");
			if ($res && $res->num_rows) {
			    $data = $res->fetch_assoc();
			    $existing_gallery_2 = array_filter(explode(',', $data['gallery_2_images']));
			}

			$deleted_gallery_2 = array_filter(
			    explode(',', $_POST['deleted_gallery_2_names'] ?? ''),
			    fn($v) => trim($v) !== ''
			);


		foreach ($deleted_gallery_2 as $del_img) {
		    $del_img = trim($del_img);
		    $img_name = explode('::', $del_img)[0];  // In case it's with title

		    $img_path = $gallery_dir . $img_name;
		    if (is_file($img_path)) {
		        unlink($img_path);
		    }

		    // Remove matching image from existing_gallery_2
		    $existing_gallery_2 = array_filter($existing_gallery_2, function($item) use ($img_name) {
		        return strpos($item, $img_name) !== 0 ? true : false;
		    });
		}

		// Upload new images with title
		$new_gallery_2 = [];
		if (!empty($_FILES['gallery_2_imgs']['tmp_name'][0])) {
		    foreach ($_FILES['gallery_2_imgs']['tmp_name'] as $i => $tmp) {
		        $new_name = uniqid('img_', true) . "_" . basename($_FILES['gallery_2_imgs']['name'][$i]);
		        $title = trim($_POST['gallery_2_titles'][$i] ?? '');
		        if (move_uploaded_file($tmp, $gallery_dir . $new_name)) {
		            $new_gallery_2[] = $new_name . (!empty($title) ? "::" . $title : '');
		        }
		    }
		}

		// Update titles of existing images
		if (isset($_POST['gallery_2_titles_existing'])) {
		    foreach ($existing_gallery_2 as $i => $entry) {
		        [$img] = explode('::', $entry);
		        $title = trim($_POST['gallery_2_titles_existing'][$img] ?? '');
		        $existing_gallery_2[$i] = $img . (!empty($title) ? "::" . $title : '');
		    }
		}

		// Final merge and save
		$final_gallery_2 = array_merge($existing_gallery_2, $new_gallery_2);
		//$this->conn->query("UPDATE meal_plans SET gallery_2_images = '" . implode(',', $final_gallery_2) . "' WHERE id = {$last_id}");


		/*--------------------- End gallery 2 ------------------*/

		$this->conn->query("UPDATE meal_plans SET gallery_images = '{$gallery_field}', gallery_2_images = '" . implode(',', $final_gallery_2) . "' WHERE id = {$last_id}");


		$action = empty($id) ? "added":"updated";
		if($save){
			if(isset($move) && $move && !empty($old_file)){
				if(is_file(base_app.$old_file))
					unlink(base_app.$old_file);
			}
			$resp['status']='success';
			$resp['message']= " Meal Plan Details successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
			
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
		exit;
	}
	public function meal_plan_delete(){
		extract($_POST);
		$fpath = $this->conn->query("SELECT file_path FROM meal_plans where id = $id")->fetch_array()['file_path'];
		$qry = $this->conn->query("DELETE FROM meal_plans where id = $id");
		if($qry){
			if(is_file(base_app.$fpath))
					unlink(base_app.$fpath);
			$resp['status']='success';
			$resp['message']= " Meal Plan Details successfully deleted";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='Failed';
			$resp['error']= $this->conn->error;
			$resp['err_msg'] = " Deleting Data failed";
		}
		return json_encode($resp);
	}
	 
	public function signature_dish(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','description','old_file'))){
				if(!empty($data)) $data .= ", ";
				$data .= "`$k` = '$v'";
			}
		}
				if(!empty($data)) $data .= ", ";
				$data .= "`description` = '".addslashes(htmlentities($description))."'";
		if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])){
			$fname = 'uploads/'.time().'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],base_app.$fname);
			if($move){
				$data .=" , `file_path` = '{$fname}' ";
			}
		}
		if(empty($id)){
			$sql ="INSERT INTO signature_dishes set $data";
		}else{
			$sql ="UPDATE signature_dishes set $data where id = {$id}";
		}
		$save = $this->conn->query($sql);
		$action = empty($id) ? "added":"updated";
		if($save){
			if(isset($move) && $move && !empty($old_file)){
				if(is_file(base_app.$old_file))
					unlink(base_app.$old_file);
			}
			$resp['status']='success';
			$resp['message']= "Signature Dish Details successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
			
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
		exit;
	}
	public function signature_dish_delete(){
		extract($_POST);
		$fpath = $this->conn->query("SELECT file_path FROM signature_dishes where id = $id")->fetch_array()['file_path'];
		$qry = $this->conn->query("DELETE FROM signature_dishes where id = $id");
		if($qry){
			if(is_file(base_app.$fpath))
					unlink(base_app.$fpath);
			$resp['status']='success';
			$resp['message']= " Signature Details successfully deleted";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='Failed';
			$resp['error']= $this->conn->error;
			$resp['err_msg'] = " Deleting Data failed";
		}
		return json_encode($resp);
	}
	public function coupon_code(){
		if (isset($_POST['name'])) {
				$_POST['name'] = str_replace(' ', '', $_POST['name']);
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','description'))){
				if(!empty($data)) $data .= ", ";
				$data .= "`$k` = '$v'";
			}
		}
		if(empty($id)){
			 $sql ="INSERT INTO coupon_codes set $data";
		}else{
			$sql ="UPDATE coupon_codes set $data where id = {$id}";
		}
		
		$save = $this->conn->query($sql);
		$action = empty($id) ? "added":"updated";
		if($save){
			$resp['status']='success';
			$resp['message']= "Coupon Code Details successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
			
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
		exit;
	}
	public function coupon_code_delete(){
		extract($_POST);
		//$fpath = $this->conn->query("SELECT file_path FROM coupon_codes where id = $id")->fetch_array()['file_path'];
		$qry = $this->conn->query("DELETE FROM coupon_codes where id = $id");
		if($qry){
			// if(is_file(base_app.$fpath))
			// 		unlink(base_app.$fpath);
			$resp['status']='success';
			$resp['message']= " Coupon successfully deleted";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='Failed';
			$resp['error']= $this->conn->error;
			$resp['err_msg'] = " Deleting Data failed";
		}
		return json_encode($resp);
	}
	public function success_story(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','description','old_file'))){
				if(!empty($data)) $data .= ", ";
				$data .= "`$k` = '$v'";
			}
		}
				if(!empty($data)) $data .= ", ";
				$data .= "`description` = '".addslashes(htmlentities($description))."'";
		if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])){
			$fname = 'uploads/'.time().'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],base_app.$fname);
			if($move){
				$data .=" , `file_path` = '{$fname}' ";
			}
		}
		if(empty($id)){
			$sql ="INSERT INTO success_stories set $data";
		}else{
			$sql ="UPDATE success_stories set $data where id = {$id}";
		}
		$save = $this->conn->query($sql);
		$action = empty($id) ? "added":"updated";
		if($save){
			if(isset($move) && $move && !empty($old_file)){
				if(is_file(base_app.$old_file))
					unlink(base_app.$old_file);
			}
			$resp['status']='success';
			$resp['message']= " Success Story Details successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
			
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
		exit;
	}
	public function success_story_delete(){
		extract($_POST);
		$fpath = $this->conn->query("SELECT file_path FROM success_stories where id = $id")->fetch_array()['file_path'];
		$qry = $this->conn->query("DELETE FROM success_stories where id = $id");
		if($qry){
			if(is_file(base_app.$fpath))
					unlink(base_app.$fpath);
			$resp['status']='success';
			$resp['message']= " Success Story Details successfully deleted";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='Failed';
			$resp['error']= $this->conn->error;
			$resp['err_msg'] = " Deleting Data failed";
		}
		return json_encode($resp);
	}
	
	public function service(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','description','old_file'))){
				if(!empty($data)) $data .= ", ";
				$data .= "`$k` = '$v'";
			}
		}
				if(!empty($data)) $data .= ", ";
				$data .= "`description` = '".addslashes(htmlentities($description))."'";
		if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])){
			$fname = 'uploads/'.time().'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],base_app.$fname);
			if($move){
				$data .=" , `file_path` = '{$fname}' ";
			}
		}
		if(empty($id)){
			$sql ="INSERT INTO services set $data";
		}else{
			$sql ="UPDATE services set $data where id = {$id}";
		}
		$save = $this->conn->query($sql);
		$action = empty($id) ? "added":"updated";
		if($save){
			if(isset($move) && $move && !empty($old_file)){
				if(is_file(base_app.$old_file))
					unlink(base_app.$old_file);
			}
			$resp['status']='success';
			$resp['message']= " Service Details successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
			
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
		exit;
	}
	public function service_delete(){
		extract($_POST);
		$fpath = $this->conn->query("SELECT file_path FROM services where id = $id")->fetch_array()['file_path'];
		$qry = $this->conn->query("DELETE FROM services where id = $id");
		if($qry){
			if(is_file(base_app.$fpath))
					unlink(base_app.$fpath);
			$resp['status']='success';
			$resp['message']= " Service Details successfully deleted";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='Failed';
			$resp['error']= $this->conn->error;
			$resp['err_msg'] = " Deleting Data failed";
		}
		return json_encode($resp);
	}
	public function blog(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','description','old_file'))){
				if(!empty($data)) $data .= ", ";
				$data .= "`$k` = '$v'";
			}
		}
				if(!empty($data)) $data .= ", ";
				$data .= "`description` = '".addslashes(htmlentities($description))."'";
		if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])){
			$fname = 'uploads/'.time().'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],base_app.$fname);
			if($move){
				$data .=" , `file_path` = '{$fname}' ";
			}
		}
		if(empty($id)){
			$sql ="INSERT INTO blogs set $data";
		}else{
			$sql ="UPDATE blogs set $data where id = {$id}";
		}
		$save = $this->conn->query($sql);
		$action = empty($id) ? "added":"updated";
		if($save){
			if(isset($move) && $move && !empty($old_file)){
				if(is_file(base_app.$old_file))
					unlink(base_app.$old_file);
			}
			$resp['status']='success';
			$resp['message']= " Blog Details successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
			
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
		exit;
	}
	public function blog_delete(){
		extract($_POST);
		$fpath = $this->conn->query("SELECT file_path FROM blogs where id = $id")->fetch_array()['file_path'];
		$qry = $this->conn->query("DELETE FROM blogs where id = $id");
		if($qry){
			if(is_file(base_app.$fpath))
					unlink(base_app.$fpath);
			$resp['status']='success';
			$resp['message']= " Blog Details successfully deleted";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='Failed';
			$resp['error']= $this->conn->error;
			$resp['err_msg'] = " Deleting Data failed";
		}
		return json_encode($resp);
	}
	public function testimonial(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','message','old_file'))){
				if(!empty($data)) $data .= ", ";
				$data .= "`$k` = '$v'";
			}
		}
				if(!empty($data)) $data .= ", ";
				$data .= "`message` = '".addslashes(htmlentities($message))."'";
		if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])){
			$fname = 'uploads/'.time().'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],base_app.$fname);
			if($move){
				$data .=" , `file_path` = '{$fname}' ";
			}
		}
		if(empty($id)){
			$sql ="INSERT INTO testimonials set $data";
		}else{
			$sql ="UPDATE testimonials set $data where id = {$id}";
		}
		$save = $this->conn->query($sql);
		$action = empty($id) ? "added":"updated";
		if($save){
			if(isset($move) && $move && !empty($old_file)){
				if(is_file(base_app.$old_file))
					unlink(base_app.$old_file);
			}
			$resp['status']='success';
			$resp['message']= " Testimony successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
			
		}else{
			$resp['status']='failed';
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
		exit;
	}

	public function testimonial_delete(){
		extract($_POST);
		$fpath = $this->conn->query("SELECT file_path FROM testimonials where id = $id")->fetch_array()['file_path'];
		$qry = $this->conn->query("DELETE FROM testimonials where id = $id");
		if($qry){
			if(is_file(base_app.$fpath))
					unlink(base_app.$fpath);
			$resp['status']='success';
			$resp['message']= " Testimony successfully deleted";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='success';
			$resp['error']= $this->conn->error;
			$resp['err_msg']= " Testimony has failed to delete";
		}
		return json_encode($resp);
	}

	public function client(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','description','old_file'))){
				if(!empty($data)) $data .= ", ";
				$data .= "`$k` = '$v'";
			}
		}
				if(!empty($data)) $data .= ", ";
				$data .= "`description` = '".addslashes(htmlentities($description))."'";

		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
				$fname = 'uploads/'.strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'],base_app. $fname);
				if($move){
					$data .=" , file_path = '{$fname}' ";
				}
		}	

		if(empty($id)){
			$sql ="INSERT INTO clients set $data";
		}else{
			$sql ="UPDATE clients set $data where id = {$id}";
		}
		$save = $this->conn->query($sql);
		$action = empty($id) ? "added":"updated";
		if($save){
			if(isset($move) && $move && !empty($old_file)){
				if(is_file(base_app.$old_file))
					unlink(base_app.$old_file);
			}
			$resp['status']='success';
			$resp['message']= " Client Details successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
			
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
		exit;
	}

	public function client_delete(){
		extract($_POST);
		$fpath = $this->conn->query("SELECT file_path FROM clients where id = $id")->fetch_array()['file_path'];
		$qry = $this->conn->query("DELETE FROM clients where id = $id");
		if($qry){
			if(is_file(base_app.$fpath))
					unlink(base_app.$fpath);
			$resp['status']='success';
			$resp['message']= " Client Details successfully deleted";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='success';
			$resp['error']= $this->conn->error;
			$resp['err_msg']= " Client Details has failed to delete";
		}
		return json_encode($resp);

	}
	public function contact(){
		extract($_POST);
		$data = "";
		foreach ($_POST as $key => $value) {
			if(!empty($data)) $data .= ", ";
				$data .= "('{$key}','{$value}')";
		}
		$this->conn->query("TRUNCATE `contacts`");
		$sql = "INSERT INTO `contacts` (meta_field, meta_value) Values $data";
		$qry = $this->conn->query($sql);
		if($qry){
			$resp['status']='success';
			$resp['message']= " Contact Details successfully updated";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='error';
			$resp['message']= $sql;
		}
		return json_encode($resp);
		exit;
	}
	public function message_delete(){
		extract($_POST);
		$qry = $this->conn->query("DELETE FROM messages where id = $id");
		if($qry){
			$resp['status']='success';
			$resp['message']= " Inquiry successfully deleted";
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='success';
			$resp['error']= $this->conn->error;
			$resp['err_msg']= " Inquiry has failed to delete";
		}
		return json_encode($resp);

	}

	public function terms_conditions(){
		extract($_POST);
		$data = "";
		
		// Set default values for required fields
		$title = "Terms & Conditions";
		$version = "1.0";
		$is_active = 1;
		
		$data .= "`title` = '".$this->conn->real_escape_string($title)."'";
		$data .= ",`version` = '".$this->conn->real_escape_string($version)."'";
		$data .= ",`is_active` = '".$this->conn->real_escape_string($is_active)."'";
		$data .= ",`content` = '".addslashes(htmlentities($content))."'";

		if(empty($id)){
			$sql ="INSERT INTO terms_conditions set $data";
		}else{
			$sql ="UPDATE terms_conditions set $data where id = {$id}";
		}
			
		$save = $this->conn->query($sql);
		$action = empty($id) ? "added":"updated";
		if($save){
			$resp['status']='success';
			$resp['message']= " Terms & Conditions successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
		exit;
	}

	public function privacy_policy(){
		extract($_POST);
		$data = "";
		
		// Set default values for required fields
		$title = "Privacy Policy";
		$version = "1.0";
		$is_active = 1;
		
		$data .= "`title` = '".$this->conn->real_escape_string($title)."'";
		$data .= ",`version` = '".$this->conn->real_escape_string($version)."'";
		$data .= ",`is_active` = '".$this->conn->real_escape_string($is_active)."'";
		$data .= ",`content` = '".addslashes(htmlentities($content))."'";

		if(empty($id)){
			$sql ="INSERT INTO privacy_policy set $data";
		}else{
			$sql ="UPDATE privacy_policy set $data where id = {$id}";
		}
			
		$save = $this->conn->query($sql);
		$action = empty($id) ? "added":"updated";
		if($save){
			$resp['status']='success';
			$resp['message']= " Privacy Policy successfully ".$action;
			$this->settings->set_flashdata('success',$resp['message']);
		}else{
			$resp['status']='failed';
			$resp['error']= $this->conn->error;
			$resp['message']= " error:".$sql;
		}
		return json_encode($resp);
		exit;
	}
}

$Content = new Content();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'update':
		echo $Content->update();
	break;
	case 'admin_home':
		echo $Content->admin_home();
	break;
	case 'about_us':
		echo $Content->about_us();
	break;
	case 'banner':
		echo $Content->banner();
	break;
	case 'banner_delete':
		echo $Content->banner_delete();
	break;
	case 'meal_plan':
		echo $Content->meal_plan();
	break;
	case 'meal_plan_delete':
		echo $Content->meal_plan_delete();
	break;
	case 'signature_dish':
		echo $Content->signature_dish();
	break;
	case 'signature_dish_delete':
		echo $Content->signature_dish_delete();
	break;
	case 'coupon_code':
		echo $Content->coupon_code();
	break;
	case 'coupon_code_delete':
		echo $Content->coupon_code_delete();
	break;
	case 'success_story':
		echo $Content->success_story();
	break;
	case 'success_story_delete':
		echo $Content->success_story_delete();
	break;
	case 'service':
		echo $Content->service();
	break;
	case 'service_delete':
		echo $Content->service_delete();
	break;
	case 'blog':
		echo $Content->blog();
	break;
	case 'blog_delete':
		echo $Content->blog_delete();
	break;
	case 'testimonial':
		echo $Content->testimonial();
	break;
	case 'testimonial_delete':
		echo $Content->testimonial_delete();
	break;
	case 'client':
		echo $Content->client();
	break;
	case 'client_delete':
		echo $Content->client_delete();
	break;
	case 'message_delete':
		echo $Content->message_delete();
	break;
	case 'contact':
		echo $Content->contact();
	break;
	case 'terms_conditions':
		echo $Content->terms_conditions();
	break;
	case 'privacy_policy':
		echo $Content->privacy_policy();
	break;
	default:
		// echo $sysset->index();
		break;
}