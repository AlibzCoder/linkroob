<?php if (session_status() == PHP_SESSION_NONE)
    session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="<?php echo home_url(); ?>/wp-content/plugins/linkroobmgr/style.css?ver=<?php echo rand(111,999); ?>">
	<link rel="stylesheet" href="<?php echo home_url(); ?>/wp-content/plugins/linkroobmgr/css/solid.css">
	<link rel="stylesheet" href="<?php echo home_url(); ?>/wp-content/plugins/linkroobmgr/css/regular.css">
	<link rel="stylesheet" href="<?php echo home_url(); ?>/wp-content/plugins/linkroobmgr/css/brands.css">
	<link rel="stylesheet" href="<?php echo home_url(); ?>/wp-content/plugins/linkroobmgr/css/fontawesome.css">
	<?php global $wpdb; ?>
</head>
<body>
	<?php if(count($_GET)>1){ 
		echo '<style>body{background:#f5f5f5;}</style>';
		echo '<a style="font-family:Shabnam" href="'.home_url('wp-admin/admin.php?page=linkroobmgr').'">بازگشت</a><br>';
		switch ($_GET['action']) {
			case 'add':
				switch ($_GET['add']) {
					case 'cat':
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							$name = $wpdb->escape(trim($_POST['title']));
							$city_filter_enable = ($wpdb->escape(trim($_POST['city_filter_enable']))=="on") ? 1 : 0;
							echo $city_filter_enable;
							if(empty($name)){$nameError = "لطفا عنوان  دسته بندی را انتخاب کنید";}
							else{
								$id = add_cat($name,$city_filter_enable);
								$_SESSION["afected"] = array('afected'=>'added','added'=>'cat','cat_id'=>$id);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">اضافه کردن  دسته بندی</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>عنوان :</td>
										<td>
											<div class="inputbox">
												<input type="text" class="inputbox-input" <?php if($everyThingOK==0&&(!empty($title))) echo 'value="'.$title.'"' ?> placeholder="عنوان" name="title">
												<div class="inputbox-hr"><hr></div>
											</div>
											<?php if(!empty($nameError)) echo '<span class="error">'.$nameError.'</span>'; ?>
										</td>
									</tr>
									<tr>
										<td>فیلتر شهر  :</td>
										<td><input type="checkbox" name="city_filter_enable"></td>
									</tr>
									<tr><td><input class="btn btn-blue" type="submit" value="ثبت"></td></tr>
								</table>
							</form>
						</div>
						<?php
						break;
					case 'subcat':
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							$everyThingOK = 1;
							$cat = $wpdb->escape(trim($_POST['cat']));
							$name = $wpdb->escape(trim($_POST['title']));
							if(empty($name)){$everyThingOK = 0;$nameError = "لطفا عنوان زیر دسته را انتخاب کنید";}
							if(empty($cat)){$everyThingOK = 0;$catError = "لطفا دسته بندی اصلی را برای زیر دسته انتخاب کنید";}
							if($everyThingOK){
								$id = add_subcat($name,$cat);
								$_SESSION["afected"] = array('afected'=>'added','added'=>'subcat','subcat_id'=>$id);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">اضافه کردن  زیردسته</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>
											انتخاب دسته بندی :
										</td>
										<td>
											<select name="cat">
												<option></option>
												<?php
													$cat_list = get_list_of_cat();
													foreach ($cat_list as $row){
														if(($everyThingOK==0)&&$row["id"]==$_GET["cat_id"]){
															echo '<option value="'.$row["id"].'" selected>'.$row["name"].'</option>';
														}else{
															echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
														}
													}
												?>
											</select>
											<?php if(!empty($catError)) echo '<span class="error">'.$catError.'</span>'; ?>
										</td>
									</tr>
									<tr>
										<td>
											عنوان :
										</td>
										<td>
											<div class="inputbox">
												<input type="text" class="inputbox-input" <?php if($everyThingOK==0&&(!empty($title))) echo 'value="'.$title.'"' ?> placeholder="عنوان" name="title">
												<div class="inputbox-hr"><hr></div>
											</div>
											<?php if(!empty($nameError)) echo '<span class="error">'.$nameError.'</span>'; ?>
										</td>
									</tr>
									<tr>
										<td>
											<input class="btn btn-blue" type="submit" value="ثبت">
										</td>
									</tr>
								</table>
							</form>
						</div>
						<?php
						break;
					case 'social_network':
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							$name = $wpdb->escape(trim($_POST['title']));
							if(empty($name)){$everyThingOK = 0;$nameError = "لطفا عنوان را وارد کنید ";}
							else{
								$id = add_social_network($name);
								$_SESSION["afected"] = array('afected'=>'added','added'=>'social_network','social_network_id'=>$id);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">اضافه کردن  شبکه اجتماعی </h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>
											عنوان :
										</td>
										<td>
											<div class="inputbox">
												<input type="text" class="inputbox-input" <?php if($everyThingOK==0&&(!empty($title))) echo 'value="'.$title.'"' ?> placeholder="عنوان" name="title">
												<div class="inputbox-hr"><hr></div>
											</div>
											<?php if(!empty($nameError)) echo '<span class="error">'.$nameError.'</span>'; ?>
										</td>
									</tr>
									<tr>
										<td>
											<input class="btn btn-blue" type="submit" value="ثبت">
										</td>
									</tr>
								</table>
							</form>
						</div>
						<?php
						break;
					case 'hashtag':
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							$everyThingOK = 1;
							$subcat = $wpdb->escape(trim($_POST['subcat']));
							$name = $wpdb->escape(trim($_POST['title']));
							if(empty($name)){$everyThingOK = 0;$nameError = "لفا عنوان  هشتگ را انتخاب کنید";}
							if(empty($subcat)){$everyThingOK = 0;$catError = "لطفا   زیر دسته  را برای  هشتگ  انتخاب کنید";}
							if($everyThingOK){
								$id = add_hashtag($name,$subcat);
								$_SESSION["afected"] = array('afected'=>'added','added'=>'hashtag','hashtag_id'=>$id);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">اضافه کردن  هشتگ</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>
											انتخاب دسته بندی :
										</td>
										<td>
											<select onchange="location = 
											<?php echo "'".home_url("wp-admin/admin.php").'?'.http_build_query($_GET)."'";?>+'&cat_id='+this.value">
												<option></option>
												<?php
													$cat_list = get_list_of_cat();
													foreach ($cat_list as $row){
														if(($everyThingOK==0)&&$row["id"]==$_GET["cat_id"]){
															echo '<option value="'.$row["id"].'" selected>'.$row["name"].'</option>';
														}else{
															echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
														}
													}
												?>
											</select>
											<?php if(!empty($catError)) echo '<span class="error">'.$catError.'</span>'; ?>
										</td>
									</tr>
									<?php if(count(get_subcats_by_cat_id($_GET["cat_id"]))>0){ ?>
										<tr>
											<td>
												انتخاب  زیر دسته :
											</td>
											<td>
												<select name="subcat">
													<option></option>
													<?php
														$subcat_list = get_subcats_by_cat_id($_GET["cat_id"]);
														foreach ($subcat_list as $row){
															if(($everyThingOK==0)&&$row["id"]==$_GET["subcat_id"]){
																echo '<option value="'.$row["id"].'" selected>'.$row["name"].'</option>';
															}else{
																echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
															}
														}
													?>
												</select>
												<?php if(!empty($subcatError)) echo '<span class="error">'.$subcatError.'</span>'; ?>
											</td>
										</tr>
									<?php } ?>
									<tr>
										<td>
											عنوان :
										</td>
										<td>
											<div class="inputbox">
												<input type="text" class="inputbox-input" <?php if($everyThingOK==0&&(!empty($title))) echo 'value="'.$title.'"' ?> placeholder="عنوان" name="title">
												<div class="inputbox-hr"><hr></div>
											</div>
											<?php if(!empty($nameError)) echo '<span class="error">'.$nameError.'</span>'; ?>
										</td>
									</tr>
									<tr>
										<td>
											<input class="btn btn-blue" type="submit" value="ثبت">
										</td>
									</tr>
								</table>
							</form>
						</div>
						<?php
						break;
					case 'city':
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							$name = $wpdb->escape(trim($_POST['title']));
							if(empty($name)){$everyThingOK = 0;$nameError = "لطفا نام را وارد کنید ";}
							else{
								$id = add_city($name);
								$_SESSION["afected"] = array('afected'=>'added','added'=>'city','city_id'=>$id);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">اضافه کردن  شهر </h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>
											نام :
										</td>
										<td>
											<div class="inputbox">
												<input type="text" class="inputbox-input" <?php if($everyThingOK==0&&(!empty($title))) echo 'value="'.$title.'"' ?> placeholder="عنوان" name="title">
												<div class="inputbox-hr"><hr></div>
											</div>
											<?php if(!empty($nameError)) echo '<span class="error">'.$nameError.'</span>'; ?>
										</td>
									</tr>
									<tr>
										<td>
											<input class="btn btn-blue" type="submit" value="ثبت">
										</td>
									</tr>
								</table>
							</form>
						</div>
						<?php
						break;
					case 'vip':
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							$time_type = $wpdb->escape(trim($_POST['time_type']));
							$count = $wpdb->escape(trim($_POST['count']));
							$currency = $wpdb->escape(trim($_POST['currency']));
							$price = $wpdb->escape(trim($_POST['price']));
							if(empty($price)){$priceError = "لطفا قیمت را انتخاب کنید";}
							else{
								$id = add_vip_offer($count,$time_type,$price,$currency);
								$_SESSION["afected"] = array('afected'=>'added','added'=>'vip','vip_id'=>$id);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">اضافه کردن  قیمت vip</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>
											انتخاب  نوع زمان  :
										</td>
										<td>
											<select name="time_type">
												<option value="day">روز</option>
												<option value="month">ماه</option>
												<option value="year">سال</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>تعداد نوع زمان :</td>
										<td><input type="number" name="count" class="form-number" min="1" max="31" value="1"></td>
									</tr>
									<tr>
										<td>انتخاب  نوع  پول :</td>
										<td>
											<select name="currency">
												<option value="r">ریال</option>
												<option value="t">تومان</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>قیمت :</td>
										<td><input type="number" name="price" class="form-number"></td>
										<?php if(!empty($priceError)) echo '<span class="error">'.$priceError.'</span>'; ?>
									</tr>
									<tr>
										<td>
											<input class="btn btn-blue" type="submit" value="ثبت">
										</td>
									</tr>
								</table>
							</form>
						</div>
						<?php
						break;
				}
				break;
			case 'edit':
				switch ($_GET['edit']) {
					case 'cat':
						$cat_to_edit = get_cat_by_id($_GET["cat_id"]);
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							$name = $wpdb->escape(trim($_POST['title']));
							$city_filter_enable = ($wpdb->escape(trim($_POST['city_filter_enable']))=="on")? 1 : 0;
							if(empty($name)){$nameError = "لطفا عنوان را وارد کنید";}
							else{
								if($cat_to_edit->name != $name||$city_filter_enable!=$cat_to_edit->city_filter_enable){
									edit_cat($cat_to_edit->id,$name,$city_filter_enable);
									$_SESSION["afected"] = array('afected'=>'edited','edited'=>'cat','cat_id'=>$cat_to_edit->id,
										'before'=> array('name'=> $cat_to_edit->name, 'city_filter_enable'=>$cat_to_edit->city_filter_enable));
									wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
								}
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">ویرایش  دسته بندی "<?php echo $cat_to_edit->name; ?>"</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>عنوان :</td>
										<td>
											<div class="inputbox">
												<input type="text" class="inputbox-input" placeholder="عنوان" 
												name="title" value="<?php echo $cat_to_edit->name; ?>">
												<div class="inputbox-hr"><hr></div>
											</div>
											<?php if(!empty($nameError)) echo '<span class="error">'.$nameError.'</span>'; ?>
										</td>
									</tr>
									<tr>
										<td>فیلتر شهر  :</td>
										<td>
											<input type="checkbox" name="city_filter_enable"
											<?php if($cat_to_edit->city_filter_enable) echo "checked"; ?>>
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
						break;
					case 'subcat':
						$subcat_to_edit = get_subcat_by_id($_GET['subcat_id']);
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							$name = $wpdb->escape(trim($_POST['title']));
							if(empty($name)){$nameError = "لطفا عنوان را وارد کنید";}
							else{if($subcat_to_edit->name != $name){edit_subcat($name,$subcat_to_edit->id);
								$_SESSION["afected"] = array('afected'=>'edited','edited'=>'subcat','subcat_id'=>$subcat_to_edit->id,'before'=>$subcat_to_edit->name);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}}
						}
						?>
						<div class="form">
							<h2 class="form-title">ویرایش زیر دسته "<?php echo $subcat_to_edit->name; ?>"</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>
											عنوان :
										</td>
										<td>
											<div class="inputbox">
												<input type="text" class="inputbox-input" placeholder="عنوان" 
												name="title" value="<?php echo $subcat_to_edit->name; ?>">
												<div class="inputbox-hr"><hr></div>
											</div>
											<?php if(!empty($nameError)) echo '<span class="error">'.$nameError.'</span>'; ?>
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
						break;
					case 'social_network':
						$social_network_to_edit = get_social_network_by_id($_GET['social_network_id']);
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							$name = $wpdb->escape(trim($_POST['title']));
							if(empty($name)){$nameError = "لطفا عنوان را وارد کنید";}
							else{if($social_network_to_edit != $name){edit_social_network($name,$_GET['social_network_id']);
								$_SESSION["afected"] = array('afected'=>'edited','edited'=>'social_network','social_network_id'=>$_GET['social_network_id'],'before'=>$social_network_to_edit);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}}
						}
						?>
						<div class="form">
							<h2 class="form-title">ویرایش  شبکه اجتماعی "<?php echo $social_network_to_edit; ?>"</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>
											عنوان :
										</td>
										<td>
											<div class="inputbox">
												<input type="text" class="inputbox-input" placeholder="عنوان" 
												name="title" value="<?php echo $social_network_to_edit->name; ?>">
												<div class="inputbox-hr"><hr></div>
											</div>
											<?php if(!empty($nameError)) echo '<span class="error">'.$nameError.'</span>'; ?>
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
						break;
					case 'hashtag':
						$hashtag_to_edit = get_hashtag_by_id($_GET['hashtag_id']);
						$hashtag_to_edit_subcat = get_subcat_by_id($hashtag_to_edit->subcat_id);
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							$name = $wpdb->escape(trim($_POST['title']));
							if(empty($name)){$nameError = "لطفا عنوان را وارد کنید";}
							else{if($hashtag_to_edit->name != $name){edit_hashtag($name,$hashtag_to_edit->id);
								$_SESSION["afected"] = array('afected'=>'edited','edited'=>'hashtag','hashtag_id'=>$hashtag_to_edit->id,'before'=>$hashtag_to_edit->name);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}}
						}
						?>
						<div class="form">
							<h2 class="form-title">ویرایش  هشتگ  "<?php echo $hashtag_to_edit->name; ?>" از زیر دسته 
								"<?php echo $hashtag_to_edit_subcat->name ?>"</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>
											عنوان :
										</td>
										<td>
											<div class="inputbox">
												<input type="text" class="inputbox-input" placeholder="عنوان" 
												name="title" value="<?php echo $hashtag_to_edit->name; ?>">
												<div class="inputbox-hr"><hr></div>
											</div>
											<?php if(!empty($nameError)) echo '<span class="error">'.$nameError.'</span>'; ?>
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
						break;
					case 'city':
						$city_to_edit = get_city($_GET['city_id']);
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							$name = $wpdb->escape(trim($_POST['title']));
							if(empty($name)){$nameError = "لطفا عنوان را وارد کنید";}
							else{if($city_to_edit->name != $name){edit_city($name,$city_to_edit->id);
								$_SESSION["afected"] = array('afected'=>'edited','edited'=>'city','city_id'=>$city_to_edit->id,'before'=>$city_to_edit->name);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}}
						}
						?>
						<div class="form">
							<h2 class="form-title">ویرایش  شهر  "<?php echo $city_to_edit->name; ?>"</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>
											نام :
										</td>
										<td>
											<div class="inputbox">
												<input type="text" class="inputbox-input" placeholder="عنوان" 
												name="title" value="<?php echo $city_to_edit->name; ?>">
												<div class="inputbox-hr"><hr></div>
											</div>
											<?php if(!empty($nameError)) echo '<span class="error">'.$nameError.'</span>'; ?>
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
						break;
					case 'vip':
						$vip_to_edit = get_vip_offer($_GET['vip_id']);
						$type = null;
						if($vip_to_edit->time_type=="day"){$type = "روزه";}
						else if($vip_to_edit->time_type=="month"){$type = "ماهه";}
						else if($vip_to_edit->time_type=="year"){$type = "ساله";}
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							$time_type = $wpdb->escape(trim($_POST['time_type']));
							$count = $wpdb->escape(trim($_POST['count']));
							$currency = $wpdb->escape(trim($_POST['currency']));
							$price = $wpdb->escape(trim($_POST['price']));
							if($vip_to_edit->count!=$count||
								$vip_to_edit->time_type!=$time_type||
								$vip_to_edit->currency!=$currency||
								$vip_to_edit->price!=$price){
								edit_vip_offer($vip_to_edit->id,$count,$time_type,$price,$currency);
								$_SESSION["afected"] = array('afected'=>'edited','edited'=>'vip',
									'vip_id'=>$_GET['vip_id'],'before'=>$vip_to_edit);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">ویرایش وی ای پی <?php echo $vip_to_edit->count . ' ' . $type;  ?></h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>
											انتخاب  نوع زمان  :
										</td>
										<td>
											<select name="time_type">
												<option value="day" <?php if($vip_to_edit->time_type=='day') echo "selected"; ?>>روز</option>
												<option value="month" <?php if($vip_to_edit->time_type=='month') echo "selected"; ?>>ماه</option>
												<option value="year" <?php if($vip_to_edit->time_type=='year') echo "selected"; ?>>سال</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>تعداد نوع زمان :</td>
										<td><input type="number" name="count" class="form-number" min="1" max="31" 
											value="<?php echo $vip_to_edit->count; ?>"></td>
									</tr>
									<tr>
										<td>انتخاب  نوع  پول :</td>
										<td>
											<select name="currency">
												<option value="r" <?php if($vip_to_edit->currency=='r') echo "selected"; ?>>ریال</option>
												<option value="t" <?php if($vip_to_edit->currency=='t') echo "selected"; ?>>تومان</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>قیمت :</td>
										<td><input type="number" name="price" class="form-number" value="<?php echo $vip_to_edit->price; ?>"></td>
										<?php if(!empty($priceError)) echo '<span class="error">'.$priceError.'</span>'; ?>
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
						break;
					case 'upgrade':
						$upgrade_to_edit = get_upgrade_offer();
						$currency = null;
						if($upgrade_to_edit->currency=="r"){$currency = "ریال";}
						else if($upgrade_to_edit->currency=="t"){$currency = "تومان";}
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {

							$currency = $wpdb->escape(trim($_POST['currency']));
							$price = $wpdb->escape(trim($_POST['price']));

							if($upgrade_to_edit->currency!=$currency||
								$upgrade_to_edit->price!=$price){
								edit_upgrade_offer($price,$currency);
								$_SESSION["afected"] = array('afected'=>'edited','edited'=>'upgrade','before'=>$upgrade_to_edit);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">ویرایش  قیمت ارتقاء  "<?php echo $upgrade_to_edit->price . ' ' . $currency;  ?>"</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>انتخاب  نوع  پول :</td>
										<td>
											<select name="currency">
												<option value="r" <?php if($upgrade_to_edit->currency=='r') echo "selected"; ?>>ریال</option>
												<option value="t" <?php if($upgrade_to_edit->currency=='t') echo "selected"; ?>>تومان</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>قیمت :</td>
										<td><input type="number" name="price" class="form-number" value="<?php echo $upgrade_to_edit->price; ?>"></td>
										<?php if(!empty($priceError)) echo '<span class="error">'.$priceError.'</span>'; ?>
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
						break;
				}
				break;
			case 'delete':
				switch ($_GET['delete']) {
					case 'cat':
						$cat_to_delete = get_cat_by_id($_GET["cat_id"]);
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							if($_POST["delete"]){
								delete_cat($cat_to_delete->id);
								$_SESSION["afected"] = array('afected'=>'deleted','deleted'=>'cat','before'=>$cat_to_delete->name);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">حذف  دسته بندی "<?php echo $cat_to_delete->name; ?>"</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>آیا میخواهید این دسته بندی  را حذف کنید؟  <br> (در صورت حذف تمام زیر دسته ها و هشتگ های انها نیز حذف میشوند ) 
										</td>
										<td><input type="hidden" name="delete" value="1"></td>
									</tr>
									<tr><td><input class="btn btn-blue" type="submit" value="بله"></td></tr>
								</table>
							</form>
						</div>
						<?php
						break;
					case 'subcat':
						$subcat_to_delete = get_subcat_by_id($_GET['subcat_id']);
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							if($_POST["delete"]){
								delete_subcat($subcat_to_delete->id);
								$_SESSION["afected"] = array('afected'=>'deleted','deleted'=>'subcat','before'=>$subcat_to_delete->name);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">حذف زیر دسته "<?php echo $subcat_to_delete->name; ?>"</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>آیا میخواهید این زیردسته را حذف کنید؟</td>
										<td><input type="hidden" name="delete" value="1"></td>
									</tr>
									<tr><td><input class="btn btn-blue" type="submit" value="بله"></td></tr>
								</table>
							</form>
						</div>
						<?php
						break;
					case 'social_network':
						$social_network_to_delete = get_social_network_by_id($_GET['social_network_id']);
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							if($_POST["delete"]){
								delete_social_network($_GET['social_network_id']);
								$_SESSION["afected"] = array('afected'=>'deleted','deleted'=>'social_network','before'=>$social_network_to_delete);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">حذف  شبکه اجتماعی "<?php echo $social_network_to_delete; ?>"</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>آیا میخواهید این شبکه اجتماعی  را حذف کنید؟</td>
										<td><input type="hidden" name="delete" value="1"></td>
									</tr>
									<tr><td><input class="btn btn-blue" type="submit" value="بله"></td></tr>
								</table>
							</form>
						</div>
						<?php
						break;
					case 'hashtag':
						$hashtag_to_delete = get_hashtag_by_id($_GET['hashtag_id']);
						$hashtag_to_delete_subcat = get_subcat_by_id($hashtag_to_delete->subcat_id);
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							if($_POST["delete"]){
								delete_hashtag($hashtag_to_delete->id);
								$_SESSION["afected"] = array('afected'=>'deleted','deleted'=>'hashtag','before'=>$hashtag_to_delete->name,'before_subcat_id'=>$hashtag_to_delete_subcat->name);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">حذف  هشتگ  "<?php echo $hashtag_to_delete->name; ?>" از زیر دسته 
								"<?php echo $hashtag_to_delete_subcat->name; ?>"</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>آیا میخواهید این هشتگ را حذف کنید؟</td>
										<td><input type="hidden" name="delete" value="1"></td>
									</tr>
									<tr><td><input class="btn btn-blue" type="submit" value="بله"></td></tr>
								</table>
							</form>
						</div>
						<?php
						break;
					case 'city':
						$city_to_delete = get_city($_GET['city_id']);
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							if($_POST["delete"]){
								delete_social_network($city_to_delete->id);
								$_SESSION["afected"] = array('afected'=>'deleted','deleted'=>'city','before'=>$city_to_delete->name);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">حذف   شهر  "<?php echo $city_to_delete->name; ?>"</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>آیا میخواهید این  شهر  را حذف کنید؟</td>
										<td><input type="hidden" name="delete" value="1"></td>
									</tr>
									<tr><td><input class="btn btn-blue" type="submit" value="بله"></td></tr>
								</table>
							</form>
						</div>
						<?php
					case 'chanel':
						$chanel_to_delete = get_chanel($_GET['chanel_id']);
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							if($_POST["delete"]){
								delete_chanel($chanel_to_delete->chanel_id);
								$_SESSION["afected"] = array('afected'=>'deleted','deleted'=>'chanel','before'=>$chanel_to_delete->post_title);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">حذف   کانال  "<?php echo $chanel_to_delete->post_title; ?>"</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>آیا میخواهید این  کانال  را حذف کنید؟</td>
										<td><input type="hidden" name="delete" value="1"></td>
									</tr>
									<tr><td><input class="btn btn-blue" type="submit" value="بله"></td></tr>
								</table>
							</form>
						</div>
						<?php
						break;
					case 'story':
						$chanel = get_chanel($_GET['chanel_id']);
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							if($_POST["delete"]){
								deleteStory($chanel->id,$chanel->chanel_id);
								$_SESSION["afected"] = array('afected'=>'deleted','deleted'=>'story','before'=>$chanel->post_title);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">حذف   استوری  کانال  "<?php echo $chanel->post_title; ?>"</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>آیا میخواهید این  استوری  را حذف کنید؟</td>
										<td><input type="hidden" name="delete" value="1"></td>
									</tr>
									<tr><td><input class="btn btn-blue" type="submit" value="بله"></td></tr>
								</table>
							</form>
						</div>
						<?php
						break;
					case 'vip':
						$vip_to_delete = get_vip_offer($_GET['vip_id']);
						$type = null;
						if($vip_to_delete->time_type=="day"){$type = "روزه";}
						else if($vip_to_delete->time_type=="month"){$type = "ماهه";}
						else if($vip_to_delete->time_type=="year"){$type = "ساله";}
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							if($_POST["delete"]){
								delete_vip_offer($_GET['vip_id']);
								$_SESSION["afected"] = array('afected'=>'deleted','deleted'=>'vip','before'=>$vip_to_delete->count.' '.$type);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">حذف   وی ای پی  "<?php echo $vip_to_delete->count.' '.$type; ?>"</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>آیا میخواهید این  وی ای پی   را حذف کنید؟</td>
										<td><input type="hidden" name="delete" value="1"></td>
									</tr>
									<tr><td><input class="btn btn-blue" type="submit" value="بله"></td></tr>
								</table>
							</form>
						</div>
						<?php
						break;
				}
				break;
			case 'show':
				switch ($_GET['show']) {
					case 'story': $chanel = get_chanel($_GET['chanel_id']);?>
							<div class="form">
								<h2 class="form-title">استوری کانال "<?php echo $chanel->post_title; ?>"</h2>

								<span><i class="fas fa-calendar-alt"></i> :تا  <?php echo human_time_diff($chanel->story_time,time()); ?> دیگر </span>
								<br>
								<span><i class="fas fa-clock"></i> : <?php echo $chanel->story_duration; ?> ثانیه</span>
								<br>
								<a style="cursor: pointer;" onclick="ShowStory(this.id)" id="s">مشاهده استوری</a>
								<div class="story-box">
									<div class="story-duration"><hr <?php if(!empty($chanel->story_duration)) echo 'style="transition:'.$chanel->story_duration.'s all"' ?> ></div>
									<div class="story"><img src="<?php echo home_url().'/wp-content/uploads/story/'.$chanel->chanel_id."?ver=".rand(111,999); ?>"></div>
									<i class="close-story fas fa-times"></i>
								</div>
								<br>
								<a href="<?php echo home_url("wp-admin/admin.php").'?'.EditGETQuery(array(),
										array('page'=>'linkroobmgr','action'=>'delete','delete'=>'story','chanel_id'=>$chanel->chanel_id)); ?>" >حذف استوری</a>
							</div>
							<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-3.3.1.js" type="text/javascript"></script>
							<script type="text/javascript">
								 function ShowStory(Id){
									$("#"+Id).click(function(){
										$("#"+Id+" + .story-box").css({display:'block'});
										$("#"+Id+" + .story-box .story").addClass('img-story-size');
										$("#"+Id).parent().parent().parent().parent().css({'transform':'none'});
										$(".swiper-wrapper").css({'position':'inherit'});
										$(".swiper-container").css({'position':'inherit'});
										setTimeout(function(){
											$("#"+Id+" + .story-box .story-duration hr").css({width:"99.5%"});
											$("#"+Id+" + .story-box .close-story").click(function(){
												$("#"+Id+" + .story-box").css({display:'none'});
												$("#"+Id+" + .story-box .story-duration hr").css({width:0});
												$("#"+Id+" + .story-box .story").removeClass('img-story-size');
												$(".swiper-wrapper").css({'position':'relative'});
												$(".swiper-container").css({'position':'relative'});
											});
										},2);
										var intervalId = null;
										var check = function(){
											var hr_width = ($("#"+Id+" + .story-box .story-duration hr").width()) | 0;
											var all = (($("#"+Id+" + .story-box .story-duration").width() * 99)/100) | 0;
											console.log(hr_width);
											console.log(all);
											if(hr_width>=all){
												$("#"+Id+" + .story-box").css({display:'none'});
												$("#"+Id+" + .story-box .story-duration hr").css({width:0});
												$("#"+Id+" + .story-box .story").removeClass('img-story-size');
												$(".swiper-wrapper").css({'position':'relative'});
												$(".swiper-container").css({'position':'relative'});
												clearInterval(intervalId);
											}
										};
										intervalId = setInterval(check ,1000);
									});
								}
							</script>
						<?php
						break;
				}
			case 'upgradeOrvip':
				switch ($_GET['upgradeOrvip']) {
					case 'channel': $chanel = get_chanel($_GET['chanel_id']);?>
						<div class="form">
							<h2>فعال کردن vip برای کانال  <?php echo "'".$chanel->post_title."'"; ?></h2>
							<table>
								<?php 
									$vip_offers = get_vip_offers();
									foreach ($vip_offers as $key => $value) {
										$type = null;
										if($value->time_type=="day"){$type = "روزه";}
										else if($value->time_type=="month"){$type = "ماهه";}
										else if($value->time_type=="year"){$type = "ساله";}

										echo '<a style="font-size:1.2em" href="'.home_url("wp-admin/admin.php?page=linkroobmgr").'&'.EditGETQuery(array(),array('action'=>'upgradeOrvip','upgradeOrvip'=>'vip','chanel_id'=>$chanel->chanel_id,
										'vip_offer_id'=>$value->id)).'">'.
										$value->count.' '.$type.'</a><br><br>';
									}
								?>
							</table>
							<h2>ارتقا کانال <?php echo "'".$chanel->post_title."'"; ?></h2>
							<table>
								<?php 
									$upgrade_offer = get_upgrade_offer();
									
									echo '<a style="font-size:1.2em" href="'.home_url("wp-admin/admin.php?page=linkroobmgr").'&'.EditGETQuery(array(),array('action'=>'upgradeOrvip','upgradeOrvip'=>'upgrade',
									'chanel_id'=>$chanel->chanel_id)).'">
									ارتقاع کانال </a>';
									
								?>
							</table>
						</div>
					<?php
					break;
					case 'vip': 
						$chanel = get_chanel($_GET['chanel_id']);
						getVIP($chanel->chanel_id,$_GET['vip_offer_id']);
						$_SESSION["afected"] = array('afected'=>'vipOrupgrade','vipOrupgrade'=>'vip','chanel_id'=>$chanel->chanel_id,'vip_offer_id'=>$_GET['vip_offer_id']);
						wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
						break;
					case 'upgrade':
						$chanel = get_chanel($_GET['chanel_id']);
						upgrade($chanel->chanel_id);
						$_SESSION["afected"] = array('afected'=>'vipOrupgrade','vipOrupgrade'=>'upgrade','chanel_id'=>$chanel->chanel_id);
						wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
						break;
				}
				break;
			case 'publish':
				switch ($_GET['publish']) {
					case 'channel':
						$chanel = get_chanel($_GET['chanel_id'],'draft');
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							if($_POST["publish"]){
								publish_channel($chanel->post_id);
								$_SESSION["afected"] = array('afected'=>'published','chanel_id'=>$chanel->chanel_id);
								wp_redirect(home_url("wp-admin/admin.php?page=linkroobmgr")); exit;
							}
						}
						?>
						<div class="form">
							<h2 class="form-title">فعالسازی کانال  "<?php echo $chanel->post_title; ?>"</h2>
							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
								<table>
									<tr>
										<td>آیا میخواهید این  کانال  را فعال کنید؟</td>
										<td><input type="hidden" name="publish" value="1"></td>
									</tr>
									<tr><td><input class="btn btn-blue" type="submit" value="بله"></td></tr>
								</table>
							</form>
						</div>
						<?php
						break;
				}
				break;
			
			}
	}else{
		switch ($_SESSION["afected"]["afected"]) {
			case 'added':
				switch ($_SESSION["afected"]["added"]) {
					case 'cat':
						$added_cat = get_cat_by_id($_SESSION["afected"]["cat_id"]);
						echo '<div class="message-box m-green">دسته بندی  "'.$added_cat->name.'" اضافه شد </div>';
						break;
					case 'subcat':
						$added_subcat = get_subcat_by_id($_SESSION["afected"]["subcat_id"]);
						$added_subcat_cat = get_cat_by_id($added_subcat->cat_id);
						echo '<div class="message-box m-green">زیر دسته "'.$added_subcat->name.
						'" به دسته بندی "'.$added_subcat_cat->name.'" اضافه شد</div>';
						break;
					case 'social_network':
						$added_social_network = get_social_network_by_id($_SESSION["afected"]["social_network_id"]);
						echo '<div class="message-box m-green">شبکه اجتماعی  "'.$added_social_network.'" اضافه شد</div>';
						break;
					case 'hashtag':
						$added_hashtag = get_hashtag_by_id($_SESSION["afected"]["hashtag_id"]);
						$added_hashtag_subcat = get_subcat_by_id($added_hashtag->subcat_id);
						echo '<div class="message-box m-green">هشتگ  "'.$added_hashtag->name.'" به زیر دسته "'.$added_hashtag_subcat->name.'" اضافه شد</div>';
						break;
					case 'city':
						$added_city = get_city($_SESSION["afected"]["city_id"]);
						echo '<div class="message-box m-green">شهر  "'.$added_city->name.'" به لیست  اضافه شد</div>';
						break;
					case 'vip':
						$vip = get_vip_offer($_SESSION["afected"]["vip_id"]);
						$type = null;
						if($vip->time_type=="day"){$type = "روزه";}
						else if($vip->time_type=="month"){$type = "ماهه";}
						else if($vip->time_type=="year"){$type = "ساله";}
						echo '<div class="message-box m-green"> وی ای پی  '.$vip->count.' '.$type.' اضافه شد</div>';
						break;
				}
				break;
			case 'edited':
				switch ($_SESSION["afected"]["edited"]) {
					case 'cat':
						$edited_cat = get_cat_by_id($_SESSION["afected"]["cat_id"]);
						
						if($edited_cat->name != $_SESSION["afected"]["before"]["name"]&&
							$_SESSION["afected"]["before"]["city_filter_enable"]!=$edited_cat->city_filter_enable){
							
							$msg1 = '<div class="message-box m-yellow">فیلتر شهر دسته بندی  "'.$_SESSION["afected"]["before"]["name"].'"';
							if($edited_cat->city_filter_enable){$msg1.= " روشن شد";} else{$msg1.=" خاموش شد";}
							$msg1 .='</div>';
							$msg2 = '<div class="message-box m-yellow">عنوان دسته بندی "'.$_SESSION["afected"]["before"]["name"].'" به  "'.
							$edited_cat->name.'" تغییر پیدا کرد </div>';
							echo $msg1;
							echo $msg2;
						}else if($edited_cat->name != $_SESSION["afected"]["before"]["name"]){
							echo '<div class="message-box m-yellow">عنوان دسته بندی "'.$_SESSION["afected"]["before"]["name"].'" به  "'.
							$edited_cat->name.'" تغییر پیدا کرد </div>';
						}else if($_SESSION["afected"]["before"]["city_filter_enable"]!=$edited_cat->city_filter_enable){
							$msg = '<div class="message-box m-yellow">فیلتر شهر دسته بندی  "'.$_SESSION["afected"]["before"]["name"].'"';
							if($edited_cat->city_filter_enable){$msg.= " روشن شد";} else{$msg.=" خاموش شد";}
							$msg .='</div>';
							echo $msg;
						}
						break;
					case 'subcat':
						$edited_subcat = get_subcat_by_id($_SESSION["afected"]["subcat_id"]);
						echo '<div class="message-box m-yellow">زیر دسته "'.
						$_SESSION["afected"]["before"].'" به "'.$edited_subcat->name.'" تغییر پیدا کرد</div>';
						break;
					case 'social_network':
						$edited_social_network = get_social_network_by_id($_SESSION["afected"]["social_network_id"]);
						echo '<div class="message-box m-yellow">شبکه اجتماعی "'.
						$_SESSION["afected"]["before"].'" به "'.$edited_social_network.'" تغییر پیدا کرد</div>';
						break;
					case 'hashtag':
						$edited_hashtag = get_hashtag_by_id($_SESSION["afected"]["hashtag_id"]);
						$edited_hashtag_subcat = get_subcat_by_id($edited_hashtag->subcat_id);
						echo '<div class="message-box m-yellow">هشتگ  "'.
						$_SESSION["afected"]["before"].'" از زیر دسته "'.$edited_hashtag_subcat->name.
						'" به "'.$edited_hashtag->name.'" تغییر پیدا کرد</div>';
						break;
					case 'city':
						$edited_city = get_city($_SESSION["afected"]["city_id"]);
						echo '<div class="message-box m-yellow"> شهر  "'.
						$_SESSION["afected"]["before"].'" به "'.$edited_city->name.'" تغییر پیدا کرد</div>';
						break;
					case 'chanel':
						$edited_chanel = get_chanel($_SESSION["afected"]["chanel_id"]);
						echo '<div class="message-box m-yellow">کانال  "'.
						$edited_chanel->post_title.'" بروزرسانی شد</div>';
						break;
					case 'vip':
						$edited_vip = get_vip_offer($_SESSION["afected"]["vip_id"]);
						$before_vip = $_SESSION["afected"]["before"];

						$edited_vip_type = null;
						if($edited_vip->time_type=="day"){$edited_vip_type = "روزه";}
						else if($edited_vip->time_type=="month"){$edited_vip_type = "ماهه";}
						else if($edited_vip->time_type=="year"){$edited_vip_type = "ساله";}

						$edited_vip_currency = null;
						if($edited_vip->currency=="r"){$edited_vip_currency = "ریال";}
						else if($edited_vip->currency=="t"){$edited_vip_currency = "تومان";}

						$before_vip_type = null;
						if($before_vip->time_type=="day"){$before_vip_type = "روزه";}
						else if($before_vip->time_type=="month"){$before_vip_type = "ماهه";}
						else if($before_vip->time_type=="year"){$before_vip_type = "ساله";}

						$before_vip_currency = null;
						if($before_vip->currency=="r"){$before_vip_currency = "ریال";}
						else if($before_vip->currency=="t"){$before_vip_currency = "تومان";}

						if($edited_vip->currency!=$before_vip->currency||
							$edited_vip->price!=$before_vip->price){
							$msg1 = '<div class="message-box m-yellow">قیمت وی ای پی  <b>"'.$before_vip->count.' '.$before_vip_type.'"</b> از ';
							$msg1.= '<b>"'.$before_vip->price.' '.$before_vip_currency.'"</b> به '.' <b>"'.$edited_vip->price.' '.$edited_vip_currency.'"</b> ';
							$msg1.= ' تغییر پیدا کرد </div>';
						}
						if($edited_vip->time_type!=$before_vip->time_type||
							$edited_vip->count!=$before_vip->count){
							$msg2 = '<div class="message-box m-yellow">وی ای پی <b>"'.$before_vip->count.' '.$before_vip_type.'"</b> به ';
							$msg2.= '<b>"'.$edited_vip->count.' '.$edited_vip_type.'"</b> تغییر پیدا کرد </div>';
						}
						echo $msg1;echo $msg2;
						break;
					case 'upgrade':
						$edited_upgrade_offer = get_upgrade_offer();
						$before_upgrade_offer = $_SESSION["afected"]["before"];

						$edited_upgrade_offer_currency = null;
						if($edited_upgrade_offer->currency=="r"){$edited_upgrade_offer_currency = "ریال";}
						else if($edited_upgrade_offer->currency=="t"){$edited_upgrade_offer_currency = "تومان";}

						$before_upgrade_offer_currency = null;
						if($before_upgrade_offer->currency=="r"){$before_upgrade_offer_currency = "ریال";}
						else if($before_upgrade_offer->currency=="t"){$before_upgrade_offer_currency = "تومان";}

						echo '<div class="message-box m-yellow">قیمت ارتقاء از <b>"'.
						$before_upgrade_offer->price.' '.$before_upgrade_offer_currency.'"</b> به '.
						'<b>"'.$edited_upgrade_offer->price.' '.$edited_upgrade_offer_currency.'"</b> تغییر پیدا کرد</div>';

						break;
				}
				break;
			case 'deleted':
				switch ($_SESSION["afected"]["deleted"]) {
					case 'cat':echo '<div class="message-box m-red">دسته بندی  "'.$_SESSION["afected"]["before"].'" با تمام زیر دسته ها و هشتگ هایشان  حذف  شدند</div>';break;
					case 'subcat':echo '<div class="message-box m-red">زیر دسته "'.$_SESSION["afected"]["before"].'" حذف شد</div>';break;
					case 'social_network':echo'<div class="message-box m-red">شبکه اجتماعی "'.$_SESSION["afected"]["before"].'" حذف شد</div>';break;
					case 'hashtag':echo '<div class="message-box m-red">هشتگ  "'.$_SESSION["afected"]["before"].'"از زیر دسته "'.$_SESSION["afected"]["before_subcat_id"].'" حذف شد</div>';break;
					case 'city':echo '<div class="message-box m-red">شهر  "'.$_SESSION["afected"]["before"].'" حذف شد</div>';break;
					case 'chanel':echo '<div class="message-box m-red">کانال  "'.$_SESSION["afected"]["before"].'" حذف شد</div>';break;
					case 'story':echo '<div class="message-box m-red">استوری کانال  "'.$_SESSION["afected"]["before"].'" حذف شد</div>';break;
					case 'vip':echo '<div class="message-box m-red">وی ای پی "'.$_SESSION["afected"]["before"].'" حذف شد</div>';break;
				}
				break;
			case 'vipOrupgrade':
				switch ($_SESSION["afected"]["vipOrupgrade"]) {
					case 'vip':
						$chanel = get_chanel($_SESSION["afected"]["chanel_id"]);

						$vip_offer = get_vip_offer($_SESSION["afected"]["vip_offer_id"]);
						
						$type = null;
						if($vip_offer->time_type=="day"){$type = "روزه";}
						else if($vip_offer->time_type=="month"){$type = "ماهه";}
						else if($vip_offer->time_type=="year"){$type = "ساله";}
						

						echo '<div class="message-box m-green">وی ای پی '.$vip_offer->count.' '.$type.' برای کانال "'.$chanel->post_title.'" فعال شد</div>';
						break;
					case 'upgrade':
						$chanel = get_chanel($_SESSION["afected"]["chanel_id"]);
						echo '<div class="message-box m-green">کانال "'.$chanel->post_title.'" ارتقا یافت</div>';
						break;
				}
				break;
			case 'published':
				$chanel = get_chanel($_SESSION["afected"]["chanel_id"]);
				echo '<div class="message-box m-green">کانال "'.$chanel->post_title.'" فعال شد</div>';
				break;
		}
		$_SESSION["afected"] = null;
		?>
		<div class="tabs">
			<input type="radio" name="radio" class="tab-input" id="tab1" checked>
			<label for="tab1" class="tab-label"><h2>دسته بندی ها</h2></label>
			
			<input type="radio" name="radio" class="tab-input" id="tab2">
			<label for="tab2" class="tab-label"><h2>کانال ها</h2></label>
				
			<input type="radio" name="radio" class="tab-input" id="tab3">
			<label for="tab3"class="tab-label"><h2>قیمت ها</h2></label>
			
			<div class="tab-content tab-content-1">
				<div class="tabs">
					<?php 
						$cats = get_list_of_cat();
						$social_network = get_list_of_social_network();
						$cities = get_cities();
						#GET HASHTAGS
						$subcats_id = $wpdb->get_results("SELECT DISTINCT subcat_id from hashtags", ARRAY_A);
						$hashtags_subcats = $cat_hashtags_count =  array();
						$hashtags_count = 0;
						foreach($subcats_id as $id){
							$subcat = get_subcat_by_id($id["subcat_id"]);
							$hashtags_subcats[$subcat->cat_id][$subcat->id] = get_hashtags_by_subcat_id($subcat->id);

							if(empty($cat_hashtags_count[$subcat->cat_id])){$cat_hashtags_count[$subcat->cat_id] = 0;}
							$cat_hashtags_count[$subcat->cat_id] += count(get_hashtags_by_subcat_id($subcat->id));
						}
						foreach ($cat_hashtags_count as $key => $value){$hashtags_count += $value;}
					?>
					<input type="radio" name="radio-1" class="tab-input" id="tab1-1" checked>
					<label for="tab1-1" class="tab-label"><h2>دسته بندی  اصلی <?php echo '('.count($cats).')'; ?></h2></label>
					
					<input type="radio" name="radio-1" class="tab-input" id="tab1-2">
					<label for="tab1-2" class="tab-label"><h2>زیردسته <?php echo '('.get_subcats_count().')'; ?></h2></label>
						
					<input type="radio" name="radio-1" class="tab-input" id="tab1-3">
					<label for="tab1-3"class="tab-label"><h2>شبکه های اجتماعی <?php echo '('.count($social_network).')'; ?></h2></label>
					
					<input type="radio" name="radio-1" class="tab-input" id="tab1-4">
					<label for="tab1-4"class="tab-label"><h2>هشتگ ها <?php echo '('.$hashtags_count.')'; ?></h2></label>

					<input type="radio" name="radio-1" class="tab-input" id="tab1-5">
					<label for="tab1-5"class="tab-label"><h2>شهر ها <?php echo '('.count($cities).')'; ?></h2></label>

					<!--CATS-->
					<div class="tab-content tab-content-1-1">
						<table class="list">
							<tr>
								<td><h2>شماره</td>
								<td><h2>عنوان</h2></td>
								<td><div>
									<h2>فیلتر شهر</h2>
									<a href="
									<?php echo home_url("wp-admin/admin.php").'?'.EditGETQuery($_GET,array('action'=>'add','add'=>'cat')); ?>">اضافه کردن</a>
								</div></td>
							</tr>
							<?php foreach ($cats as $row){ ?>
								<tr class="list-row">
									<td><?php echo $row["id"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><div>
										<?php if($row["city_filter_enable"]){echo "روشن";} else{echo "خاموش";} ?>
										<div>
											<a href="<?php echo home_url("wp-admin/admin.php").'?'.
											EditGETQuery($_GET,array('action'=>'edit','edit'=>'cat','cat_id'=>$row["id"])); ?>">ویرایش</a>
											<a href="<?php echo home_url("wp-admin/admin.php").'?'.
											EditGETQuery($_GET,array('action'=>'delete','delete'=>'cat','cat_id'=>$row["id"])); ?>">حذف</a>
										</div>
									</div></td>
								</tr>
							<?php } ?>
						</table>
					</div>
					<!--SUBCATS-->
					<div class="tab-content tab-content-1-2">
						<a style="margin-bottom: 0.5em" href="<?php echo home_url("wp-admin/admin.php").'?'.EditGETQuery($_GET,array('action'=>'add','add'=>'subcat')); ?>">اضافه کردن</a>
						<?php foreach ($cats as $row){ $subcats = get_subcats_by_cat_id($row["id"]); 
							if(count($subcats)>0){ ?>
							<div class="tab-content-expand-box">
								<input type="checkbox" name="expand-1-2" class="expand-input" id="expand1-2-<?php echo $row["id"]; ?>">
								<label for="expand1-2-<?php echo $row["id"]; ?>" class="expand-label">
									<h2><?php echo $row["name"].' ('.count($subcats).')'; ?></h2>
									<div>
										<a href="<?php echo home_url("wp-admin/admin.php").'?'.
										EditGETQuery($_GET,array('action'=>'add','add'=>'subcat','cat_id'=>$row["id"])); ?>">اضافه کردن</a>
										<i class="fas fa-angle-down"></i>
									</div>
								</label>
								<div class="tab-content-expand">
									<table class="list">
										<tr>
											<td><h2>شماره</td>
											<td><h2>عنوان</td>
										</tr>
										<?php foreach ($subcats as $row){ ?>
											<tr class="list-row">
												<td><?php echo $row["id"]; ?></td>
												<td>
													<div>
														<?php echo $row["name"]; ?>
														<div>
															<a href="<?php echo home_url("wp-admin/admin.php").'?'.
															EditGETQuery($_GET,array('action'=>'edit','edit'=>'subcat','subcat_id'=>$row["id"])); ?>">ویرایش</a>
															<a href="<?php echo home_url("wp-admin/admin.php").'?'.
															EditGETQuery($_GET,array('action'=>'delete','delete'=>'subcat','subcat_id'=>$row["id"])); ?>">حذف</a>
														</div>
													</div>
												</td>	
											</tr>
										<?php } ?>
									</table>
								</div>
							</div>
						<?php }} ?>
					</div>
					<!--SOCIAL NETWORK-->
					<div class="tab-content tab-content-1-3">
						<table class="list">
							<tr>
								<td><h2>شماره</td>
								<td><div>
									<h2>عنوان</h2>
									<a href="
									<?php echo home_url("wp-admin/admin.php").'?'.
									EditGETQuery($_GET,array('action'=>'add','add'=>'social_network')); ?>">اضافه کردن</a>
								</div></td>
							</tr>
							<?php foreach ($social_network as $row){ ?>
								<tr class="list-row">
									<td><?php echo $row["id"]; ?></td>
									<td>
										<div>
											<?php echo $row["name"]; ?>
											<div>
												<a href="<?php echo home_url("wp-admin/admin.php").'?'.
												EditGETQuery($_GET,array('action'=>'edit','edit'=>'social_network','social_network_id'=>$row["id"])); ?>">ویرایش</a>
												<a href="<?php echo home_url("wp-admin/admin.php").'?'.
												EditGETQuery($_GET,array('action'=>'delete','delete'=>'social_network','social_network_id'=>$row["id"])); ?>">حذف</a>
											</div>
										</div>
									</td>	
								</tr>
							<?php } ?>
						</table>
					</div>
					<!--HASHTAGS-->
					<div class="tab-content tab-content-1-4">
						<a style="margin-bottom: 0.5em" href="<?php echo home_url("wp-admin/admin.php").'?'.EditGETQuery($_GET,array('action'=>'add','add'=>'hashtag')); ?>">اضافه کردن</a>
						<?php 
							foreach ($hashtags_subcats as $cat_id => $subcat) {?>
								<div class="tab-content-expand-box">
									<input type="checkbox" name="expand-1-4" class="expand-input" id="expand1-4-<?php echo $cat_id; ?>">
									<label for="expand1-4-<?php echo $cat_id; ?>" class="expand-label">
										<h2><?php echo get_cat_by_id($cat_id)->name.' ('.$cat_hashtags_count[$cat_id].')'; ?></h2>
										<div>
											<a href="<?php echo home_url("wp-admin/admin.php").'?'.
											EditGETQuery($_GET,array('action'=>'add','add'=>'hashtag','cat_id'=>$cat_id)); ?>">اضافه کردن</a>
											<i class="fas fa-angle-down"></i>
										</div>
									</label>
									<div class="tab-content-expand">
										<?php foreach ($subcat as $subcatkey => $hashtags) { ?>
											<div class="expand-content-expand-box">
												<input type="checkbox" name="expand-1-4<?php echo $cat_id; ?>" 
												class="expand-input" id="expand1-4-<?php echo $cat_id.'-'.$subcatkey; ?>">
												<label for="expand1-4-<?php echo $cat_id.'-'.$subcatkey; ?>" class="expand-label">
													<h2><?php echo get_subcat_by_id($subcatkey)->name.' ('.count($hashtags).')'; ?></h2>
													<div>
														<a href="<?php echo home_url("wp-admin/admin.php").'?'.
														EditGETQuery($_GET,array('action'=>'add','add'=>'hashtag','cat_id'=>$cat_id,'subcat_id'=>$subcatkey)); ?>">اضافه کردن</a>
														<i class="fas fa-angle-down"></i>
													</div>
												</label>
												<div class="expand-content-expand">
													<table class="list">
														<tr>
															<td><h2>شماره</td>
															<td><h2>عنوان</td>
														</tr>
														<?php foreach ($hashtags as $hashtagkey => $hashtagvalue){ ?>
															<tr class="list-row">
																<td><?php echo ($hashtagkey+1); ?></td>
																<td>
																	<div>
																		<?php echo $hashtagvalue["name"]; ?>
																		<div>
																			<a href="<?php echo home_url("wp-admin/admin.php").'?'.
																			EditGETQuery($_GET,
																				array('action'=>'edit',
																					'edit'=>'hashtag',
																					'hashtag_id'=>$hashtagvalue["id"])
																			); ?>">ویرایش</a>
																			<a href="<?php echo home_url("wp-admin/admin.php").'?'.
																			EditGETQuery($_GET,
																				array('action'=>'delete',
																					'delete'=>'hashtag',
																					'hashtag_id'=>$hashtagvalue["id"])
																			); ?>">حذف</a>
																		</div>
																	</div>
																</td>	
															</tr>
														<?php } ?>
													</table>
												</div>
											</div>
										<?php } ?>
									</div>
								</div>
						<?php } ?>		
					</div>
					<!--CITIES-->
					<div class="tab-content tab-content-1-5">
						<table class="list">
							<tr>
								<td><h2>شماره</td>
								<td><div>
									<h2>عنوان</h2>
									<a href="
									<?php echo home_url("wp-admin/admin.php").'?'.EditGETQuery($_GET,array('action'=>'add','add'=>'city')); ?>">اضافه کردن</a>
								</div></td>
							</tr>
							<?php 
							$i = 1;
							foreach ($cities as $row){ ?>
								<tr class="list-row">
									<td><?php echo $i; ?></td>
									<td>
										<div>
											<?php echo $row["name"]; ?>
											<div>
												<a href="<?php echo home_url("wp-admin/admin.php").'?'.
												EditGETQuery($_GET,array('action'=>'edit','edit'=>'city','city_id'=>$row["id"])); ?>">ویرایش</a>
												<a href="<?php echo home_url("wp-admin/admin.php").'?'.
												EditGETQuery($_GET,array('action'=>'delete','delete'=>'city','city_id'=>$row["id"])); ?>">حذف</a>
											</div>
										</div>
									</td>	
								</tr>
							<?php $i++;} ?>
						</table>
					</div>
				</div>
			</div>
			<div class="tab-content tab-content-2">
				<?php $posts = get_chanels(0); ?>
				<table class="list">
					<tr class="list-chanels-types">
						<td><h2>شماره</h2></td>
						<td><h2>عکس</h2></td>
						<td><h2>نام</h2></td>
						<td><h2>آدرس</h2></td>
						<td><h2>دسته بندی</h2></td>
						<td><h2>کاربرثبت کننده</h2></td>
						<td><h2>نوع ثبت</h2></td>
						<td>
							<a href="<?php echo home_url("add"); ?>">اضافه کردن</a>
						</td>
					</tr>
					<?php $i=1;foreach ($posts as $key => $post){ ?>
						<tr class="list-row chanel">
							<td><?php echo $i; ?></td>
							<td><img width="75px" src="<?php echo home_url().'/wp-content/uploads/img/'.$post->chanel_id.'.jpg'; ?>" alt></td>	
							<td><a href="<?php echo $post->guid; ?>"><?php echo $post->post_title ?></a></td>
							<td><a style="direction: ltr;" target="_blank" href="<?php echo $post->joinchat_url; ?>"><?php echo $post->chanel_id; ?></a></td>
							<td><p><?php echo get_cat_by_id($post->cat_id)->name ?><br><?php echo get_subcat_by_id($post->subcat_id)->name ?><p></td>
							<td><?php echo get_user_by('id',$post->post_author)->display_name;  ?></td>
							<td>
								<?php
									if($post->post_status=="publish"){
										$free = 1;
										if($post->vip_date>time()){
											echo ' vip برای '.human_time_diff($post->vip_date,time());$free = 0;
											if($post->upgrade){echo '<hr style="width: 80%;border: 0.5px solid #ababab;margin: 2px auto;">';}
										}
										if($post->upgrade){echo "ارتقاع یافته";$free = 0;}
										if($free){echo 'رایگان';}
									}else if($post->post_status=="draft"){
										echo '<span>در انتظار فعالسازی<br/> توسط مدیر</span> <hr style="width: 80%;border: 0.5px solid #ababab;margin: 2px auto;">';
										?>
											<a style="margin:2px;" href="<?php echo home_url("wp-admin/admin.php?page=linkroobmgr").'&'.
											EditGETQuery(array(),array('action'=>'publish','publish'=>'channel','chanel_id'=>$post->chanel_id)); ?>">فعالسازی</a>
										<?php 
									}
							  		
							  	?>
							</td>
							<td>
								<div style="display:inline-flex;flex-direction: column;padding: 0.25em 0">
									<a style="margin:2px;" href="<?php echo home_url("user-panel").'?'.
									EditGETQuery(array(),array('action'=>'edit','edit'=>'chanel','chanel_id'=>$post->chanel_id,'redirect_url'=>home_url("wp-admin/admin.php?page=linkroobmgr"))); ?>">ویرایش</a>
									<a style="margin:2px;" href="<?php echo home_url("wp-admin/admin.php").'?'.
									EditGETQuery($_GET,array('action'=>'delete','delete'=>'chanel','chanel_id'=>$post->chanel_id)); ?>">حذف</a>
									<a style="margin:2px;" href="<?php echo home_url("wp-admin/admin.php").'?'.
									EditGETQuery($_GET,array('action'=>'upgradeOrvip','upgradeOrvip'=>'channel','chanel_id'=>$post->chanel_id)); ?>">ویژه کردن و ارتقا</a>
								</div>
								<div style="display:inline-flex;flex-direction: column;padding: 0.25em 0">
									<?php if($post->upgrade){ 
									 	$events = get_events($post->id);
									 	if(count($events)>0){ ?>
											<a style="margin:2px;" href="<?php echo home_url("user-panel").'?'.EditGETQuery(array(),
											array('action'=>'get','get'=>'events','chanel_id'=>$post->chanel_id,)); ?>">پست ها</a>
									<?php }} ?>
									<?php if($post->vip_date>time()&&time()<$post->story_time){ ?>
										<a style="margin:2px;" href="<?php echo home_url("wp-admin/admin.php").'?'.EditGETQuery($_GET,
										array('action'=>'show','show'=>'story','chanel_id'=>$post->chanel_id)); ?>">استوری</a>
									<?php } ?>
								</div>
							</td>
						</tr>
					<?php $i++;} ?>
				</table>
			</div>
			<div class="tab-content tab-content-3">
				<h2>VIP : </h2>
				<table class="list">
					<tr>
						<td><h2>شماره</td>
						<td><h2>عنوان</h2></td>
						<td><div>
							<h2>قیمت</h2>
							<a href="
							<?php echo home_url("wp-admin/admin.php").'?'.EditGETQuery($_GET,array('action'=>'add','add'=>'vip')); ?>">اضافه کردن</a>
						</div></td>
					</tr>
					<?php $i=1; $vip_offers = get_vip_offers();
						foreach ($vip_offers as $key => $value){ 
							$type = null;
							if($value->time_type=="day"){$type = "روزه";}
							else if($value->time_type=="month"){$type = "ماهه";}
							else if($value->time_type=="year"){$type = "ساله";}

							$currency = null;
							if($value->currency=="r"){$currency = "ریال";}
							else if($value->currency=="t"){$currency = "تومان";}
							?>
							<tr class="list-row offer">
								<td><?php echo $i; ?></td>
								<td><?php echo $value->count.' '.$type; ?></td>	
								<td><div><?php echo $value->price.' '.$currency ?>
									<div>
										<a href="<?php echo home_url("wp-admin/admin.php").'?'.
										EditGETQuery($_GET,array('action'=>'edit','edit'=>'vip','vip_id'=>$value->id)); ?>">ویرایش</a>
										<a href="<?php echo home_url("wp-admin/admin.php").'?'.
										EditGETQuery($_GET,array('action'=>'delete','delete'=>'vip','vip_id'=>$value->id)); ?>">حذف</a>
									</div>
								</div></td>	
							</tr>
					<?php $i++;} ?>
				</table>
				<h2>ارتقاع : </h2>
				<table class="list">
					<tr>
						<td><h2>عنوان</h2></td>
						<td><h2>قیمت</h2></td>
					</tr>
					<?php $upgrade_offer = get_upgrade_offer();
						$currency = null;
						if($upgrade_offer->currency=="r"){$currency = "ریال";}
						else if($upgrade_offer->currency=="t"){$currency = "تومان";}
						?>
						<tr class="list-row offer">
							<td>ارتقاع</td>	
							<td><div><?php echo $upgrade_offer->price.' '.$currency; ?>
								<div>
									<a href="<?php echo home_url("wp-admin/admin.php").'?'.
									EditGETQuery($_GET,array('action'=>'edit','edit'=>'upgrade')); ?>">ویرایش</a>
								</div>
							</div></td>	
						</tr>
				</table>
			</div>
		</div>
	<?php } ?> 
</body>
</html>
