<?php /* Template Name: sign-in */
	require('inc/recaptcha/constant.php');

	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	get_header();
	$usernameError = null;
	$username = null;
	$emailError = null;
	$email = null;
	$pwd1 = null;
	$pwd2 = null;
	$pwdError = null;
	$captchaError = null;
	$registerError = null;
	$everyThingOK = 1;
	
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {



		if (isset($_POST['g-recaptcha-response'])) {
		
			require('inc/recaptcha/src/autoload.php');		
			
			$recaptcha = new \ReCaptcha\ReCaptcha(SECRET_KEY);
	
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
	
			if ($resp->isSuccess()) {
				$username = $wpdb->escape(trim($_POST['user']));
				$email = $wpdb->escape(trim($_POST['email']));
				$pwd1 = $wpdb->escape(trim($_POST['pwd1']));
				$pwd2 = $wpdb->escape(trim($_POST['pwd2']));
				
				if(!empty($username)){
					if (preg_match('/[^A-Za-z0-9]/', $username)){
						$usernameError = "لطفا در نام کاربری فقط از حروف انگلیسی و اعداد استفاده کنید";
						$everyThingOK = 0;
					}else if(username_exists($username)){
						$usernameError = "نام کاربری قبلا ثبت شده است";
						$everyThingOK = 0;
					}
				}else{
					$usernameError = "لطفا نام کاربری را وارد کنید" ;
					$everyThingOK = 0;
				}
				
				if(!empty($email)){
					if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
						$emailError = "لطفا ایمیل معتبر وارد کنید";
						$everyThingOK = 0;
					}else if(email_exists($email)){
						$emailError = "این ایمیل قبلا ثبت شده است";
						$everyThingOK = 0;
					}
				}else{
					$emailError = "لطفا ایمیل خود را وارد کنید";
					$everyThingOK = 0;
				}
				if(empty($pwd1)||empty($pwd2)){
					$pwdError = "لطفا رمز عبور را وارد کنید";
					$everyThingOK = 0;
				}else if($pwd1<>$pwd2){
					$pwdError = "رمز های عبور با یک دیگر مطابقت ندارند";
					$everyThingOK = 0;
				}

				if($everyThingOK){
					if(!register($email,$username,$pwd2)){
						$registerError = "متاسفانه خطایی در در ثبت نام شما پیش امده است";
					}else{
						$Error = log_in($username,$pwd2);
						if($Error == "invalid_username"){
							$Error = "نام کاربری یا کلمه عبور نادرست است";
						}else if(empty($Error)){
							wp_redirect(home_url('user-panel'));
							exit;
						}
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
		<h2>ثبت نام</h2>
	</div>
	<hr>
</div>
<div class="form">
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
		<table>
			<tr>
				<td>
					نام کاربری :
				</td>
				<td>
					<div class="inputbox">
						<input type="text" class="inputbox-input" <?php if($everyThingOK==0&&(!empty($username))) echo 'value="'.$username.'"' ?> placeholder="نام کاربری" name="user">
						<div class="inputbox-hr"><hr></div>
					</div>
					<?php if(!empty($usernameError)) echo '<span class="error">'.$usernameError.'</span>'; ?>
				</td>
			</tr>
			<tr>
				<td>
				ایمیل :
				</td>
				<td>
					<div class="inputbox">
						<input type="email" class="inputbox-input" <?php if($everyThingOK==0&&(!empty($email))) echo 'value="'.$email.'"' ?> placeholder="ایمیل" name="email">
						<div class="inputbox-hr"><hr></div>
					</div>
					<?php if(!empty($emailError)) echo '<span class="error">'.$emailError.'</span>'; ?>
				</td>
			</tr>
			<tr>
				<td>
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
					 تکرار رمز عبور :
				</td>
				<td>
					<div class="inputbox">
						<input type="password" class="inputbox-input" placeholder="رمز عبور" name="pwd2">
						<div class="inputbox-hr"><hr></div>
					</div>
				</td>
			</tr>
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
					<input class="btn btn-blue" type="submit" value="ثبت نام">
					<a href="<?php echo home_url('log-in') ?>" style="margin:0 10px">ورود</a>
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
<script src="<?php echo get_template_directory_uri() ?>/lib/chosen/chosen.jquery.js"></script>
<?php get_footer(); ?>
