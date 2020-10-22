<?php /* Template Name: user-panel */ if (session_status() == PHP_SESSION_NONE) session_start(); 
if(is_user_logged_in()){ 
	$user = wp_get_current_user();
	get_header();
?>
<div id="article">
	<div id="continer">

		<?php 
			if(!empty($_GET['action'])){
				switch ($_GET['action']) {
					case 'add':
						switch ($_GET['add']) {
							case 'event':
								$chanel = get_chanel($_GET['chanel_id']);
								if($chanel->post_author==$user->ID||current_user_can('administrator')){
									$events = get_events($chanel->id);
									if($chanel->upgrade){
										if(count($events)<4){
											if($_SERVER['REQUEST_METHOD'] === 'POST'){
												$everyThingOK = 1;

												$event_title = $wpdb->escape(trim($_POST['event_title']));
												$event_text = $wpdb->escape(trim($_POST['event_text']));
												$event_link = $wpdb->escape(trim($_POST['event_link']));

												if(!isset($_FILES['event_img']) || !$_FILES['event_img']['error'] == UPLOAD_ERR_NO_FILE) {
													$eventImageError = upload_img($_FILES['event_img'],$chanel->id,0,"/events/");
													if(!empty($eventImageError)) $everyThingOK = 0;
												}else{
													$eventImageError = "لطفا عکس را انتخاب کنید";
													$everyThingOK = 0;
												}
												if(empty($event_title)){
													$everyThingOK = 0;
													$eventTitleError = "لطفا عنوان را وارد کنید";
												}
												if(!check_url($event_link)){
													$event_link = null;
												}
												if($everyThingOK){
													$insertID = AddEvent($event_title,$event_text,$event_link,$chanel->id);
													rename(wp_get_upload_dir()['basedir']."/events/".$chanel->id.".jpg",
														wp_get_upload_dir()['basedir']."/events/".$chanel->id."_".$insertID.".jpg");
													wp_redirect(home_url('user-panel/?').EditGETQuery(array(),array('action'=>'get','get'=>'events','chanel_id'=>$chanel->chanel_id)));exit;
												}else{
													if(file_exists(wp_get_upload_dir()['basedir']."/events/".$chanel->id.".jpg"))
														unlink(wp_get_upload_dir()['basedir']."/events/".$chanel->id.".jpg");
												}
											}
											?>
											<div class="catview">
												<div>
													<h2><?php echo 'اضافه کردن  پست برای کانال  '."'".$chanel->post_title."'"; ?></h2>
												</div>
												<hr>
											</div>
											<div class="form">
												<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
													<table>
														<tr>
															<td>
																<span class="important">*</span>
																انتخاب عکس :
															</td>
															<td>
															  <label for="file-upload" class="custom-file-upload btn btn-black">
																<i class="fas fa-cloud-upload-alt"></i> انتخاب عکس
															  </label>
															  <input id="file-upload" type="file" style="display:none;" accept="image/png, image/jpeg" name="event_img">
															  <?php if(!empty($eventImageError)) echo '<span class="error">'.$eventImageError.'</span>'; ?>
															</td>
														</tr>
														<tr>
															<td><span class="important">*</span> عنوان :</td>
															<td>
																<div class="inputbox">
																	<input type="text" class="inputbox-input" placeholder="عنوان" name="event_title">
																	<div class="inputbox-hr"><hr></div>
																</div>
																<?php if(!empty($eventTitleError)) echo '<span class="error">'.$eventTitleError.'</span>'; ?>
															</td>
														</tr>
														<tr>
															<td>متن :</td>
															<td>
																<div class="inputbox">
																	<textarea style="max-width: 250px;max-height: 200px;" class="inputbox-input" name="event_text"></textarea>
																	<div class="inputbox-hr"><hr></div>
																</div>
															</td>
														</tr>
														<tr>
															<td>لینک :</td>
															<td>
																<div class="inputbox">
																	<input type="text" class="inputbox-input" placeholder="لینک" name="event_link">
																	<div class="inputbox-hr"><hr></div>
																</div>
															</td>
														</tr>
														<tr>
															<td>
																<input class="btn btn-blue" type="submit" value="اپلود">
															</td>
														</tr>
													</table>
												</form>
											</div>
											<?php
										}else{wp_redirect(home_url('user-panel/?').EditGETQuery(array(),array('action'=>'get','get'=>'events','chanel_id'=>$chanel->chanel_id)));exit;}
									}else{wp_redirect(home_url('user-panel'));exit;}
								}else{wp_redirect(home_url('user-panel')); exit;}
								break;
							case 'story':
								$chanel = get_chanel($_GET['chanel_id']);
								if($chanel->post_author==$user->ID||current_user_can('administrator')){
									if($chanel->vip_date>time()){
										$storyImageError = null;
										if($_SERVER['REQUEST_METHOD'] === 'POST'){
											$story_duration = $wpdb->escape(trim($_POST['story_duration']));
											$story_time = $wpdb->escape(trim($_POST['story_time']));
											$story_link = $wpdb->escape(trim($_POST['story_link']));
											
											$storyImageError = upload_img($_FILES['story_img'],$_GET['chanel_id'],0,"/story/");
											if(empty($storyImageError)){
												addStory($chanel->id,$story_duration,time()+($story_time*DAY_IN_SECONDS),$story_link);
												wp_redirect(home_url('user-panel'));
												exit;
											}
										}
										?>
										<div class="catview">
											<div>
												<h2>ثبت استوری برای کانال <?php echo "'".$chanel->post_title."'"; ?></h2>
											</div>
											<hr>
										</div>
										<div class="form">
											<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
												<table>
													<tr>
														<td>
															انتخاب عکس :
														</td>
														<td>
														  <label for="file-upload" class="custom-file-upload btn btn-black">
															<i class="fas fa-cloud-upload-alt"></i> انتخاب عکس
														  </label>
														  <input id="file-upload" type="file" style="display:none;" accept="image/png, image/jpeg" name="story_img">
														  <?php if(!empty($storyImageError)) echo '<span class="error">'.$storyImageError.'</span>'; ?>
														</td>
													</tr>
													<tr>
														<td>لینک :</td>
														<td>
															<div class="inputbox">
																<input type="text" class="inputbox-input" placeholder="لینک" name="story_link">
																<div class="inputbox-hr"><hr></div>
															</div>
														</td>
													</tr>
													<tr>
														<td>مدت زمان استوری ( ثانیه ) :</td>
														<td><input type="number" name="story_duration" class="form-number" value="10" min="10" max="30"></td>
													</tr>
													<tr>
														<td>مدت نمایش استوری ( روز ) :</td>
														<td><input type="number" name="story_time" class="form-number" value="1" min="1" max="5"></td>
													</tr>
													<tr>
														<td>
															<input class="btn btn-blue" type="submit" value="اپلود">
														</td>
													</tr>
												</table>
											</form>
										</div>
										<?php
									}else{wp_redirect(home_url('user-panel')); exit;}
								}else{wp_redirect(home_url('user-panel')); exit;}
								break;
						}
						break;
					case 'edit':
						switch ($_GET['edit']) {
							case 'chanel':
								$chanel = get_chanel($_GET['chanel_id']);
								if($chanel->post_author==$user->ID||current_user_can('administrator')){	
									if($_SERVER['REQUEST_METHOD'] === 'POST'){		 
										$sn = null;
										$cat = null;
										$subcat = null;
										$hashtags = null;
										$city = null;
										$title = null;
										$chanel_id = null;
										$chanel_idError = null;
										$joinchat_url = null;
										$description = null;
										$everyThingOK = 1;

										if(!empty($_POST["social_network"])){$sn = (int) $_POST["social_network"];}
										if(!empty($_POST["cat"])){$cat = $_POST["cat"];}
										if(!empty($_POST["subcat"])) $subcat = $_POST["subcat"];
										if(!empty($_POST["title"])) $title = $_POST["title"];
										if(!empty($subcat)&&count(get_hashtags_by_subcat_id($subcat))>0){
											if(!empty($_POST["hashtags"])) $hashtags = $_POST["hashtags"];
										}
										if(!empty($cat)&&!get_cat_by_id($cat)->city_filter_enable){
											if(!empty($_POST["city"])) $city = $_POST["city"];
										}
										if (!preg_match("/[^A-Za-z0-9\@\_]/", $_POST["chanel_id"])){
											$chanel_id = ($_POST["chanel_id"][0]!="@") ? "@".$_POST["chanel_id"] : $_POST["chanel_id"];
										}else{
											$chanel_idError = "لطفا ایدی را درست وارد کنید";
											$everyThingOK = 0;
										}
										if(!empty($_POST["joinchat_url"])){
											if(check_url($_POST["joinchat_url"])){
												$joinchat_url = $_POST["joinchat_url"];
											}else{
												#get link from id
											}
										}
										if(!empty($_POST["description"])) $description = trim($_POST["description"]);
										
										
										if(empty($sn)){$sn = $chanel->social_network_id;}
										if(empty($cat)){$cat = $chanel->cat_id;}
										if(empty($subcat)){$subcat = $chanel->subcat_id;}
										if(empty($city)){$city = $chanel->city_id;}
										if(empty($chanel_id)){$chanel_id = $chanel->chanel_id;}


										if(!isset($_FILES['chanel_img']) || !$_FILES['chanel_img']['error'] == UPLOAD_ERR_NO_FILE) {
											$imageError = upload_img($_FILES['chanel_img'],$chanel->chanel_id,1,"/img/");
											if(!empty($imageError)) $everyThingOK = 0;
										}
										if($everyThingOK){
											edit_chanel($chanel,$chanel_id,$title,$sn,$cat,$subcat,$joinchat_url,$description,$hashtags,$city);
											if(!empty($_GET['redirect_url'])){
												$_SESSION["afected"]=array('afected'=>'edited','edited'=>'chanel','chanel_id'=>$chanel->chanel_id);
												wp_redirect($_GET['redirect_url']);
											}
											else{wp_redirect(home_url('user-panel'));
										}
										exit;
									}
								} ?>
									<div class="catview">
										<div>
											<h2>ویرایش کانال <?php echo '"'.$chanel->post_title.'"'; ?></h2>
										</div>
										<hr>
									</div>
									<div class="form">
										<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
											<table>
												<tr>
													<td>
														انتخاب شبکه اجتماعی :
													</td>
													<td>
														<div id="form_social_network_select" class="form-chosen">
														  <select data-placeholder="انتخاب" class="select2">
															<option></option>
															  <?php
																  $social_network_list = get_list_of_social_network();
																  foreach ($social_network_list as $row){
																	  if($row["id"]==$chanel->social_network_id){
																		echo '<option selected value="'.$row["id"].'">'.$row["name"].'</option>';
																	  }else{
																		echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
																	  }
																  }
															  ?>
														  </select>
														  <?php echo '<input type="text" style="display:none;" value="'.$chanel->social_network_id.'" name="social_network" class="input"/>'; ?>
														</div>
													</td>
												</tr>
												<tr>
													<td>
														انتخاب دسته بندی :
													</td>
													<td>
														<div id="form_category_select" class="form-chosen">
														  <select data-placeholder="انتخاب" class="select2">
															<option></option>
															  <?php
															  $cat_list = get_list_of_cat();
															  foreach ($cat_list as $row){
																if($row["id"]==$chanel->cat_id){
																	echo '<option value="'.$row["id"].'" selected>'.$row["name"].'</option>';
																}else{
																	echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
																}
															  }
															  ?>
														  </select>
														  <input type="text" style="display:none;" <?php echo 'value="'.$chanel->cat_id.'"' ?>  name="cat" class="input"/>
														</div>
													</td>
												</tr>
												<tr>
													<td>
														انتخاب زیر دسته :
													</td>
													<td>
														<div id="form_category_children_select" class="form-chosen">
														  <select data-placeholder="انتخاب" class="select2">
															<option></option>
															<?php
																foreach (get_subcats_by_cat_id($chanel->cat_id) as $row){
																	if($row["id"]==$chanel->subcat_id){
																		echo '<option value="'.$row["id"].'" selected>'.$row["name"].'</option>';
																	}else{
																		echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
																	}
																}
															?>
														  </select>
														  <input type="text" style="display:none;" <?php echo 'value="'.$chanel->subcat_id.'"' ?> name="subcat" class="input"/>
														</div>
													</td>
												</tr>
												<tr <?php if(count(get_hashtags_by_subcat_id($chanel->subcat_id))==0) echo 'style="display:none"'; ?>>
													<td>انتخاب هشتگ :</td>
													<td>
														<div id="form_hashtag_select" class="form-chosen">
															<select data-placeholder="هشتگ" multiple class="select2">
																<option></option>
																<?php
																	$hashtags = get_hashtags_by_subcat_id($chanel->subcat_id);
																	$chanel_hashtags = explode(',',$chanel->hashtags);
																	foreach ($hashtags as $row){
																		$hashtag_selected_id = 0;
																		for($i=0;$i<=count($chanel_hashtags)-1;$i++){
																			if($row["id"]==$chanel_hashtags[$i]){
																				$hashtag_selected_id = $chanel_hashtags[$i];
																			}
																		}
																		if($row["id"]==$hashtag_selected_id){
																			echo '<option value="'.$row["id"].'" selected="selected">'.$row["name"].'</option>';
																		}else{
																			echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
																		}
																	}
																?>
															</select>
															<input type="hidden" <?php  if(!empty($chanel->hashtags)) echo 'value="'.$chanel->hashtags.'"' ?> name="hashtags" class="input"/>
														</div>
													</td>
												</tr>
												<tr <?php if(!get_cat_by_id($chanel->cat_id)->city_filter_enable) echo 'style="display:none;"'; ?>>
													<td>انتخاب شهر :</td>
													<td>
														<div id="form_select_city" class="form-chosen">
														  <select class="select2" data-placeholder="انتخاب">
															<option></option>
															<?php
																$cities = get_cities();
																foreach ($cities as $row){
																  if($row["id"]==$chanel->city_id){
																	echo '<option selected value="'.$row["id"].'">'.$row["name"].'</option>';
																  }else{
																	echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
																  }
																}
															?>
														  </select>
														  <input type="text" style="display:none;" name="city" class="input"/>
														</div>
													</td>
												</tr>
												<tr>
													<td>
														عنوان :
													</td>
													<td>
														<div class="inputbox">
															<input type="text" class="inputbox-input" <?php echo 'value="'.$chanel->post_title.'"' ?> placeholder="عنوان" name="title">
															<div class="inputbox-hr"><hr></div>
														</div>
													</td>
												</tr>
												<tr>
													<td>
														ایدی (یوزرنیم) :
													</td>
													<td>
														<div class="inputbox" style="direction: ltr;">
															<input type="text" class="inputbox-input" <?php echo 'value="'.$chanel->chanel_id.'"' ?> placeholder="@LinkRoob" name="chanel_id">
															<div class="inputbox-hr"><hr></div>
														</div>
														<?php if(!empty($chanel_idError)) echo '<span class="error">'.$chanel_idError.'</span>'; ?>
													</td>
												</tr>
												<tr>
													<td>
														لینک اشتراک (join chat) :
													</td>
													<td>
														<div class="inputbox" style="direction: ltr;">
															<input type="text" class="inputbox-input"
															<?php if((!empty($chanel->joinchat_url))) echo 'value="'.$chanel->joinchat_url.'"' ?>
															placeholder="https://t.me/LinkRoob" name="joinchat_url"/>
															<div class="inputbox-hr"><hr></div>
														</div>
													</td>
												</tr>
												<tr>
													<td>
														توضیحات :
													</td>
													<td>
														<div class="inputbox">
															<textarea style="max-width: 250px;max-height: 200px;" class="inputbox-input" name="description"><?php if(!empty($chanel->description)) echo $chanel->description ?></textarea>
															<div class="inputbox-hr"><hr></div>
														</div>
													</td>
												</tr>
												<tr>
													<td>
														انتخاب عکس :
													</td>
													<td>
													  <label for="file-upload" class="custom-file-upload btn btn-black">
														<i class="fas fa-cloud-upload-alt"></i> انتخاب عکس
													  </label>
													  <input id="file-upload" type="file" style="display:none;" accept="image/png, image/jpeg" name="chanel_img">
													  <?php if(!empty($imageError)) echo '<span class="error">'.$imageError.'</span>'; ?>
													</td>
												</tr>
												<tr>
													<td>
														<input type="submit" value="بروزرسانی" class="btn btn-blue">
													</td>
												</tr>
											</table>
										</form>
									</div>
									<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-3.3.1.js" type="text/javascript"></script>
									<script src="<?php echo get_template_directory_uri() ?>/lib/select2/select2.js"></script>
									<script>
										<?php
											echo "var subcats = ". json_encode(get_list_of_subcats()) . ";";
											echo "var hashtags = ". json_encode(get_hashtags()) . ";";
											echo "var selectedSubCat = ".$chanel->subcat_id.";";
										?>
											/******File Upload on choose file******/
											$('#file-upload').change(function() {
											  var i = $(this).prev('label').clone();
											  var file = $('#file-upload')[0].files[0].name;
											  $(this).prev('label').text(file);
											});
											/******File Upload on choose file******/

											/******Form Chosen******/
											$(".form-chosen select.select2").select2({
												width:"100%",
												minimumResultsForSearch: 15,
												formatNoMatches: "متاسفانه چیزی پیدا نشد!",
											});
											/******Form Chosen******/


											$("#form_social_network_select .select2").on("select2-selecting", function(e){$("#form_social_network_select .input").val(e.val);});
											$("#form_category_select .select2").on("select2-selecting", function(e){
												$("#form_category_select .input").val(e.val);
												$("#form_hashtag_select").parent().parent().css('display','none');
												if(subcats[e.val][1]=="1"){
													$("#form_select_city").parent().parent().css('display','table-row');
												}else{
													$("#form_select_city").parent().parent().css('display','none');
												}
												updateSubcatSelect(e.val);
											});
											$("#form_category_children_select .select2").on("select2-selecting", function(e){
												$("#form_category_children_select .input").val(e.val);
												selectedSubCat = e.val;
												updateHashTagChosen(e.val);
											});
											$("#form_hashtag_select .select2").on("select2-selecting", function(e){
												var a = [];
												$("#form_hashtag_select select.select2 option[value = "+e.val+"]").attr('selected',true);
												$('#form_hashtag_select select.select2 option[selected="selected"]').each(function(index){
													for (var k in hashtags[selectedSubCat]){
														if($(this).val()==hashtags[selectedSubCat][k]['id']){
															a.push(hashtags[selectedSubCat][k]['id']);
														}
													}
												});
												$("#form_hashtag_select .input").val(a.join(','));
											});
											$("#form_hashtag_select .select2").on("select2-removing", function(e){
												var a = [];
												$("#form_hashtag_select select.select2 option[value = "+e.val+"]").removeAttr('selected');
												$('#form_hashtag_select select.select2 option[selected="selected"]').each(function(index){
													for (var k in hashtags[selectedSubCat]){
														if($(this).val()==hashtags[selectedSubCat][k]['id']){
															a.push(hashtags[selectedSubCat][k]['id']);
														}
													}
												});
												$("#form_hashtag_select .input").val(a.join(','));
											});
											$("#form_select_city .select2").on("select2-selecting", function(e){
												$("#form_select_city .input").val(e.val);
											});

											function updateSubcatSelect(CatId){
												var a = subcats[CatId][0];
												if(a !== undefined){
													$("#form_category_children_select").parent().parent().css('display','table-row');
													$("#form_category_children_select select.select2").empty();
													$("#form_category_children_select select.select2").append('<option></option>');
													for(var val in a){
														if(selectedSubCat==a[val]["id"]){
															$("#form_category_children_select select.select2").append('<option selected value="'+a[val]["id"]+'">'+a[val]["name"]+'</option>');
														}else{
															$("#form_category_children_select select.select2").append('<option value="'+a[val]["id"]+'">'+a[val]["name"]+'</option>');
														}
													}
													$('#form_category_children_select select.select2').select2({
														width:"100%",
														minimumResultsForSearch: 15,
														formatNoMatches: "متاسفانه چیزی پیدا نشد!"
													}).trigger('change');
												}else{
													$("#form_category_children_select").parent().parent().css('display','none');
												}
											}
											function updateHashTagChosen(SubcatId){
												var a = hashtags[SubcatId];
												if(a !== undefined){
													$("#form_hashtag_select").parent().parent().css('display','table-row');
													$("#form_hashtag_select select.select2").empty();
													$("#form_hashtag_select select.select2").append('<option></option>');
													for(var val in a){
														$("#form_hashtag_select select.select2").append('<option value="'+a[val]["id"]+'">'+a[val]["name"]+'</option>');
													}
													$('#form_hashtag_select select.select2').select2({
														width:"100%",
														minimumResultsForSearch: 15,
														formatNoMatches: "متاسفانه چیزی پیدا نشد!"
													}).trigger('change');
												}else{
													$("#form_hashtag_select").parent().parent().css('display','none');
												}
											}
									</script>
									<?php
								}else{wp_redirect(home_url('user-panel')); exit;}
								break;
							case 'event':
								$event = get_event($_GET['event_id']);
								$chanel = get_chanel(get_chanel_id($event->chanel_id));
								if($chanel->post_author==$user->ID||current_user_can('administrator')){
									if($_SERVER['REQUEST_METHOD'] === 'POST'){				
										$event_title = $wpdb->escape(trim($_POST['event_title']));
										$event_text = $wpdb->escape(trim($_POST['event_text']));
										$event_link = $wpdb->escape(trim($_POST['event_link']));

										if(!isset($_FILES['event_img']) || !$_FILES['event_img']['error'] == UPLOAD_ERR_NO_FILE) {
											$eventImageError = upload_img($_FILES['event_img'],$chanel->id,0,"/events/");
										}
										if(empty($event_title)){$event_title = $event->title;}
										if(!check_url($event_link)){$event_link = $event->link;}

										if(empty($eventImageError)){
											EditEvent($event->id,$event_title,$event_text,$event_link);
											if(file_exists(wp_get_upload_dir()['basedir']."/events/".$chanel->id.".jpg")){
												rename(wp_get_upload_dir()['basedir']."/events/".$chanel->id.".jpg",
														wp_get_upload_dir()['basedir']."/events/".$chanel->id."_".$event->id.".jpg");
											}
											wp_redirect(home_url('user-panel/?events_list='.$chanel->chanel_id));
											exit;
										}else{
											if(file_exists(wp_get_upload_dir()['basedir']."/events/".$chanel->id.".jpg"))
												unlink(wp_get_upload_dir()['basedir']."/events/".$chanel->id.".jpg");
										}
									}
									?>
									<div class="catview">
										<div>
											<h2><?php echo 'ویرایش  پست  "'.$event->title.'" از  کانال  '."'".$chanel->post_title."'"; ?></h2>
										</div>
										<hr>
									</div>
									<div class="form">
										<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
											<table>
												<tr>
													<td>انتخاب عکس :</td>
													<td>
													  <label for="file-upload" class="custom-file-upload btn btn-black">
														<i class="fas fa-cloud-upload-alt"></i> انتخاب عکس
													  </label>
													  <input id="file-upload" type="file" style="display:none;" accept="image/png, image/jpeg" name="event_img">
													  <?php if(!empty($eventImageError)) echo '<span class="error">'.$eventImageError.'</span>'; ?>
													</td>
												</tr>
												<tr>
													<td>عنوان :</td>
													<td>
														<div class="inputbox">
															<input type="text" class="inputbox-input" <?php echo 'value="'.$event->title.'"'; ?> placeholder="عنوان" name="event_title">
															<div class="inputbox-hr"><hr></div>
														</div>
														<?php if(!empty($eventTitleError)) echo '<span class="error">'.$eventTitleError.'</span>'; ?>
													</td>
												</tr>
												<tr>
													<td>متن :</td>
													<td>
														<div class="inputbox">
															<textarea style="max-width: 250px;max-height: 200px;" class="inputbox-input" name="event_text">
																<?php echo $event->text; ?>
															</textarea>
															<div class="inputbox-hr"><hr></div>
														</div>
													</td>
												</tr>
												<tr>
													<td>لینک :</td>
													<td>
														<div class="inputbox">
															<input type="text" class="inputbox-input" <?php echo 'value="'.$event->link.'"'; ?> placeholder="لینک" name="event_link">
															<div class="inputbox-hr"><hr></div>
														</div>
													</td>
												</tr>
												<tr>
													<td>
														<input class="btn btn-blue" type="submit" value="بروزرسانی">
													</td>
												</tr>
											</table>
										</form>
									</div>
									<?php
								}else{wp_redirect(home_url('user-panel')); exit;}
								break;
						}
						break;
					case 'delete':
						switch ($_GET['delete']) {
							case 'chanel':
								$chanel = get_chanel($_GET['chanel_id']);
								if($chanel->post_author==$user->ID||current_user_can('administrator')){
									if(!empty($chanel)){
										if($_SERVER['REQUEST_METHOD'] === 'POST'){
											if($_POST['delete']){
												delete_chanel($_GET['chanel_id']);
												wp_redirect(home_url('user-panel'));exit;
											}
										}
										?>
										<div class="catview">
											<div>
												<h2>حذف  کانال <?php echo "'".$chanel->post_title."'"; ?></h2>
											</div>
											<hr>
										</div>
										<div class="form">
											<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
												<table>
													<tr>
														<td>آیا میخواهید  این کانال را پاک کنید :</td>
														<td><input type="hidden" name="delete" value="1"></td>
													</tr>
													<tr><td><input class="btn btn-blue" type="submit" value="بله"></td></tr>
												</table>
											</form>
										</div>
										<?php
									}else{wp_redirect(home_url('user-panel'));exit;}
								}else{wp_redirect(home_url('user-panel')); exit;}
								break;
							case 'event':
								$event = get_event($_GET['event_id']);
								$chanel = get_chanel(get_chanel_id($event->chanel_id));
								if($chanel->post_author==$user->ID||current_user_can('administrator')){
									if(!empty($chanel)){
										if($_SERVER['REQUEST_METHOD'] === 'POST'){
											if($_POST['delete']){
												DeleteEvent($_GET['event_id'],$event->chanel_id);
												wp_redirect(home_url('user-panel'));exit;
											}
										}
										?>
										<div class="catview">
											<div>
												<h2><?php echo 'حذف  پست  "'.$event->title.'" از  کانال  '."'".$chanel->post_title."'"; ?></h2>
											</div>
											<hr>
										</div>
										<div class="form">
											<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
												<table>
													<tr>
														<td>آیا میخواهید این  پست را پاک کنید :</td>
														<td><input type="hidden" name="delete" value="1"></td>
													</tr>
													<tr><td><input class="btn btn-blue" type="submit" value="بله"></td></tr>
												</table>
											</form>
										</div>
										<?php
									}else{wp_redirect(home_url('user-panel'));exit;}
								}else{wp_redirect(home_url('user-panel')); exit;}
								break;
							case 'story':
								$chanel = get_chanel($_GET['chanel_id']);
								if($chanel->post_author==$user->ID||current_user_can('administrator')){
									if(!empty($chanel)){
										if($_SERVER['REQUEST_METHOD'] === 'POST'){
											if($_POST['delete']){
												deleteStory($chanel->id,$_GET['chanel_id']);
												wp_redirect(home_url('user-panel'));exit;
											}
										}
										?>
										<div class="catview">
											<div>
												<h2>حذف استوری  کانال <?php echo "'".$chanel->post_title."'"; ?></h2>
											</div>
											<hr>
										</div>
										<div class="form">
											<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
												<table>
													<tr>
														<td>آیا میخواهید استوری این کانال را پاک کنید :</td>
														<td><input type="hidden" name="delete" value="1"></td>
													</tr>
													<tr><td><input class="btn btn-blue" type="submit" value="بله"></td></tr>
												</table>
											</form>
										</div>
										<?php
									}else{wp_redirect(home_url('user-panel'));exit;}
								}else{wp_redirect(home_url('user-panel')); exit;}
								break;
						}
						break;
					case 'get':
						switch ($_GET['get']) {
							case 'vip':
								$chanel = get_chanel($_GET['chanel_id']);
								if($chanel->post_author==$user->ID||current_user_can('administrator')){
									$vip_offer_id = $wpdb->escape(trim($_GET['vip_offer_id']));
									if(!empty($vip_offer_id)){
										$offer =  get_vip_offer($vip_offer_id);

										$type = null;
										if($offer->time_type=="day"){$type = "روزه";}
										else if($offer->time_type=="month"){$type = "ماهه";}
										else if($offer->time_type=="year"){$type = "ساله";}

										$offer->price = (int) $offer->price;
										if($offer->currency=="r"){
											$offer->price /= 10; // Convert to Toman if the currency is rial
										}
										
										$Description = "خرید وی ای پی";
										$Description.= " {$offer->count} {$type} ";
										$Description.= "برای کانال '{$chanel->post_title}' ";
										
										$MerchantID = '704c9f26-e437-4bbe-ad7e-aca441225ce7';  //Required
										$Amount = $offer->price; //Amount will be based on Toman  - Required
										$CallbackURL = home_url("payment/?offerId={$offer->id}");// Required
										$AdditionalData = "{'chanel_id':'{$chanel->chanel_id}'}";
										$AdditionalData = str_replace("'","\"",$AdditionalData);

										// URL also can be ir.zarinpal.com or de.zarinpal.com
										$client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

										$result =  $client->PaymentRequestWithExtra([
											'MerchantID' => $MerchantID,
											'Amount' => $Amount,
											'Description' => $Description,
											'AdditionalData' => $AdditionalData,
											'CallbackURL' => $CallbackURL
										]);

										//Redirect to URL You can do it also by creating a form
										if ($result->Status == 100) {
											header('Location: https://www.zarinpal.com/pg/StartPay/'.$result->Authority);
										} else {
											echo'ERR: '.$result->Status;
										}
										
									}
									?>
									<div class="catview">
										<div>
											<h2>خرید vip برای کانال  <?php echo "'".$chanel->post_title."'"; ?></h2>
										</div>
										<hr>
									</div>
									<div class="form">
										<?php 
											$vip_offers = get_vip_offers();
											foreach ($vip_offers as $key => $value) {
												$type = null;
												if($value->time_type=="day"){$type = "روزه";}
												else if($value->time_type=="month"){$type = "ماهه";}
												else if($value->time_type=="year"){$type = "ساله";}

												$currency = null;
												if($value->currency=="r"){$currency = "ریال";}
												else if($value->currency=="t"){$currency = "تومان";}

												echo '<a class="link" href="'.home_url('user-panel/?action=get&get=vip&chanel_id='.$_GET['chanel_id'].'&vip_offer_id='.$value->id).'">
												<h3>'.$value->count.' '.$type.' -> '.$value->price.' '.$currency.'</h3></a>';
											}
										?>

										<br>
										<br>
										<br>
										<h3 style="color: #333;">ویژگی ها :</h3>
										<span style="color: #333;">• نمایش در اولین نتایج</span>
										<br>
										<span style="color: #333;">• ارسال استوری</span>
									</div>
									<?php
								}else{wp_redirect(home_url('user-panel')); exit;}
								break;
							case 'upgrade':
								$chanel = get_chanel($_GET['chanel_id']);
								if($chanel->post_author==$user->ID||current_user_can('administrator')){
									if(!empty($_GET['upgrade'])){
										$offer =  get_upgrade_offer();

										$offer->price = (int) $offer->price;
										if($offer->currency=="r"){
											$offer->price /= 10; // Convert to Toman if the currency is rial
										}
										
										$Description = "خرید ارتقا";
										$Description.= "برای کانال '{$chanel->post_title}' ";
										
										$MerchantID = '704c9f26-e437-4bbe-ad7e-aca441225ce7';  //Required
										$Amount = $offer->price ; //Amount will be based on Toman  - Required
										$CallbackURL = home_url("payment/?offerId={$offer->id}");// Required
										$AdditionalData = "{'chanel_id':'{$chanel->chanel_id}'}";
										$AdditionalData = str_replace("'","\"",$AdditionalData);

										// URL also can be ir.zarinpal.com or de.zarinpal.com
										$client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

										$result =  $client->PaymentRequestWithExtra([
											'MerchantID' => $MerchantID,
											'Amount' => $Amount,
											'Description' => $Description,
											'AdditionalData' => $AdditionalData,
											'CallbackURL' => $CallbackURL
										]);

										//Redirect to URL You can do it also by creating a form
										if ($result->Status == 100) {
											header('Location: https://www.zarinpal.com/pg/StartPay/'.$result->Authority);
										} else {
											echo'ERR: '.$result->Status;
										}
									}
									?>
									<div class="catview">
										<div>
											<h2> ارتقاع کانال  <?php echo "'".$chanel->post_title."'"; ?></h2>
										</div>
										<hr>
									</div>
									<div class="form">
										<?php 
											$upgrade_offer = get_upgrade_offer();

											$currency = null;
											if($upgrade_offer->currency=="r"){$currency = "ریال";}
											else if($upgrade_offer->currency=="t"){$currency = "تومان";}

											echo '<a class="link" href="'.home_url('user-panel/?action=get&get=upgrade&upgrade='.$_GET['chanel_id']).'">
											<h3>ارتقاع کانال با قیمت  '.$upgrade_offer->price.' '.$currency.'</h3></a>';
										?>

										<br>
										<br>
										<br>
										<h3 style="color: #333;">ویژگی ها :</h3>
										<span style="color: #333;">• امکان ارسال 4عدد عکس در صفحه</span>
										<br>
										<span style="color: #333;">• نمایش هنگام فیلتر(نمایش بر اساس اجناس،کالا و ...)</span>
									</div>
									<?php
								}else{wp_redirect(home_url('user-panel')); exit;}
								break;
							case 'events':
								$chanel = get_chanel($_GET['chanel_id']);
								if($chanel->post_author==$user->ID||current_user_can('administrator')){ ?>
									<div class="catview">
										<div>
											<h2><?php echo 'پست های  کانال  '."'".$chanel->post_title."'"; ?></h2>
											<?php 
												$events = get_events($chanel->id);
												if(count($events)<4){?>
													<a class="btn btn-blue" 
													href="<?php echo home_url("user-panel/?").EditGETQuery(array(),array('action'=>'add','add'=>'event','chanel_id'=>$chanel->chanel_id)); ?>">
														<i class="fas fa-plus"></i> اضافه کردن  </a>
											<?php } ?>
										</div>
										<hr>
									</div>
									<div  class="user-panel-events">
										<?php
											foreach ($events as $e => $event){ 
												$ev = "";
												$ev.='<div class="event">';
													if(!empty($event->link)) $ev.='<a href="'.$event->link.'">';
														$size = getimagesize(wp_get_upload_dir()['basedir']."/events/".$event->chanel_id."_".$event->id.".jpg");
														$ev.='<div class="event-img-box"><div class="event-img"><img class="';
														if($size[0]<$size[1]){$ev.='event-img-horizontal';} 
														else{$ev.='event-img-vertical';}
														$ev.='" src="'.home_url().'/wp-content/uploads/events/'.$event->chanel_id.'_'.$event->id.".jpg?ver=".rand(111,999).'"></div></div>';

														$ev.='<h4>'.$event->title.'</h4>';
														if(!empty($event->text)) $ev.='<p>'.$event->text.'</p>';
													if(!empty($event->link)) $ev.='</a>';

													$ev.='<div class="user-panel-event-actions">';
														$ev.='<a class="link-green" href="'.home_url('user-panel/?').EditGETQuery(array(),array('action'=>'edit','edit'=>'event','event_id'=>$event->id)).'"><i class="fas fa-edit"></i> ویرایش</a>';
														$ev.='<a class="link-red" href="'.home_url('user-panel/?').EditGETQuery(array(),array('action'=>'delete','delete'=>'event','event_id'=>$event->id)).'"><i class="fas fa-trash"></i> حذف</a>';
													$ev.='</div>';

												$ev.='</div>';
												echo $ev;
											}
										?>
									</div>
									<?php
								}else{wp_redirect(home_url('user-panel')); exit;}
								break;
						}
						break;
				}
			}else{ ?>
				<div class="catview">
					<div>
						<h2>پنل کاربری</h2>
					</div>
					<hr>
				</div>
				<div class="user-panel">
				  <div class="catview">
					<div>
						<h4>لیست کانال های شما</h4>
						<a class="btn btn-blue" href="<?php echo home_url('add'); ?>"><i class="fas fa-plus"></i> اضافه کردن  </a>
					</div>
					<hr>
				  </div>
				  <?php 
					$posts = get_chanels(0,"publish",null,0,$user->ID);

					if(count($posts)>0){ ?>
					  <table class="chanels-list">
						   <tr class="types">
							  <td></td>
							  <td><div>تصویر</div></td>
							  <td><div>مشخصات</div></td>
							  <td><div>نوع ثبت</div></td>
							  <td><div>استوری</div></td>
							  <td></td>
						   </tr>
						<?php
							$i = 0;
							foreach ($posts as $p => $post){
								$i++;
								$cat = get_cat_by_id($post->cat_id);
								$subcat = get_subcat_by_id($post->subcat_id);
								$social_network = get_social_network_by_id($post->social_network_id);
							?>
									<tr class="chanel">
									  <td><?php echo $i; ?></td>
									  <td>
										<div>
											<?php 
												$has_story = false; 
												$vip_avaible = false; 
												if($post->vip_date>time()){
													$vip_avaible = true;
													if(time()<$post->story_time){
														$has_story = true;
													}
												}
											?>
											<div class="user-panel-chanel-image-box 
											<?php 
												if($vip_avaible){
													if($has_story){echo 'user-panel-chanel-image-story';}
													else{echo 'user-panel-chanel-image-spical';}
												}else{echo 'user-panel-chanel-image-normal';}
											?>"
											<?php if($vip_avaible&&$has_story){echo 'onclick="ShowStory(this.id)" id="'.$post->id.'"';}?>>
												<img src='<?php echo home_url().'/wp-content/uploads/img/'.$post->chanel_id.".jpg"; ?>' alt=""/>
											</div>
											<?php if($vip_avaible&&$has_story){ ?>
												<div class="story-box">
													<div class="story-duration"><hr <?php if(!empty($post->story_duration)) echo 'style="transition:'.$post->story_duration.'s all"' ?> ></div>
													<div class="story">
														<a href="<?php echo $post->story_link; ?>" target="_blank">
															<img src="<?php echo home_url().'/wp-content/uploads/story/'.$post->chanel_id.".jpg" ?>">
														</a>
													</div>
													<i class="close-story fas fa-times"></i>
												</div>
											<?php } ?>
										</div>
									  </td>
									  <td>
									  	<div>
										  	<table class="chanel-properties">
										  		<tr><td>نام :</td><td><?php echo $post->post_title; ?></td></tr>
										  		<tr><td><i class="fas fa-list"></i> : </td><td><div style="flex-flow: column;"><?php echo "<span>".$cat->name."</span><span>".$subcat->name."</span>"; ?></td></div></tr>
										  		<tr><td><i class="fab fa-telegram-plane"></i> : </td><td><?php echo $social_network; ?></td></tr>
										  		<tr class="chanel-properties-last-one"><td>ایدی :</td><td style="direction: ltr"><a class="link" href="<?php echo $post->guid; ?>"><?php echo $post->chanel_id; ?></a></td></tr>
										  		<tr class="registration-type-2">
										  			<td>نوع ثبت :</td>
										  			<td>
													  	<div style="flex-flow: column;font-size: 13px;line-height:20px;">
														  	<?php 
														  		$free = 1;
														  		if($vip_avaible){
														  			echo "<span>";
																	echo ' vip برای ';
														  			echo human_time_diff($post->vip_date,time());
														  			echo "</span>";
														  			$free = 0;
														  			if($post->upgrade){echo '<hr style="width:100%;border: 0.5px solid #333;">';}
														  		}
														  		if($post->upgrade){echo "ارتقاع یافته";$free = 0;}
														  		if($free){echo 'رایگان';}
														  	?>
														</div>
													</td>
										  		</tr>
										  		<tr class="story-registration-2">
										  			<td>استوری  :</td>
										  			<td>
													  	<div style="flex-flow: column;font-size: 13px;line-height:20px;">
													  	<?php 
													  		if($vip_avaible){
														  		if($has_story){
																	echo '<span style="width: 150px;font-weight: bold;">برای مشاهده استوری روی عکس کانال کلیک کنید</span>';
																	echo '<hr style="width:100%;border: 0.5px solid #333;">';
														  			echo '<span><i class="fas fa-calendar-alt"></i> : تا '.human_time_diff($post->story_time,time()).' دیگر </span>';
														  			echo '<span><i class="fas fa-clock"></i> :'.$post->story_duration.' ثانیه</span>';
														  			?>
																	<a class="btn btn-blue" 
																	href="<?php echo home_url("user-panel/?").EditGETQuery(array(),array('action'=>'delete','delete'=>'story','chanel_id'=>$post->chanel_id)); ?>" >حذف استوری</a>
																<?php }else{ ?>
														  			<a class="btn btn-blue" 
														  			href="<?php echo home_url("user-panel/?").EditGETQuery(array(),array('action'=>'add','add'=>'story','chanel_id'=>$post->chanel_id)); ?>">ثبت استوری</a>
														  		<?php }
													  		}else{ echo '<span style="width: 150px;font-weight: bold;">برای ثبت استوری باید کانال را vip کنید</span>';} ?>
													  </div>
													</td>
										  		</tr>
										  	</table>
									  	</div>
									  </td>
									  <td class="registration-type-1">
									  	<div style="flex-flow: column;font-size: 13px;line-height:20px;">
									  	<?php 
									  		$free = 1;
									  		if($vip_avaible){
									  			echo "<span>";
												echo ' vip برای ';
									  			echo human_time_diff($post->vip_date,time());
									  			echo "</span>";
									  			$free = 0;
									  			if($post->upgrade){echo '<hr style="width:100%;border: 0.5px solid #333;">';}
									  		}
									  		if($post->upgrade){echo "ارتقاع یافته";$free = 0;}
									  		if($free){echo 'رایگان';}
									  	?>
									  </div></td>
									  <td class="story-registration-1">
									  	<div style="flex-flow: column;font-size: 13px;line-height:20px;">
									  	<?php 
									  		if($vip_avaible){
										  		if($has_story){
													echo '<span style="width: 150px;font-weight: bold;">برای مشاهده استوری روی عکس کانال کلیک کنید</span>';
													echo '<hr style="width:100%;border: 0.5px solid #333;">';
										  			echo '<span><i class="fas fa-calendar-alt"></i> : تا '.human_time_diff($post->story_time,time()).' دیگر </span>';
										  			echo '<span><i class="fas fa-clock"></i> :'.$post->story_duration.' ثانیه</span>';
										  			?>
													<a class="btn btn-blue" 
													href="<?php echo home_url("user-panel/?").EditGETQuery(array(),array('action'=>'delete','delete'=>'story','chanel_id'=>$post->chanel_id)); ?>" >حذف استوری</a>
												<?php }else{ ?>
										  			<a class="btn btn-blue" 
										  			href="<?php echo home_url("user-panel/?").EditGETQuery(array(),array('action'=>'add','add'=>'story','chanel_id'=>$post->chanel_id)); ?>">ثبت استوری</a>
										  		<?php }
									  		}else{ echo '<span style="width: 150px;font-weight: bold;">برای ثبت استوری باید کانال را vip کنید</span>';} ?>
									  </div></td>
									  <td>
										<div class="chanel-actions noselect">
											<a class="vip" href="<?php echo home_url("user-panel/?").EditGETQuery(array(),array('action'=>'get','get'=>'vip','chanel_id'=>$post->chanel_id)); ?>">
												ویژه کردن
												<span>VIP</span>
											</a>
											<?php 
												if($post->upgrade){ 
												 	$events = get_events($post->id);
												 	if(count($events)>0){ ?>
														<a class="upgrade" 
														href="<?php echo home_url("user-panel/?").EditGETQuery(array(),array('action'=>'get','get'=>'events','chanel_id'=>$post->chanel_id)); ?>">
															پست ها
															<i class="fas fa-list"></i>
														</a>										
													<?php }else{ ?>
														<a class="upgrade" 
														href="<?php echo home_url("user-panel/?").EditGETQuery(array(),array('action'=>'add','add'=>'event','chanel_id'=>$post->chanel_id)); ?>">
															اضافه کردن  پست
															<i class="fas fa-plus"></i>
														</a>
													<?php } ?>
											<?php }else{ ?>
													<a class="upgrade" 
													href="<?php echo home_url("user-panel/?").EditGETQuery(array(),array('action'=>'get','get'=>'upgrade','chanel_id'=>$post->chanel_id)); ?>">
														ارتقاء
														<i class="fas fa-arrow-up"></i>
													</a>
											<?php } ?>
											<a class="edit" 
											href="<?php echo home_url("user-panel/?").EditGETQuery(array(),array('action'=>'edit','edit'=>'chanel','chanel_id'=>$post->chanel_id)); ?>">
												ویرایش
												<i class="fas fa-edit"></i>
											</a>
											<a class="delete" 
											href="<?php echo home_url("user-panel/?").EditGETQuery(array(),array('action'=>'delete','delete'=>'chanel','chanel_id'=>$post->chanel_id)); ?>">
												حذف
												<i class="fas fa-trash"></i>
											</a>
										</div>
									  </td>          
									</tr>
							<?php } ?>
					  </table>
				  <?php }else{ ?>
					  <div class="chanels-not-found">
						<h4>هیج کانالی پیدا نشد</h4>
					  </div>
				  <?php } ?>
				</div>
		<?php } ?>

<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-3.3.1.js" type="text/javascript"></script>
<script>
	/******File Upload on choose file******/
	$('#file-upload').change(function() {
	  var i = $(this).prev('label').clone();
	  var file = $('#file-upload')[0].files[0].name;
	  $(this).prev('label').text(file);
	});
	/******File Upload on choose file******/

	function post(path, params, method) {
		method = method || "post"; // Set method to post by default if not specified.

		// The rest of this code assumes you are not using a library.
		// It can be made less wordy if you use one.
		var form = document.createElement("form");
		form.setAttribute("method", method);
		form.setAttribute("action", path);

		for(var key in params) {
			if(params.hasOwnProperty(key)) {
				var hiddenField = document.createElement("input");
				hiddenField.setAttribute("type", "hidden");
				hiddenField.setAttribute("name", key);
				hiddenField.setAttribute("value", params[key]);

				form.appendChild(hiddenField);
			}
		}

		document.body.appendChild(form);
		form.submit();
	}
</script>
<?php get_footer(); }else{
	wp_redirect(home_url('log-in'));
	exit;
} ?>
