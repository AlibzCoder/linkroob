<?php /* Template Name: cat-view */
	
	$dont_show_intro = ($wpdb->escape(trim($_POST['dont_show_intro']))) ? true : false;
	if(count($_COOKIE) > 0) {
		if(isset($_COOKIE["dont_show_intro"])) {
			$dont_show_intro = $_COOKIE["dont_show_intro"];
		}else{
			setcookie("dont_show_intro", $dont_show_intro, time() + (86400 * 30), "/");
		}
    }

	get_header();

	$page = isset($_GET['index']) ? $_GET['index'] : 1;
	
	$cat_id = (int) $wpdb->escape(trim($_GET['cat_id']));
	$subcat_id = (int) $wpdb->escape(trim($_GET['subcat_id']));
	$social_network_id = (int) $wpdb->escape(trim($_GET['social_network_id']));
	$hashtag_ids = $wpdb->escape(trim($_GET['hashtag_ids']));
	$order_id = (int) $wpdb->escape(trim($_GET['order_id']));
	$city_id = (int) $wpdb->escape(trim($_GET['city_id']));
	$event_enable = (empty((int) $wpdb->escape(trim($_GET['event_enable'])))) ? 0 : (int) $wpdb->escape(trim($_GET['event_enable'])) ;
	
	$cat = get_cat_by_id($cat_id);
	$subcat = get_subcat_by_id($subcat_id);
	$social_network = get_social_network_by_id($social_network_id);
	
	//check if category not set but subcategory set
	if(empty($cat)&&!empty($subcat)){
		$cat = get_subcat_cat($subcat_id);
	}
?>
<?php 
	if(!empty($social_network)||!empty($cat->name)||!empty($subcat)){
		echo '<div id="site_map" class="noselect">';
			if(!empty($social_network)){
				echo '<a href="'.home_url('cat-view').'?social_network_id='.$social_network_id.'">'.$social_network.'</a>';
				if(!empty($cat->name)){
					echo '<span>|</span>';
				}
			}
			if(!empty($cat->name)){
				echo '<a href="'.home_url('cat-view').'?cat_id='.$cat_id.'">'.$cat->name.'</a>';
			}
			if(!empty($subcat)){
				echo '<span>/</span><a href="'.home_url('cat-view').'?cat_id='.$cat_id.'&subcat_id='.$subcat_id.'">'.$subcat->name.'</a>';
			}
		echo '</div>';
	}
?>
<div id="article">
	<div id="continer">
		<div class="catview">
			<div>
				<?php
					if(!empty($subcat))echo'<h2>'.$subcat->name.'</h2>';
					else if(empty($subcat)&&!empty($cat->name))echo'<h2>'.$cat->name.'</h2>';
					else if(!empty($social_network))echo'<h2>'.$social_network.'</h2>';
					else echo'<h2>لیست کانال ها</h2>';
				?>
				<ul class="cat-filters">
					<li>
						<i class="fas fa-filter" data-intro="برای انتخاب فیلتر روی این دکمه کلیک کنید"></i>
						<input id="cat-filters-btn" type="checkbox"/>
						<ul>
							<table>
								<tr>
									<td><i class="fab fa-telegram-plane"></i></td>
									<td>
										<div id="social_network_select" class="cat-filters-select">
											<select class="select2" data-placeholder="انتخاب شبکه اجتماعی">
												<option></option>
												<?php
												  $social_network_list = get_list_of_social_network();
												  foreach ($social_network_list as $row){
													  if($row["id"]==$social_network_id){
														echo '<option selected value="'.$row["id"].'">'.$row["name"].'</option>';
													  }else{
														echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
													  }
												  }
												  ?>
											</select>
										</div>
									</td>
								</tr>
								<tr>
									<td><i class="fas fa-sort-amount-down"></i></td>
									<td>
										<div id="order" class="cat-filters-select">
											<select class="select2" data-placeholder="نمایش بر اساس">
												<option></option>
												<option value="0" <?php if($order_id==0) echo 'selected';?> >جدیدترین ها</option>
												<option value="1" <?php if($order_id==1) echo 'selected';?> >پرطرفدارترین</option>
												<option value="2" <?php if($order_id==2) echo 'selected';?> >بیشترین بازدید</option>
											</select>
										</div>
									</td>
								</tr>
								<?php if($cat->city_filter_enable){ ?>
									<tr>
										<td><i class="fas fa-city"></i></td>
										<td>
											<div id="cat_filters_select_city" class="cat-filters-select">
											  <select class="select2" data-placeholder="شهر">
												<option></option>
												<?php
													$cities = get_cities();
													foreach ($cities as $row){
													  if($row["id"]==$city_id){
														echo '<option selected value="'.$row["id"].'">'.$row["name"].'</option>';
													  }else{
														echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
													  }
													}
												?>
											  </select>
											</div>
										</td>
									</tr>
								<?php } ?>
								<?php 
									if(!empty($subcat)){ 
										$hashtags = get_hashtags_by_subcat_id($subcat_id);
										if(count($hashtags)>0){ ?>
											<tr>
												<td><i class="fas fa-hashtag"></i></td>
												<td>
													<div id="cat_filters_hashtag_select" class="cat-filters-select">
													  <select multiple class="select2" data-placeholder="برچسب">
														<option></option>
														<?php 
															$h = explode(',',$hashtag_ids);
															foreach ($hashtags as $row){
																$hashtag_selected_id = 0;
																for($i=0;$i<=count($h)-1;$i++){
																	if($row["id"]==$h[$i]){
																		$hashtag_selected_id = $h[$i];
																	}
																}
																if($row["id"]==$hashtag_selected_id){
																	echo '<option selected="selected" value="'.$row["id"].'">'.$row["name"].'</option>';
																}else{
																	echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
																}
															}
														?>
													  </select>
													</div>
												</td>
											</tr>
								<?php 	}
									} ?>
								<tr>
									<td><i class="fas fa-envelope"></i></td>
									<td id="product_box" tabindex="1">
										<div id="just_product" class="pretty p-switch p-fill">
											<input type="checkbox" <?php if(!empty($event_enable)&&$event_enable) echo 'checked'; ?> />
											<div class="state" style="width: 100%;<?php if(!empty($event_enable)&&$event_enable) echo 'color:#2888d2'; else echo 'color:#bdc3c7'; ?>">
												<label>فیلتر بر اساس پست ها </label>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</ul>
					</li>
				</ul>
			</div>
			<hr>
		</div>
		<?php
			$posts = get_chanels($order_id,"publish",null,0,null,$cat_id,$subcat_id,$social_network_id,$city_id,$hashtag_ids,$event_enable);
			$page_post_count = 20;
			if(count($posts)>0){
				$pages_count = count($posts) / $page_post_count;
				if($pages_count-(int)$pages_count>0){
					$pages_count = (int)$pages_count +1;
				}
				$page_posts_index = $page * $page_post_count - $page_post_count;
		?>
			<div class="chanel-previews-gridview">
				<?php foreach (array_slice($posts,$page_posts_index,$page_post_count) as $p => $post){ ?>
					<div class="chanel_preview <?php if($post->vip_date>time()) echo 'spical-chanel-preview'; else echo 'normal-chanel-preview'; ?>">
					  <div class="chanel_preview_image">
						  <div class="chanel_preview_image">
							  <div class="chanel_image_border 
							  <?php if(!empty($post->story_time)&&time()<$post->story_time) echo "chanel_image_border_story";
							  else echo "chanel_image_border_normal"; ?>" id="<?php echo $post->id.rand(111,999); ?>" onclick="ShowStory(this.id)">
								<img src="<?php echo home_url().'/wp-content/uploads/img/'.$post->chanel_id.".jpg" ?>" alt="">
							  </div>
							  <?php if(!empty($post->story_time)&&time()<$post->story_time){ ?>
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
					  </div>
					  <div>
						  <a href="<?php echo $post->guid; ?>">
							<div class="chanel_preview_name">
							  <h4><?php echo $post->post_title; ?></h4>
							</div>
							<div class="chanel_preview_description">
							  <span><?php echo $post->description; ?></span>
							</div>
						  </a>
						  <table class="chanel_preview_details">
							  <tr>
								<td>
									<a target="_blank" href="<?php echo home_url('cat-view').'/?social_network_id='.$post->social_network_id; ?>">
										<h6><?php echo get_social_network_by_id($post->social_network_id); ?></h6>
									</a>
								</td>
								<td><i class="fab fa-telegram-plane"></i></td>
							  </tr>
							  <?php if($order_id==2){ ?>
								  <tr class="chanel_preview_time_posted">
									<td><h6><?php echo $post->views; ?> بازدید</h6></td>
									<td><i class="fas fa-eye"></i></td>
								  </tr>
							  <?php }else{ ?>
								  <tr class="chanel_preview_time_posted">
									<td><h6><?php echo meks_time_ago($post->post_id); ?></h6></td>
									<td><i class="fas fa-clock"></i></td>
								  </tr>
							  <?php } ?>
						  </table>
						  <div class="chanel_preview_rite">
							  <?php
									$rate = floatval($post->rate);
									post_rate($rate);
								?>
						  </div>
						  <hr/>
						  <a href="<?php echo $post->guid; ?>">
							<div class="chanel_preview_goto">
								<span class="<?php if($post->vip_date>time()) echo 'btn btn-blue'; else echo 'btn btn-black'; ?>">پیوستن</span>
							</div>
						  </a>
					  </div>
					</div>
				<?php } ?>
			</div>
			<?php if($pages_count>1){ ?>
				<div class="pagination noselect">
					<?php  
						$q = $_GET;
						$step = 1;
					
						if($page == 1){$q['index'] = $page;}
						else{$q['index'] = $page-1;}
						echo '<a class="fas fa-angle-left" href="'.home_url('cat-view').'?'.http_build_query($q).'"></a>';

						if ($pages_count < $step * 2 + 6) {
							for ($i = 1;$i<=$pages_count;$i++){
								$q['index'] = $i;
								if($i==$page){echo '<a href="'.home_url('cat-view').'?'.http_build_query($q).'" class="pagination-selected">'.$i.'</a>';}
								else{echo '<a href="'.home_url('cat-view').'?'.http_build_query($q).'">'.$i.'</a>';}
							}
						}else if ($page < $step * 2 + 1) {
							for ($i = 1;$i<=($step*2+3);$i++){
								$q['index'] = $i;
								if($i==$page){echo '<a href="'.home_url('cat-view').'?'.http_build_query($q).'" class="pagination-selected">'.$i.'</a>';}
								else{echo '<a href="'.home_url('cat-view').'?'.http_build_query($q).'">'.$i.'</a>';}
							}
							$q['index'] = $pages_count;
							echo '<i>...</i><a href="'.home_url('cat-view').'?'.http_build_query($q).'">'.$pages_count.'</a>';
						}else if ($page > $pages_count - $step * 2) {
							$q['index'] = 1;
							echo '<a href="'.home_url('cat-view').'?'.http_build_query($q).'">1</a><i>...</i>';

							for ($i = $pages_count-$step*2-2;$i<=$pages_count;$i++){
								$q['index'] = $i;
								if($i==$page){echo '<a href="'.home_url('cat-view').'?'.http_build_query($q).'" class="pagination-selected">'.$i.'</a>';}
								else{echo '<a href="'.home_url('cat-view').'?'.http_build_query($q).'">'.$i.'</a>';}
							}
						}else {
							$q['index'] = 1;
							echo '<a href="'.home_url('cat-view').'?'.http_build_query($q).'">1</a><i>...</i>';
							for ($i = $page-$step;$i<=$page+$step;$i++){
								$q['index'] = $i;
								if($i==$page){echo '<a href="'.home_url('cat-view').'?'.http_build_query($q).'" class="pagination-selected">'.$i.'</a>';}
								else{echo '<a href="'.home_url('cat-view').'?'.http_build_query($q).'">'.$i.'</a>';}
							}
							$q['index'] = $pages_count;
							echo '<i>...</i><a href="'.home_url('cat-view').'?'.http_build_query($q).'">'.$pages_count.'</a>';
						}

						if($page == $pages_count){$q['index'] = $page;}
						else{$q['index'] = $page+1;}
						echo '<a class="fas fa-angle-right" href="'.home_url('cat-view').'?'.http_build_query($q).'"></a>';
					?>
				</div>
			<?php }?>
		<?php }else{
			echo '<div class="chanels-not-found"><h3>هیج کانال یا پیجی با فیلتر انتخابی شما پیدا نشد</h3></div>';
		} ?>
<script src="<?php echo get_template_directory_uri() ?>/js/jquery-3.3.1.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri() ?>/lib/select2/select2.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/lib/introJs/intro.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/lib/ritebar/jquery.star-rating-svg.js"></script>
<script>
	$(document).ready(function() {
        /******RiteBar******/
		$(".normal-chanel-preview .chanel_preview_ritebar").starRating({
		  totalStars: 5,
		  emptyColor: 'lightgray',
		  activeColor: '#333',
		  initialRating: 4,
		  strokeWidth: 0,
		  useGradient: false,
		  readOnly : true,
		  starSize: 20
		});
		$(".spical-chanel-preview .chanel_preview_ritebar").starRating({
		  totalStars: 5,
		  emptyColor: 'lightgray',
		  activeColor: '#2888d2',
		  initialRating: 4,
		  strokeWidth: 0,
		  useGradient: false,
		  readOnly : true,
		  starSize: 20
		});
        /******RiteBar******/
		
		<?php echo 'var dont_show_intro = ';echo ($dont_show_intro) ? 'true;' : 'false;';  ?>
		if(!dont_show_intro){
			introJs().setOptions({ 'nextLabel': 'بعد', 'prevLabel': 'قبل', 'skipLabel': 'باشه', 'doneLabel': 'باشه' ,showBullets: false}).start();
			$('.introjs-tooltipbuttons').prepend('<div id="dont-show-intro" class="pretty p-icon p-round p-jelly"><input type="checkbox" /><div class="state"><i class="icon fas fa-check"></i><label>دیگر نمایش نده</label></div></div>');
			$("#dont-show-intro input[type=checkbox]").on( "click", function(){
				if($("#dont-show-intro input[type=checkbox]:checked").val()){
					post("<?php echo $_SERVER['REQUEST_URI'];?>", {dont_show_intro:true});
				}
			});
		}
		
		
		
		/******Filters******/
		$("#cat-filters-btn").on( "click", function(){
			$('body').append('<div id="overlay" class="overlay"><div>');
			$('.cat-filters ul').css('z-index',9999);
			$(".overlay").click(function(){
				$('.cat-filters ul').css('z-index','9999');
				$("#cat-filters-btn").prop('checked', false);
				$(this).remove();
			});
		});
		
		$(".cat-filters-select .select2").select2({
			width:"100%",
			minimumResultsForSearch: 15,
            formatNoMatches: "متاسفانه چیزی پیدا نشد!",
        });

		$("#just_product input[type=checkbox]").on( "click", function(){
			var data = get_standard_variables();
			if($("#just_product input[type=checkbox]:checked").val()){
				$("#just_product *").css({color:"#2888d2"});
				data.event_enable = 1;
				get("<?php echo $_SERVER['REQUEST_URI'];?>", data);
			}else{
				$("#just_product *").css({color:"#bdc3c7"});
				data.event_enable = 0;
				get("<?php echo $_SERVER['REQUEST_URI'];?>", data);
			}
		});
		
		
		
		$("#social_network_select .select2").on("select2-selecting", function(e){
			var data = get_standard_variables();
			data.social_network_id = e.val;
			get("<?php echo $_SERVER['REQUEST_URI'];?>", data);
		});
		$("#cat_filters_select_city .select2").on("select2-selecting", function(e){
			var data = get_standard_variables();
			data.city_id = e.val
			get("<?php echo $_SERVER['REQUEST_URI'];?>", data);
		});
		$("#order .select2").on("select2-selecting", function(e){
			var data = get_standard_variables();
			data.order_id = e.val;
			get("<?php echo $_SERVER['REQUEST_URI'];?>", data);
		});
		$("#cat_filters_hashtag_select .select2").on("select2-selecting", function(e){
			var val = [];
			$("#cat_filters_hashtag_select select.select2 option[value = "+e.val+"]").attr('selected',true);
			$('#cat_filters_hashtag_select select.select2 option[selected="selected"]').each(function(index){val.push($(this).val());});			
			console.log(val);
			if(val.length>0){
				var data = get_standard_variables();
				data.hashtag_ids = val.join(',');
				get("<?php echo $_SERVER['REQUEST_URI'];?>", data);
			}else{
				window.location.href = removeParam('hashtag_ids');
			}
		});
		$("#cat_filters_hashtag_select .select2").on("select2-removing", function(e){
			var val = [];
			$("#cat_filters_hashtag_select select.select2 option[value = "+e.val+"]").removeAttr('selected');
			$('#cat_filters_hashtag_select select.select2 option[selected="selected"]').each(function(index){val.push($(this).val());});
			console.log(val);
			if(val.length>0){
				var data = get_standard_variables();
				data.hashtag_ids = val.join(',');
				get("<?php echo $_SERVER['REQUEST_URI'];?>", data);
			}else{
				window.location.href = removeParam('hashtag_ids');
			}
		});
		
		/******Filters******/
		
		
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
		function get(path, params, method) {
			method = method || "get"; // Set method to post by default if not specified.

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
		function removeParam(parameter){
			var url=document.location.href;
			var urlparts= url.split('?');
			if (urlparts.length>=2){
				var urlBase=urlparts.shift(); 
				var queryString=urlparts.join("?"); 

				var prefix = encodeURIComponent(parameter)+'=';
				var pars = queryString.split(/[&;]/g);
				for (var i= pars.length; i-->0;)               
					if (pars[i].lastIndexOf(prefix, 0)!==-1)   
						pars.splice(i, 1);
				url = urlBase+'?'+pars.join('&');
				window.history.pushState('',document.title,url); // added this line to push the new url directly to url bar .
			}
			return url;
		}
		function get_standard_variables(){
			return {
				<?php 
					if(!empty($cat_id)) echo 'cat_id:'.$cat_id.',';
					if(!empty($subcat_id)) echo 'subcat_id:'.$subcat_id.',';
					if(!empty($social_network_id)) echo 'social_network_id:'.$social_network_id.',';
					if(!empty($hashtag_ids)) echo 'hashtag_ids:"'.explode(',',$hashtag_ids).'",';
					if(!empty($order_id)) echo 'order_id:'.$order_id.',';
					if(!empty($city_id)) echo 'city_id:'.$city_id.',';
					if(!empty($event_enable)) echo 'event_enable:'.$event_enable;
				?>
			};
		}
	});
</script>
<?php get_footer(); ?>
