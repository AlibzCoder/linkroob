<?php
/*
Plugin Name: ادمین لینکروب
Plugin URI: http://linkroob.ir
Author: Alibz
Version: 1
Author URI: http://alibz.com
*/

add_action('admin_menu', 'setup_menu');
function setup_menu(){add_menu_page('مدیریت سایت','مدیریت سایت','manage_options','linkroobmgr','init');}
function init(){include(dirname(__FILE__).DIRECTORY_SEPARATOR."admin.php");}
add_action('init', 'do_output_buffer');
function do_output_buffer() {ob_start();}




function meks_time_ago($postID = null) {
	if(!empty($postID)){
		return human_time_diff( get_the_time( 'U' , $postID), current_time( 'timestamp' ) ).' '.__( 'پیش' );
	}else{
		return human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ).' '.__( 'پیش' );
	}
}
function check_url($url) {
   $headers = @get_headers( $url);
   $headers = (is_array($headers)) ? implode( "\n ", $headers) : $headers;

   return (bool)preg_match('#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers);
}
function EditGETQuery($q,$v){
	return http_build_query(array_merge($q,$v));
}
function unique_obj($obj) {
    static $idList = array();
    if(in_array($obj->id,$idList)) {
        return false;
    }
    $idList []= $obj->id;
    return true;
}
function upload_img($file,$name = null,$img_size_important = 1,$path = "/img/"){
	$Error = null;
	$uploadOk = 1;
	$name = (empty($name)) ? pathinfo($file["name"])['filename'] : $name;
	if(!$file['error'] == UPLOAD_ERR_NO_FILE) {
		$target_dir = wp_get_upload_dir()['basedir'].$path;
		$imageFileType = strtolower(pathinfo($file["name"],PATHINFO_EXTENSION));
		
				
		// Image Validation
		$check = getimagesize($file["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
			if($img_size_important && $check[0]!=$check[1]){
				$Error = "عکس باید مربعی باشد";
				$uploadOk = 0;
			}else if ($file['error'] == UPLOAD_ERR_FORM_SIZE) {
				$Error = "حجم عکس زیاد از حد مجاز است";
				$uploadOk = 0;
			}else if($imageFileType != "jpg" && $imageFileType != "jpeg") {
				$Error = "لطفا فایل فقط با پسوند jpg یا jpeg انتخاب کنید";
				$uploadOk = 0;
			}
		}else{
			$uploadOk = 0;
			$Error = "فایل عکس نیست !";
		}
		$target_file = $target_dir . $name .'.jpg';
		if($uploadOk){
			if (!move_uploaded_file($file["tmp_name"], $target_file)) {$Error = "عکس اپلود نشد!";}
		}
	}else{
		$Error = "لطفا عکس را انتخاب کنید";
	}
	return $Error;
}







function add_chanel($title,$sn,$cat,$subcat,$chanel_id,$joinchat,$description,$hashtags,$city,$post_status){
	$date = date('Y-m-d H:i:s',current_time('timestamp',0));
	$chanel = array(
		'post_title' => $title,
		'post_date' => $date,
		'post_content' => $chanel_id,
		'post_name' => $chanel_id,
		'post_status' => $post_status,
		'post_type' => 'post',
	);
	$post_id = wp_insert_post( $chanel );
	$data = array(
	   'ID' => $post_id,
	   'guid' => get_option('siteurl') .'/?p='.$post_id
	);
	wp_update_post($data);
	global $wpdb;
	$wpdb->insert('chanels_ditails', array(
		'chanel_id' => $chanel_id,
		'cat_id' =>$cat,
		'subcat_id' =>$subcat,
		'social_network_id' =>$sn,
		'city_id' => $city,
		'description' => $description,
		'joinchat_url' => $joinchat,
		'vip_date' => time(),
		'upgrade' => 0
	),array('%s','%d','%d','%d','%d','%s','%s','%d','%d'));
	
	$chanel_detail_id = $wpdb->get_var("SELECT id FROM `chanels_ditails` WHERE `chanel_id` = '".$chanel_id."'");
	$hashtags = explode(',',$hashtags);
	for($i = 0;$i<count($hashtags);$i++){
		$wpdb->insert('chanels_hashtags', array(
			'chanel_ditail_id' => $chanel_detail_id,
			'hashtag_id' => $hashtags[$i]
		),array('%d','%d'));
	}
}
function edit_chanel($chanel,$chanel_id,$title,$sn,$cat,$subcat,$joinchat,$description,$hashtags,$city){
	global $wpdb;
	
	$sql = "UPDATE chanels_ditails SET";
	
	$i = 0;
	if($chanel->chanel_id!=$chanel_id){
		$sql .= " chanel_id = '".$chanel_id."'";
		$i=1;
	}
	if($chanel->social_network_id!=$sn){
		if($i) $sql .=" ,"; else $i=1;
		$sql .= " social_network_id = ".$sn;
	}
	if($chanel->cat_id!=$cat){
		if($i) $sql .=" ,"; else $i=1;
		$sql .= " cat_id = ".$cat;
	}
	if($chanel->subcat_id!=$subcat){
		if($i) $sql .=" ,"; else $i=1;
		$sql .= " subcat_id = ".$subcat;
	}
	if(empty($chanel->city_id)&&!empty($city)||
	!empty($chanel->city_id)&&!empty($city)&&$chanel->city_id!=$city){
		if($i) $sql .=" ,"; else $i=1;
		$sql.= " city_id = ".$city;
	}
	if(empty($chanel->joinchat_url)&&!empty($joinchat)||
	!empty($chanel->joinchat_url)&&!empty($joinchat)&&$chanel->joinchat_url!=$joinchat){
		if($i) $sql .=" ,"; else $i=1;
		$sql.= " joinchat_url = '".$joinchat."'";
	}else if(!empty($chanel->joinchat_url)&&empty($joinchat)){
		if($i) $sql .=" ,"; else $i=1;;
		$sql.= " joinchat_url = NULL";
	}
	if(empty($chanel->description)&&!empty($description)||
	!empty($chanel->description)&&(!empty($description)&&$chanel->joinchat_url!=$description)){
		if($i) $sql .=" ,"; else $i=1;
		$sql.= " description = '".$description."'";
	}else if(!empty($chanel->description)&&empty($description)){
		if($i) $sql .=" ,"; else $i=1;
		$sql.= " description = NULL";
	}
	
	$sql .= " WHERE id = ".$chanel->id;
	$wpdb->query($sql);
	$i = 0;
	
	
	$sql = "UPDATE wp_posts SET";
	
	if($chanel->post_content!=$chanel_id){
		$sql .= " post_content = '".$chanel_id."'";
		$i=1;
	}
	if($chanel->post_title!=$title){
		if($i) $sql .=","; else $i=1;
		$sql .= " post_title = '".$title."'";
	}
	if($chanel->post_name!=$chanel_id){
		if($i) $sql .=","; else $i=1;
		$sql .= " post_name = '".substr($chanel_id,1)."'";
	}
	if($chanel->guid!=get_option('siteurl') .'/'.substr($chanel_id,1).'/'){
		if($i) $sql .=","; else $i=1;
		$sql .= " guid = '".get_option('siteurl') .'/'.$chanel_id."/'";
	}

	$sql .= " WHERE ID = ".$chanel->post_id;
	$wpdb->query($sql);
	


	if(empty($chanel->hashtags)&&!empty($hashtags)||
	!empty($chanel->hashtags)&&!empty($hashtags)&&$chanel->hashtags!=$hashtags){
		$wpdb->delete('chanels_hashtags',array('chanel_ditail_id' => $chanel->id));	
		$hashtags = explode(',',$hashtags);
		for($i = 0;$i<count($hashtags);$i++){
			$wpdb->insert('chanels_hashtags', array(
				'chanel_ditail_id' => $chanel->id,
				'hashtag_id' => $hashtags[$i]
			),array('%d','%d'));
		}
	}else if(!empty($chanel->hashtags)&&empty($hashtags)){
		$wpdb->delete('chanels_hashtags',array('chanel_ditail_id' => $chanel->id));	
	}

	if($chanel->chanel_id!=$chanel_id){
		rename(wp_get_upload_dir()['basedir']."/img/".$chanel->chanel_id.".jpg",
			wp_get_upload_dir()['basedir']."/img/".$chanel_id.".jpg");
	}
}
function delete_chanel($chanel_id){
	global $wpdb;
	$postid = $wpdb->get_var("SELECT ID FROM `wp_posts` WHERE `post_content` = '".$chanel_id."' LIMIT 1");
	wp_delete_post($postid,true);
	$chanel_detail_id = $wpdb->get_var("SELECT id FROM `chanels_ditails` WHERE `chanel_id` = '".$chanel_id."'");
	$wpdb->delete('chanels_ditails',array('chanel_id' => $chanel_id));
	$wpdb->delete('wp_post_views',array('chanel_ditails_id' => $chanel_detail_id));
	$wpdb->delete('wp_post_rates',array('chanel_ditails_id' => $chanel_detail_id));	
	$wpdb->delete('chanels_hashtags',array('chanel_ditails_id' => $chanel_detail_id));	
	//delete image
	unlink(wp_upload_dir()['basedir'].'/img/'.$chanel_id.'.jpg');
	DeleteEvents($chanel_detail_id);
	if(!empty($wpdb->get_var("SELECT id FROM `storys` WHERE `chanel_ditails_id` = '".$chanel_detail_id."' LIMIT 1"))){
		deleteStory($chanel_detail_id,$chanel_id);
	}
}
function is_chanel_exists($chanel_id){
	global $wpdb;
	return (!empty($wpdb->get_var("SELECT * FROM `wp_posts` WHERE `post_content` LIKE '".$chanel_id."%' LIMIT 1"))) ? true : false;
}
function get_chanel($chanel_id,$post_status="publish"){
	global $wpdb;
	$sql = "SELECT chanels_ditails.*,
	wp_posts.id AS post_id,
	wp_posts.post_title,
	wp_posts.post_status,
	wp_posts.post_author,
	wp_posts.guid,
	storys.story_duration,
	storys.story_time,
	storys.story_link,
	hashtags 
	FROM chanels_ditails 
	INNER JOIN wp_posts 
	ON chanels_ditails.chanel_id = wp_posts.post_content 
	LEFT JOIN (SELECT GROUP_CONCAT(DISTINCT hashtag_id SEPARATOR ',') AS hashtags,chanel_ditail_id 
	FROM chanels_hashtags GROUP BY chanel_ditail_id) AS chanel_hashtags 
	ON chanels_ditails.id = chanel_hashtags.chanel_ditail_id
	LEFT JOIN storys ON storys.chanel_ditails_id = chanels_ditails.id
	WHERE chanel_id = '".$chanel_id."' AND wp_posts.post_status = '".$post_status."' LIMIT 1";
	return $wpdb->get_row($sql, OBJECT);
}
function get_chanels($order = 0,$post_status = "all",$limit = null,$type = 0,$author=null,$cat = null,$subcat = null,$social_network = null,$city = null,$hashtags = null,$just_event = 0){
	global $wpdb;
	
	$sql = 'SELECT chanels_ditails.*,
	wp_posts.id AS post_id,
	wp_posts.post_title,
	wp_posts.post_date,
	wp_posts.post_status,
	wp_posts.post_author,
	wp_posts.guid,
	storys.story_duration,
	storys.story_time,
	storys.story_link
	FROM chanels_ditails
		INNER JOIN wp_posts ON chanels_ditails.chanel_id = wp_posts.post_content
		LEFT JOIN storys ON storys.chanel_ditails_id = chanels_ditails.id';

	if($just_event){$sql .= ' INNER JOIN `events` ON events.chanel_id = chanels_ditails.id';}
		
	if(!empty($hashtags)){
		$sql .= ' INNER JOIN
			(SELECT DISTINCT chanels_hashtags.chanel_ditail_id FROM chanels_hashtags
			WHERE chanels_hashtags.hashtag_id IN ('.$hashtags.')) AS hashtags
		ON chanels_ditails.id = hashtags.chanel_ditail_id';
	}
	
	if(!empty($author)){
		$sql .= " WHERE wp_posts.post_author = ".$author." AND";
	}else{
		$sql .= " WHERE";
	}

	if($post_status != "all"){
		$sql .= ' wp_posts.post_status = "'.$post_status.'"';
	}else{
		$sql .= ' (wp_posts.post_status = "publish" OR wp_posts.post_status = "draft")';
	}
	
	if(!empty($cat)){
		$sql .= " AND cat_id = ".$cat;
	}
	if(!empty($subcat)){
		$sql .= " AND subcat_id = ".$subcat;
	}
	if(!empty($social_network)){
		$sql .= " AND social_network_id = ".$social_network;
	}
	if(!empty($city)){
		$sql .= " AND city_id = ".$city;
	}


	$normalSql = $spicalSql = $sql;


	$normalSql .= " AND vip_date < ".time();
	switch($order){
		case 0:
			$normalSql .= " ORDER BY `post_date`";
			break;
		case 1:
			$normalSql .= " ORDER BY `rate` DESC";
			break;
		case 2:
			$normalSql .= " ORDER BY `views` DESC";
			break;
	}
	if(!empty($limit)) $normalSql.=" LIMIT ".$limit;
	

	$spicalSql .= " AND vip_date > ".time();

	if(!empty($limit)) $spicalSql.=" LIMIT ".$limit;


	switch ($type) {
		case 0:
			$normal = $wpdb->get_results($normalSql, OBJECT);
			$spical = $wpdb->get_results($spicalSql, OBJECT);
			$results = array_merge($spical, $normal);
			$results = array_map('json_encode', $results);
			$results = array_unique($results);
			$results = array_map('json_decode', $results);
			return $results;
			break;
		case 1:
			$spical = $wpdb->get_results($spicalSql, OBJECT);
			return $spical;
			break;
		case 2:
			$normal = $wpdb->get_results($normalSql, OBJECT);
			return $normal;
			break;
	}

}

function publish_channel($post_ID){
	wp_update_post(array(
        'ID'    =>  $post_ID,
        'post_status'   =>  'publish'
    ));
}

function getVIP($chanel_id,$vip_offer_id){
	global $wpdb;
	$vip_offer = $wpdb->get_row("SELECT * FROM offers WHERE id = ".$vip_offer_id." AND type = 'vip'", OBJECT);
	$type = 0;
	if($vip_offer->time_type=="day"){$type = DAY_IN_SECONDS;}
	else if($vip_offer->time_type=="month"){$type = MONTH_IN_SECONDS;}
	else if($vip_offer->time_type=="year"){$type = YEAR_IN_SECONDS;}
	
	$vip_date = time()+($type*$vip_offer->count);

	$wpdb->query("UPDATE chanels_ditails SET vip_date=".$vip_date." WHERE chanel_id = '".$chanel_id."'");
}
function upgrade($chanel_id){
	global $wpdb;
	$wpdb->query("UPDATE chanels_ditails SET upgrade=1 WHERE chanel_id = '".$chanel_id."'");
}


function get_chanel_detail_id($chanel_id){
	global $wpdb;
	return $wpdb->get_var("SELECT id FROM `chanels_ditails` WHERE `chanel_id` = '".$chanel_id."'");;
}
function get_chanel_id($id){
	global $wpdb;
	return $wpdb->get_var("SELECT chanel_id FROM `chanels_ditails` WHERE `id` = ".$id);;
}

function set_post_rate($id,$rate,$user){
	global $wpdb;
	if(!empty($wpdb->get_var("SELECT id FROM `wp_post_rates` WHERE `user` = ".$user." AND chanel_ditails_id = ".$id))){
		$wpdb->query("UPDATE wp_post_rates SET rate='".$rate."' WHERE user='".$user."' AND chanel_ditails_id = ".$id);
	}else{
		$wpdb->query("INSERT INTO `wp_post_rates`(`chanel_ditails_id`, `user`, `rate`) VALUES ('".$id."','".$user."','".$rate."')");
	}
}
function update_rate($id){
	global $wpdb;
	$sql = "SELECT `rate` FROM `wp_post_rates` WHERE `chanel_ditails_id` = ".$id;
	$rates = $wpdb->get_results($sql, ARRAY_A);
	$rates_sum = 0;
	$rates_count = 0;
	foreach($rates as $val){
		$rates_sum += $val["rate"];
		$rates_count += 1;
	}
	$rate = $rates_sum / $rates_count;
	$wpdb->query("UPDATE chanels_ditails SET rate='".$rate."' WHERE  id = ".$id);
}

function post_rate($rate = 0){
	echo '<div class="star-box">';
		$emptyStarCount = 0;
		while($rate>0){
			if($rate>=1){
				echo '<div><i class="fas fa-star checked"></i></div>';
			}else{
				echo '<div class="half"><i class="fas fa-star checked"></i><i class="fas fa-star"></i></div>';
			}
			$rate--;
			$emptyStarCount++;
		}
		$emptyStarCount = 5 - $emptyStarCount;
		while($emptyStarCount>0){
			echo '<div><i class="fas fa-star"></i></div>';
			$emptyStarCount--;
		}
	echo '</div>';
}
function set_post_views($id,$user){
	global $wpdb;
	if(empty($wpdb->get_var("SELECT id FROM `wp_post_views` WHERE `user` = ".$user." AND chanel_ditails_id = ".$id))){
		$wpdb->query("INSERT INTO `wp_post_views`(`chanel_ditails_id`, `user`) VALUES ('".$id."','".$user."')");
	}
}
function update_views($id){
	global $wpdb;
	$views = (int) $wpdb->get_var("SELECT COUNT(*) FROM `wp_post_views` WHERE `chanel_ditails_id` = '".$id."'");
	if($views>$wpdb->get_var("SELECT `views` FROM `chanels_ditails` WHERE `id` = ".$id)){
		$wpdb->query("UPDATE chanels_ditails SET views='".$views."' WHERE id = ".$id);
	}
}



function addStory($id,$duration,$story_time,$story_link){
	global $wpdb;
	if(!empty($wpdb->get_var("SELECT id FROM storys WHERE chanel_ditails_id = ".$id))){
		$wpdb->query("UPDATE storys SET story_duration=".$duration.",story_time = ".$story_time." , story_link = '".$story_link."'  WHERE chanel_ditails_id = '".$id."'");
	}else{
		$wpdb->insert('storys', array(
			'story_duration' => $duration,
			'story_time' => $story_time,
			'chanel_ditails_id' => $id,
			'story_link' => $story_link,
		),array('%d','%d','%s','%'));
	}
}
function deleteStory($id,$chanel_id){
	global $wpdb;
	$wpdb->query("DELETE FROM `storys` WHERE `chanel_ditails_id` = ".$id);
	unlink(wp_upload_dir()['basedir'].'/story/'.$chanel_id.'.jpg');
}


function AddEvent($title,$text,$link,$chanel_id){
	global $wpdb;
	$wpdb->insert('events', array(
		'chanel_id' => $chanel_id,
		'title' =>$title,
		'text' =>$text,
		'link' =>$link
	),array('%d','%s','%s','%s'));
	return $wpdb->insert_id;
}
function EditEvent($id,$title,$text,$link){
	global $wpdb;
	$wpdb->query('UPDATE events SET title="'.$title.'",text = "'.$text.'",link = "'.$link.'" WHERE id = '.$id);
}
function DeleteEvent($id,$chanel_id){
	global $wpdb;
	$wpdb->delete('events',array('id' => $id));
	unlink(wp_get_upload_dir()['basedir']."/events/".$chanel_id."_".$id.".jpg");
}
function DeleteEvents($chanel_id){
	global $wpdb;
	$events = get_events($chanel_id);
	foreach ($events as $e => $event){unlink(wp_get_upload_dir()['basedir']."/events/".$event->chanel_id."_".$event->id.".jpg");}
	$wpdb->delete('events',array('chanel_id' => $chanel_id));
}
function get_events($id){
	global $wpdb;
	return $wpdb->get_results("SELECT * FROM `events` WHERE `chanel_id` = ".$id, OBJECT);		
}
function get_event($event_id){
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM `events` WHERE `id` = ".$event_id);	
}






function get_cat_by_id($id){
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM `cat` WHERE id = ".$id);
}
function get_list_of_cat(){
	global $wpdb;
	$sql = "SELECT * FROM `cat`";
	$subcat = $wpdb->get_results($sql, ARRAY_A);
	return $subcat;
}
function add_cat($name,$city_filter_enable){
	global $wpdb;
	$wpdb->insert('cat', array(
		'name' => $name,
		'city_filter_enable' =>$city_filter_enable
	),array('%s','%d'));
	return $wpdb->insert_id;
}
function edit_cat($id,$name,$city_filter_enable){
	global $wpdb;
	$wpdb->query('UPDATE cat SET name="'.$name.'" , city_filter_enable="'.$city_filter_enable.'"  WHERE id = '.$id);
}
function delete_cat($id){
	global $wpdb;
	$wpdb->delete('cat',array('id' => $id));
	$subcats = get_subcats_by_cat_id($id);
	foreach($subcats as $key){delete_subcat($key['id']);}
}





function get_subcat_by_id($id){
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM `subcat` WHERE id = ".$id." LIMIT 1");;
}
function get_subcat_cat($id){
	global $wpdb;
	return get_cat_by_id($wpdb->get_var("SELECT `cat_id` FROM `subcat` WHERE `id` = ".$id." LIMIT 1"));
}
function get_subcats_by_cat_id($cat_id){
	global $wpdb;
	$sql = "SELECT id,name FROM `subcat` WHERE cat_id = ".$cat_id;
	$subcat = $wpdb->get_results($sql, ARRAY_A);
	return $subcat;
}
function get_list_of_subcats(){
	global $wpdb;
	$sql = "SELECT id,city_filter_enable FROM `cat`";
	$cat = $wpdb->get_results($sql, ARRAY_A);
	$list_subcats = array();
	foreach($cat as $id){
		$list_subcats[$id["id"]] = [get_subcats_by_cat_id($id["id"]),$id["city_filter_enable"]];
	}
	return $list_subcats;
}
function get_subcats_count(){
	global $wpdb;
	return $wpdb->get_var("SELECT COUNT(*) FROM `subcat`");;
}
function add_subcat($name,$cat){
	global $wpdb;
	$wpdb->insert('subcat', array(
		'name' => $name,
		'cat_id' =>$cat
	),array('%s','%d'));
	return $wpdb->insert_id;
}
function edit_subcat($name,$id){
	global $wpdb;
	$wpdb->query('UPDATE subcat SET name="'.$name.'" WHERE id = '.$id);
}
function delete_subcat($id){
	global $wpdb;
	$wpdb->delete('subcat',array('id' => $id));
	delete_hashtags_by_subcat_id($id);
}







function get_social_network_by_id($id){
	global $wpdb;
	return $wpdb->get_var("SELECT `name` FROM `social_networks` WHERE `id` = ".$id." LIMIT 1");
}
function get_list_of_social_network(){
	global $wpdb;
	return $wpdb->get_results("SELECT `id`,`name` FROM `social_networks`", ARRAY_A);
}
function add_social_network($name){
	global $wpdb;
	$wpdb->insert('social_networks', array('name' => $name),array('%s'));
	return $wpdb->insert_id;
}
function edit_social_network($name,$id){
	global $wpdb;
	$wpdb->query('UPDATE social_networks SET name="'.$name.'" WHERE id = '.$id);	
}
function delete_social_network($id){
	global $wpdb;
	$wpdb->delete('social_networks',array('id' => $id));
}



function get_hashtags_by_subcat_id($subcat_id){
	global $wpdb;
	$sql = "SELECT `id`, `name` FROM `hashtags` WHERE subcat_id = ".$subcat_id;
	$hastags = $wpdb->get_results($sql, ARRAY_A);
	return $hastags;
}
function get_hashtag_by_id($hashtag_id){
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM `hashtags` WHERE id = ".$hashtag_id);
}
function get_hashtags(){
	global $wpdb;
	$sql = "SELECT DISTINCT subcat_id from hashtags";
	$subcat_id = $wpdb->get_results($sql, ARRAY_A);
	$hashtags = array();
	foreach($subcat_id as $id){
		$hashtags[$id["subcat_id"]] = get_hashtags_by_subcat_id($id["subcat_id"]);
	}
	return $hashtags;
}
function add_hashtag($name,$subcat_id){
	global $wpdb;
	$wpdb->insert('hashtags', array('name' => $name,'subcat_id'=>$subcat_id),array('%s','%d'));
	return $wpdb->insert_id;
}
function edit_hashtag($name,$id){
	global $wpdb;
	$wpdb->query('UPDATE hashtags SET name="'.$name.'" WHERE id = '.$id);
}
function delete_hashtag($id){
	global $wpdb;
	$wpdb->delete('hashtags',array('id' => $id));
}
function delete_hashtags_by_subcat_id($id){
	global $wpdb;
	$wpdb->delete('hashtags',array('subcat_id' => $id));
}




function get_cities(){
	global $wpdb;
	return $wpdb->get_results("SELECT * FROM `city` order by name", ARRAY_A);;
}
function get_city($id){
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM `city` WHERE id = ".$id);;
}
function add_city($name){
	global $wpdb;
	$wpdb->insert('city', array('name' => $name),array('%s'));
	return $wpdb->insert_id;
}
function edit_city($name,$id){
	global $wpdb;
	$wpdb->query('UPDATE city SET name="'.$name.'" WHERE id = '.$id);
}
function delete_city($id){
	global $wpdb;
	$wpdb->delete('city',array('id' => $id));
}





function get_vip_offers(){
	global $wpdb;
	return $wpdb->get_results("SELECT * FROM offers WHERE type = 'vip'", OBJECT);
}
function get_vip_offer($id){
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM offers WHERE type = 'vip' AND id = ".$id);	
}
function add_vip_offer($count,$time_type,$price,$currency){
	global $wpdb;
	$wpdb->insert('offers',
		array(
			'count' => $count,
			'time_type' => $time_type,
			'price' => $price,
			'currency' => $currency,
			'type' => 'vip'
	),array('%d','%s','%d','%s','%s'));
	return $wpdb->insert_id;
}
function edit_vip_offer($id,$count,$time_type,$price,$currency){
	global $wpdb;
	$wpdb->query('UPDATE offers SET 
		count='.$count.',
		time_type="'.$time_type.'",
		price='.$price.',
		currency="'.$currency.'"
		WHERE id = '.$id.' AND type="vip"');
}
function delete_vip_offer($id){
	global $wpdb;
	$wpdb->delete('offers',array('id' => $id,'type'=>'vip'));
}



function get_upgrade_offer(){
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM offers WHERE type = 'upgrade'");	
}
function edit_upgrade_offer($price,$currency){
	global $wpdb;
	$wpdb->query('UPDATE offers SET
		price='.$price.',
		currency="'.$currency.'"
		WHERE type="upgrade"');
}


function get_offer($id){
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM offers WHERE id = ".$id);	
}



function register($email,$username,$password){
	$WP_array = array (
		'user_login'    =>  $username,
		'user_email'    =>  $email,
		'user_pass'     =>  $password
	);
	$id = wp_insert_user($WP_array) ;
	if( is_wp_error($id) ) {
		return false;
	}else{
		wp_update_user(array('ID'=>$id,'role'=>'subscriber')) ;
		return true;
	}
}
function log_in($user,$pass){
	$creds = array();
	$creds['user_login'] = $user;
	$creds['user_password'] = $pass;
	$creds['remember'] = true;
	$user = wp_signon( $creds, false );
	if (is_wp_error($user)) {
		return $user->get_error_code();
	}else if(!is_wp_error($user)) {
		wp_set_current_user($user->ID);
		wp_set_auth_cookie($user->ID);
		return;
	}
}









function SearchInWpPost($srch){
	global $wpdb;
	return $wpdb->get_results('SELECT post_content AS chanel_id,post_date FROM `wp_posts`
	 WHERE (post_content LIKE "%'.$srch.'%" OR post_title LIKE "%'.$srch.'%") AND post_type = "post"', OBJECT);	
}
function SearchInChanelsDitails($srch){
	global $wpdb;
	return $wpdb->get_results('SELECT chanel_id,post_date FROM `chanels_ditails` INNER JOIN wp_posts ON chanels_ditails.chanel_id = wp_posts.post_content
		WHERE chanel_id LIKE "%'.$srch.'%" OR description LIKE "%'.$srch.'%"', OBJECT);	
}
function SearchInHashtags($srch){
	global $wpdb;
	$hashtags = $wpdb->get_results('SELECT id FROM `hashtags` WHERE name LIKE "%'.$srch.'%"', OBJECT);	
	if(!empty($hashtags)){
		foreach ($hashtags as $hash => $hashtag){$h .= '"'.$hashtag->id.'",';}
		return $wpdb->get_results('SELECT `chanel_id`,post_date FROM `chanels_ditails`
			INNER JOIN wp_posts ON chanels_ditails.chanel_id = wp_posts.post_content
			INNER JOIN
				(SELECT DISTINCT chanels_hashtags.chanel_ditail_id FROM chanels_hashtags
				WHERE chanels_hashtags.hashtag_id IN ('.substr($h,0,-1).')) AS hashtags
			ON chanels_ditails.id = hashtags.chanel_ditail_id',
 			OBJECT);
	}else{return;}
}
function SearchInEvents($srch){
	global $wpdb;
	return $wpdb->get_results('SELECT `chanel_id`,post_date FROM `chanels_ditails`
		INNER JOIN wp_posts ON chanels_ditails.chanel_id = wp_posts.post_content
		INNER JOIN
			(SELECT `chanel_id` AS id FROM `events` WHERE title LIKE "%'.$srch.'%" OR text LIKE "%'.$srch.'%") AS events
		ON chanels_ditails.id = events.id',
		OBJECT);
}
function SearchInCat($srch){
	global $wpdb;
	return $wpdb->get_row('SELECT * FROM `cat` WHERE name LIKE "'.$srch.'"');
}
function SearchInSubCat($srch){
	global $wpdb;
	return $wpdb->get_row('SELECT * FROM `subcat` WHERE name LIKE "'.$srch.'"');
}
function SearchInSocialNetworks($srch){
	global $wpdb;
	return $wpdb->get_row('SELECT * FROM `social_networks` WHERE name LIKE "'.$srch.'"');
}