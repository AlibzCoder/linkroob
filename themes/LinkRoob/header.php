<!doctype html>
<html style="margin:0 !important;">
<head>
	<?php $pagename = get_query_var('pagename');  ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8">
	<title>Link Roob</title>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/css/solid.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/css/regular.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/css/brands.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/css/fontawesome.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/lib/select2/select2.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/lib/checkbox/pretty-checkbox.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/lib/ritebar/css/star-rating-svg.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/lib/introJs/introjs-rtl.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/lib/introJs/introjs.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/lib/swiper/css/swiper.css">
	<link rel="stylesheet" href="<?php echo get_stylesheet_uri()."?ver=".rand(111,999);  ?>" media="screen">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/header.css<?php echo "?ver=".rand(111,999); ?>">


	<script src='https://www.google.com/recaptcha/api.js?explicit&hl=fa'></script>
	<style>
		.rc-anchor-checkbox-label,
		.rc-anchor-normal .rc-anchor-pt,
		.rc-anchor-invisible .rc-anchor-pt,
		.rc-anchor-compact .rc-anchor-pt{
			font-family: Shabnam !important;
		}
	</style>
	<?php wp_head(); ?>
</head>
<body>
	<div class="Flat_mega_menu">
		<div class="nav-icon"><div><span></span></div></div>
		<input class="mobile_button" type="checkbox">
		<ul>
			<li><a href="<?php bloginfo("url"); ?>">صفحه اصلی</a></li>

			<?php
			global $wpdb;
			$sql = "SELECT id,name FROM cat";
			// Results can be parsed as OBJECT, OBJECT_K, ARRAY_A, ARRAY_N
			$cat = $wpdb->get_results($sql, OBJECT);

			foreach ($cat as $ckey => $cvalue){
				$sql = "SELECT id,name FROM subcat WHERE cat_id = {$cvalue->id}";
				$subcat = $wpdb->get_results($sql, ARRAY_A);
				?>
				<li>
					<i class="fas fa-angle-down menu-dropdown-icon"></i>
					<a href="<?php echo home_url('cat-view').'?cat_id='.$cvalue->id; ?>"><?php echo $cvalue->name; ?></a>
					<?php if(count($subcat)>0){ ?>
						<ul <?php echo (count($subcat)>10) ? 'class="submenu four_col"' : 'class="submenu one_col"'; ?> >
							<?php foreach ($subcat as $sckey){ ?>
								<li><a href="<?php echo home_url('cat-view').'?cat_id='.$cvalue->id.'&subcat_id='.$sckey["id"]; ?>"><?php echo $sckey["name"] ?></a></li>
							<?php } ?>
						</ul>
					<?php } ?>
				</li>
			<?php } ?>
			<li class="user_login"><a class="fa fa-user menu-dropdown-icon"></a>
				<ul class="submenu one_col">
					<?php
						if(is_user_logged_in()){ 
							$u = wp_get_current_user();
							echo '<li><a class="btn btn-blue" href="'.home_url('user-panel').'">پنل کاربری</a></li>';
							echo '<li><a class="btn btn-blue" href="'.home_url('log-in').'/?action=logout'.'">خروج</a></li>';
							echo '<li><div class="user-name-view">'.$u->display_name.'</div></li>';
						}else{
							echo '<li><a class="btn btn-blue" href="'.home_url('sign-in').'">ثبت نام</a></li>';
							echo '<li><a class="btn btn-blue" href="'.home_url('log-in').'">ورود</a></li>';
						}
					?>
				</ul>
			</li>
			<li class="plus"> <a href="<?php echo home_url('add'); ?>" class="fas fa-plus"></a></li>
			<li class="search_bar"><a class="fa fa-search menu-dropdown-icon"></a>
				<ul class="submenu one_col">
					<form method="get" action="<?php echo home_url('search'); ?>">
						<input type="text" name="srch" placeholder="دنبال چی میگردی">
						<input class="btn btn-blue" type="submit" value="جستجو">
					</form>
				</ul>
			</li>
		</ul>
		<a class="website-name" href="<?php bloginfo("url"); ?>">Link Roob</a>
	</div>
<?php
	if (!is_home()){
		$pagename = get_query_var('pagename'); 
	}
?>


