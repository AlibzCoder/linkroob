<?php /* Template Name: log-in */
require('inc/recaptcha/constant.php');

$redirect_url = $wpdb->escape(trim($_GET['redirect_url']));
$redirect_url = (!empty($redirect_url)) ? $redirect_url : home_url('user-panel');

$action = $wpdb->escape(trim($_GET['action']));

if(!is_user_logged_in()){
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	
	$Error = null;
	$everyThingOK = 1;
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {	
		$username = $wpdb->escape(trim($_POST['user']));
		$pwd = $wpdb->escape(trim($_POST['pwd']));
		
		
		
		if (isset($_POST['g-recaptcha-response'])) {
		
			require('inc/recaptcha/src/autoload.php');		
			
			$recaptcha = new \ReCaptcha\ReCaptcha(SECRET_KEY);
	
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
	
			if ($resp->isSuccess()) {
				if(empty($username) || empty($pwd)){
					$Error = "لطفا نام کاربری و رمز عبور را وارد کنید";
				}else{
					$Error = log_in($username,$pwd);
					if($Error == "invalid_username"){
						$Error = "نام کاربری یا کلمه عبور نادرست است";
					}else if($Error == "incorrect_password"){
						$Error = "رمز عبور نادرست است";
					}else if(empty($Error)){
						wp_redirect($redirect_url);
						exit;
					}
				}
			}else{
				$Error = "لطفا مشخص کنید که ربات نیستید";
			}	
		}


		

		
	}
	
	get_header();
?>
<div id="article">
	<div id="continer">
		<div class="catview">
			<div>
				<h2>ورود</h2>
			</div>
			<hr>
		</div>
		<div class="form">
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
				<?php if(!empty($Error)) echo '<span class="error">'.$Error.'</span>'; ?>
				<table>
					<tr>
						<td>
							نام کاربری
						</td>
						<td>
							<div class="inputbox">
								<input type="text" class="inputbox-input" placeholder="نام کاربری" name="user">
								<div class="inputbox-hr"><hr></div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							 رمز عبور
						</td>
						<td>
							<div class="inputbox">
								<input type="password" class="inputbox-input" placeholder="رمز عبور" name="pwd">
								<div class="inputbox-hr"><hr></div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="g-recaptcha" data-sitekey="<?php echo SITE_KEY; ?>"></div>
						</td>
					</tr>
					<tr>
						<td>
							<input class="btn btn-blue" type="submit" value="ورود">
							<a href="<?php echo home_url('sign-in') ?>" style="margin:0 10px">ثبت نام</a>
						</td>
						<td>
							<a href="<?php echo wp_lostpassword_url(); ?>">آیا رمز عبور خود را فراموش کرده اید؟</a>
						</td>
					</tr>
				</table>
			</form>
		</div>


<script src="<?php echo get_template_directory_uri() ?>/js/jquery-3.3.1.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri() ?>/lib/chosen/chosen.jquery.js"></script>
<?php get_footer();
}else{
	if($action=='logout'){
		wp_logout();
		wp_redirect(home_url('log-in'));
		exit;
	}else{
		wp_redirect($redirect_url);
		exit;
	}
}
?>

