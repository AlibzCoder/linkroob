<?php /* Template Name: addpage */
require('inc/recaptcha/constant.php');
if (session_status() == PHP_SESSION_NONE)
	session_start();

get_header();

$everyThingOK = 1;
$registerOK = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$captchaError = null;
	$catError = null;
	$subcatError = null;
	$social_networkError = null;
	$titleError = null;
	$chanel_idError = null;
	$imageError = null;
	
	$sn = null;
	$cat = null;
	$subcat = null;
	$hashtags = null;
	$city = null;
	$title = null;
	$chanel_id = null;
	$joinchat_url = null;
	$description = null;
	
	$usernameError = null;
	$username = null;
	$emailError = null;
	$email = null;
	$pwd1 = null;
	$pwd2 = null;
	$pwdError = null;
	$captchaError = null;
	$registerError = null;

	if(!empty($_POST["social_network"])){$sn = (int) $_POST["social_network"];}
	else{
		$social_networkError = "لطفا شبکه اجتماعی کانال خود رو انتخاب وارد کنید";
		$everyThingOK = 0;
	}

	if(!empty($_POST["cat"])){$cat = $_POST["cat"];}
	else{
		$catError = "لطفا دسته بندی را انتخاب کنید";
		$everyThingOK = 0;
	}

	if(!empty($_POST["subcat"])) $subcat = $_POST["subcat"];
	else{
		$subcatError = "لطفا زیر دسته را انتخاب کنید";
		$everyThingOK = 0;
	}

	if(!empty($subcat)&&count(get_hashtags_by_subcat_id($subcat))>0){
		if(!empty($_POST["hashtags"])) $hashtags = $_POST["hashtags"];
	}
	if(!empty($cat)&&!get_cat_by_id($cat)->city_filter_enable){
		if(!empty($_POST["city"])) $city = $_POST["city"];
	}
	
	
	if(!empty($_POST["title"])) $title = $_POST["title"];
	else{
		$titleError = "لطفا عنوان کانال خود را وارد کنید";
		$everyThingOK = 0;
	}

	

	

	if(!empty($_POST["chanel_id"])){
		if (!preg_match("/[^A-Za-z0-9\@\_]/", $_POST["chanel_id"])){
			$chanel_id = ($_POST["chanel_id"][0]!="@") ? "@".$_POST["chanel_id"] : $_POST["chanel_id"];
			if(is_chanel_exists($chanel_id)){
				$chanel_idError = "کانالی دیگر قبلا با همین ایدی ثبت شده است";
				$everyThingOK = 0;
			}
		}else{
			$chanel_idError = "لطفا ایدی را درست وارد کنید";
			$everyThingOK = 0;
		}
	}else{
		if($cat==4){
			$chanel_id = uniqid('@');//create random id 
		}else{
			$chanel_idError = "لطفا ایدی کانال خود را وارد کنید";
			$everyThingOK = 0;
		}
	}

	
	if(!empty($_POST["joinchat_url"])){
		if(check_url($_POST["joinchat_url"])){
			
		}else{
			#get link from id
		}
		$joinchat_url = $_POST["joinchat_url"];
	}else{
		if($cat==4){
			$joinchat_urlError = "لطفا لینک اشتراک گروه خود را وارد کنید";
			$everyThingOK = 0;
		}
	}




	if(!empty($_POST["description"])) $description = trim($_POST["description"]);
	
	if(!is_user_logged_in()){
		$username = $wpdb->escape(trim($_POST['user']));
		$email = $wpdb->escape(trim($_POST['email']));
		$pwd1 = $wpdb->escape(trim($_POST['pwd1']));
		$pwd2 = $wpdb->escape(trim($_POST['pwd2']));
		if(!empty($username)){
			if (preg_match('/[^A-Za-z0-9]/', $username)){
				$usernameError = "لطفا در نام کاربری فقط از حروف انگلیسی و اعداد استفاده کنید";
				$registerOK = 0;
			}else if(username_exists($username)){
				$usernameError = "نام کاربری قبلا ثبت شده است";
				$registerOK = 0;
			}
		}else{
			$usernameError = "لطفا نام کاربری را وارد کنید" ;
			$registerOK = 0;
		}
		
		if(!empty($email)){
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$emailError = "لطفا ایمیل معتبر وارد کنید";
				$registerOK = 0;
			}else if(email_exists($email)){
				$emailError = "این ایمیل قبلا ثبت شده است";
				$registerOK = 0;
			}
		}else{
			$emailError = "لطفا ایمیل خود را وارد کنید";
			$registerOK = 0;
		}
		if(empty($pwd1)||empty($pwd2)){
			$pwdError = "لطفا رمز عبور را وارد کنید";
			$registerOK = 0;
		}else if($pwd1<>$pwd2){
			$pwdError = "رمز های عبور با یک دیگر مطابقت ندارند";
			$registerOK = 0;
		}
	}
	
	
	if (isset($_POST['g-recaptcha-response'])) {
		
		require('inc/recaptcha/src/autoload.php');		
		
		$recaptcha = new \ReCaptcha\ReCaptcha(SECRET_KEY);

		$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

		if ($resp->isSuccess()) {
			if(!is_user_logged_in()&&$registerOK){
				if(!register($email,$username,$pwd2)){
					$registerError = "متاسفانه خطایی در در ثبت نام شما پیش امده است";
					$everyThingOK = 0;
				}else{
					log_in($username,$pwd2);
				}
			}
			if($everyThingOK){
				$imageError = upload_img($_FILES["chanel_img"],$chanel_id);
				if(empty($imageError)){
					$status = current_user_can('administrator') ? 'publish' : 'draft';
					add_chanel($title,$sn,$cat,$subcat,$chanel_id,$joinchat_url,$description,$hashtags,$city,$status);
					wp_redirect(home_url('user-panel'));
					exit;
				}else{
					$everyThingOK = 0;
				}
			}
		}else{
			$captchaError = "لطفا مشخص کنید که ربات نیستید";
		}
	}else{
		$captchaError = "لطفا مشخص کنید که ربات نیستید";
	}
}

?>
<div id="article">
	<div id="continer">
		<div class="catview">
			<div>
				<?php if(is_user_logged_in()){ ?>
					<h2>اضافه کردن</h2>
				<?php }else{ ?>
					<h2>ثبت نام و اضافه کردن</h2>
				<?php } ?>
			</div>
			<hr>
		</div>
		<div class="form">
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
				<table>
					<tr>
						<td>
							<span class="important">*</span>
							انتخاب شبکه اجتماعی :
						</td>
						<td>
							<div id="form_social_network_select" class="form-chosen">
							  <select data-placeholder="انتخاب" class="select2">
								<option></option>
								  <?php
									  $social_network_list = get_list_of_social_network();
									  foreach ($social_network_list as $row){
										  if(($everyThingOK==0)&&$row["id"]==$sn){
											echo '<option selected value="'.$row["id"].'">'.$row["name"].'</option>';
										  }else{
											echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
										  }
									  }
								  ?>
							  </select>
							  <?php 
								if(($everyThingOK==0)&&(!empty($sn))){
									echo '<input type="text" style="display:none;" value="'.$sn.'" name="social_network" class="input"/>';
								}else{
									echo '<input type="text" style="display:none;" name="social_network" class="input"/>';
								}
							  ?>
							</div>
							<?php if(!empty($social_networkError)) echo '<span class="error">'.$social_networkError.'</span>'; ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="important">*</span>
							انتخاب دسته بندی :
						</td>
						<td>
							<div id="form_category_select" class="form-chosen">
							  <select data-placeholder="انتخاب" class="select2">
								<option></option>
								  <?php
								  $cat_list = get_list_of_cat();
								  foreach ($cat_list as $row){
									if(($everyThingOK==0)&&$row["id"]==$cat){
										echo '<option value="'.$row["id"].'" selected>'.$row["name"].'</option>';
									}else{
										echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
									}
								  }
								  ?>
							  </select>
							  <input type="text" style="display:none;" <?php if(($everyThingOK==0)&&(!empty($cat))) echo 'value="'.$cat.'"' ?>  name="cat" class="input"/>
							</div>
							<?php if(!empty($catError)) echo '<span class="error">'.$catError.'</span>'; ?>
						</td>
					</tr>
					<tr <?php if(empty($cat)) echo 'style="display:none"'; ?> >
						<td>
							<span class="important">*</span>
							انتخاب زیر دسته :
						</td>
						<td>
							<div id="form_category_children_select" class="form-chosen">
							  <select data-placeholder="انتخاب" class="select2">
								<option></option>
								<?php
									if(!empty($cat)){
										foreach (get_subcats_by_cat_id($cat) as $row){
											if(($everyThingOK==0)&&$row["id"]==$subcat){
												echo '<option value="'.$row["id"].'" selected>'.$row["name"].'</option>';
											}else{
												echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
											}
										}
									}
								?>
							  </select>
							  <input type="text" style="display:none;" <?php if(($everyThingOK==0)&&!empty($subcat)) echo 'value="'.$subcat.'"' ?> name="subcat" class="input"/>
							</div>
							<?php if(!empty($subcatError)) echo '<span class="error">'.$subcatError.'</span>'; ?>
						</td>
					</tr>
					<tr <?php if(empty($subcat)||(!empty($subcat)&&count(get_hashtags_by_subcat_id($subcat))>0)) echo 'style="display:none"'; ?>>
						<td>انتخاب هشتگ :</td>
						<td>
							<div id="form_hashtag_select" class="form-chosen">
								<select data-placeholder="هشتگ" multiple class="select2">
									<option></option>
									<?php
										if(!empty($subcat)){
											$h = get_hashtags_by_subcat_id($subcat);
											if(!empty($hashtags)){
												$hashtags = explode(',',$hashtags);
												foreach ($h as $row){
													$hashtag_selected_id = 0;
													for($i=0;$i<=count($hashtags)-1;$i++){
														if($row["id"]==$hashtags[$i]){
															$hashtag_selected_id = $hashtags[$i];
														}
													}
													if(($everyThingOK==0)&&$row["id"]==$hashtag_selected_id){
														echo '<option value="'.$row["id"].'" selected>'.$row["name"].'</option>';
													}else{
														echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
													}
													
												}
											}
										}
									?>
								</select>
								<input type="text" style="display:none;" name="hashtags" class="input"/>
							</div>
						</td>
					</tr>
					
					<tr <?php if(empty($cat)||(!empty($cat)&&!get_cat_by_id($cat)->city_filter_enable)) echo 'style="display:none;"'; ?>>
						<td>انتخاب شهر :</td>
						<td>
							<div id="form_select_city" class="form-chosen">
							  <select class="select2" data-placeholder="انتخاب">
								<option></option>
								<?php
									$cities = get_cities();
									foreach ($cities as $row){
									  if($row["id"]==$city){
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
							<span class="important">*</span>
							عنوان :
						</td>
						<td>
							<div class="inputbox">
								<input type="text" class="inputbox-input" <?php if($everyThingOK==0&&(!empty($title))) echo 'value="'.$title.'"' ?> placeholder="عنوان" name="title">
								<div class="inputbox-hr"><hr></div>
							</div>
							<?php if(!empty($titleError)) echo '<span class="error">'.$titleError.'</span>'; ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="channel-id-important important">*</span>
							ایدی (یوزرنیم) :
						</td>
						<td>
							<div class="inputbox" style="direction: ltr;">
								<input type="text" class="inputbox-input" <?php if($everyThingOK==0&&(!empty($chanel_id))) echo 'value="'.$chanel_id.'"' ?> placeholder="@LinkRoob" name="chanel_id">
								<div class="inputbox-hr"><hr></div>
							</div>
							<?php if(!empty($chanel_idError)) echo '<span class="error">'.$chanel_idError.'</span>'; ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="join-chat-important important" style="display:none;">*</span>
							لینک اشتراک (join chat) :
						</td>
						<td>
							
							<div class="inputbox" style="direction: ltr;">
								<input type="text" class="inputbox-input"
								<?php if($everyThingOK==0&&(!empty($joinchat_url))) echo 'value="'.$joinchat_url.'"' ?>
								placeholder="https://t.me/LinkRoob" name="joinchat_url"/>
								<div class="inputbox-hr"><hr></div>
							</div>
							<?php if(!empty($joinchat_urlError)) echo '<span class="error">'.$joinchat_urlError.'</span>'; ?>
						</td>
					</tr>
					<tr>
						<td>
							توضیحات :
						</td>
						<td>
							<div class="inputbox">
								<textarea style="max-width: 250px;max-height: 200px;" class="inputbox-input" name="description"><?php if($everyThingOK==0&&(!empty($description))) echo $description ?></textarea>
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

					<?php if(!is_user_logged_in()){ ?>
						<tr>
							<td>
							<span class="important" style="margin-right:-0.5em;">*</span>
								نام کاربری :
							</td>
							<td>
								<div class="inputbox">
									<input type="text" class="inputbox-input" <?php if($registerOK==0&&(!empty($username))) echo 'value="'.$username.'"' ?> placeholder="نام کاربری" name="user">
									<div class="inputbox-hr"><hr></div>
								</div>
								<?php if(!empty($usernameError)) echo '<span class="error">'.$usernameError.'</span>'; ?>
							</td>
						</tr>
						<tr>
							<td>
							<span class="important" style="margin-right:-0.5em;">*</span>
							ایمیل :
							</td>
							<td>
								<div class="inputbox">
									<input type="email" class="inputbox-input" <?php if($registerOK==0&&(!empty($email))) echo 'value="'.$email.'"' ?> placeholder="ایمیل" name="email">
									<div class="inputbox-hr"><hr></div>
								</div>
								<?php if(!empty($emailError)) echo '<span class="error">'.$emailError.'</span>'; ?>
							</td>
						</tr>
						<tr>
							<td>
								<span class="important" style="margin-right:-0.5em;">*</span>
								رمز عبور :
							</td>
							<td>
								<div class="inputbox">
									<input type="password" class="inputbox-input" placeholder="رمز عبور" name="pwd1">
									<div class="inputbox-hr"><hr></div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<span class="important" style="margin-right:-0.5em;">*</span>
								 تکرار رمز عبور :
							</td>
							<td>
								<div class="inputbox">
									<input type="password" class="inputbox-input" placeholder="رمز عبور" name="pwd2">
									<div class="inputbox-hr"><hr></div>
								</div>
							</td>
						</tr>
					<?php } ?>

					<tr>
						<td>
							<div class="g-recaptcha" data-sitekey="<?php echo SITE_KEY; ?>"></div>
						</td>
						<td>
							<?php if(!empty($captchaError)) echo'<span class="error">'.$captchaError.'</span>'  ?>
						</td>
					</tr>
					<tr>
						<td>
							<input class="btn btn-blue" type="submit" value="ثبت">
							<?php if(!is_user_logged_in()){ ?>
								<a href="<?php echo home_url('log-in').'/?redirect_url='.home_url('add'); ?>" style="margin:0 10px">ورود</a>
							<?php } ?>
							<?php
								if(!empty($pwdError)){
									echo '<span class="error">'.$pwdError.'</span>';
								}else if(!empty($registerError)){
									echo '<span class="error">'.$registerError.'</span>';
								}
							?>
						</td>
					</tr>
				</table>
			</form>
		</div>
<script src="<?php echo get_template_directory_uri() ?>/js/jquery-3.3.1.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri() ?>/lib/select2/select2.js"></script>
<script>
<?php
	echo "var subcats = ". json_encode(get_list_of_subcats()) . ";";
	echo "var hashtags = ". json_encode(get_hashtags()) . ";";
	echo "var selectedSubCat = null;";
	if(($everyThingOK==0)&&!empty($subcat))echo 'selectedSubCat='.$subcat;
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

		<?php
			if($everyThingOK==0){
				if(!empty($cat)){
					if($cat==4){
						echo '$(".channel-id-important").css("display","none");$(".join-chat-important").css("display","inline");';
					}else{
						echo '$(".channel-id-important").css("display","inline");$(".join-chat-important").css("display","none");';
					}
				}
			}
		?>

		$("#form_social_network_select .select2").on("select2-selecting", function(e){$("#form_social_network_select .input").val(e.val);});
		$("#form_category_select .select2").on("select2-selecting", function(e){
			$("#form_category_select .input").val(e.val);
			$("#form_hashtag_select").parent().parent().css('display','none');
			if(subcats[e.val][1]=="1"){
				$("#form_select_city").parent().parent().css('display','table-row');
			}else{
				$("#form_select_city").parent().parent().css('display','none');
			}

			if(e.val=="4"){
				$(".channel-id-important").css('display','none');
				$(".join-chat-important").css('display','inline');
			}else{
				$(".channel-id-important").css('display','inline');
				$(".join-chat-important").css('display','none');
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
						a.push(hashtags[selectedSubCat][k]['name']);
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


<?php get_footer(); ?>
