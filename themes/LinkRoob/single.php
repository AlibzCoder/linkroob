<?php
	if(count($_COOKIE) > 0) {
		if(isset($_COOKIE["rate_name"])) {
			$user_rate_name = $_COOKIE["rate_name"];
		}else{
			$user_rate_name = time();
			setcookie("rate_name", $user_rate_name, time() + (86400 * 30), "/");
		}
    }
	
	get_header();
	have_posts();
	the_post();
	$content = get_the_content();
	if($content[0]=="@"){
		$chanel_id = $content;
		$chanel_detail_id = get_chanel_detail_id($chanel_id);
		if(isset($_POST['rate'])){
			$rate = $_POST['rate'];
			set_post_rate($chanel_detail_id,$rate,$user_rate_name);
			update_rate($chanel_detail_id);
		}
		set_post_views($chanel_detail_id ,$user_rate_name);
		update_views($chanel_detail_id);

		$chanel = get_chanel($chanel_id);

		$title = get_the_title();
		$cat = get_cat_by_id($chanel->cat_id);
		$subcat = get_subcat_by_id($chanel->subcat_id);
		$social_network = get_social_network_by_id($chanel->social_network_id);
		$has_story = false; 
		$vip_avaible = false; 
		if($chanel->vip_date>time()){
			$vip_avaible = true;
			if(!empty($chanel->story_time)&&time()<$chanel->story_time){
				$has_story = true;
			}
		}
?>
<div id="article">
	<div id="continer">
		<div class="chanel-view-box">
			<div class="chanel-view <?php if($vip_avaible) echo 'chanel-view-spical'; ?>">
				<div class="chanel-view-box-1">
					<div>
						<div class="chanel_img_box 
							<?php 
								if($vip_avaible){
									if($has_story){echo 'chanel_img_story';}
									else{echo 'chanel_img_spical';}
								}else{echo 'chanel_img_normal';}
							?>">
							<div class="chanel_img" style='overflow: hidden;' 
							<?php if($vip_avaible&&$has_story){echo 'onclick="ShowStory(this.id)" id="'.$chanel->id.'"';}?>>
								<img src="<?php echo home_url().'/wp-content/uploads/img/'.$chanel_id.".jpg"; ?>" alt="">
								<a target="_blank" href="<?php echo $chanel->joinchat_url; ?>">
									<i class="fas fa-link"></i>
									<span>پیوستن</span>
								</a>
							</div>
							<?php if($vip_avaible&&$has_story){ ?>
								<div class="story-box">
									<div class="story-duration"><hr <?php if(!empty($chanel->story_duration)) echo 'style="transition:'.$chanel->story_duration.'s all"' ?> ></div>
									<div class="story">
										<a href="<?php echo $chanel->story_link; ?>" target="_blank">
											<img src="<?php echo home_url().'/wp-content/uploads/story/'.$chanel->chanel_id.".jpg" ?>">
										</a>
									</div>
									<i class="close-story fas fa-times"></i>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="chanel_details_box">
						<h2 class="chanel-name"><?php echo $title; ?></h2>
						<div class="chanel_details">
							<?php if(!empty($chanel->description)){ ?>
								<div><p class="chanel-view-description"><?php echo $chanel->description; ?></p></div>
							<?php } ?>
							<div>
								<table>
									<tr>
										<td>
											<a class="link" target="_blank" href="<?php echo home_url('cat-view').'/?cat_id='.$chanel->cat_id.'&subcat_id='.$chanel->subcat_id; ?>">
												<h6><?php echo $cat->name.' - '.$subcat->name; ?></h6>
											</a>
										</td>
										<td><i class="fas fa-list"></i></td>
									</tr>
									<tr>
										<td>
											<a class="link" target="_blank" href="<?php echo home_url('cat-view').'/?social_network_id='.$chanel->social_network_id; ?>">
												<h6><?php echo $social_network; ?></h6>
											</a>
										</td>
										<td><i class="fab fa-telegram-plane"></i></td>
									</tr>
									<tr>
										<td>
											<h6><?php #echo get_chanel_member($detail[0]["joinchat_url"],$social_network_id); ?></h6>
										</td>
										<td><i class="fas fa-users"></i></td>
									</tr>
									<tr>
										<td>
										<h6 style="display:flex;"><?php echo $chanel->views; ?>&nbsp;بازدید</h6>
										</td>
										<td><i class="fas fa-eye"></i></td>
									</tr>
									<tr>
										<td><h6><?php echo meks_time_ago(); ?></h6></td>
										<td><i class="fas fa-clock"></i></td>
									</tr>
									
									<tr>
										<td>
											<div class="chanel_ritebar"></div>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="chanel-view-box-2">
					<div class="hashtags-box">
						<?php
							if (!empty($chanel->hashtags)) {
								$hashtags = explode(',', $chanel->hashtags);
								if(is_array($hashtags)||is_object($hashtags)){
									foreach($hashtags as $hashtag){
										echo '<a class="hashtag" 
										href="'.home_url('cat-view').'/?cat_id='.$chanel->cat_id.
										'&subcat_id='.$chanel->subcat_id.
										'&hashtag_ids='.$chanel->hashtags.'">
											<i class="fas fa-hashtag"></i>'.get_hashtag_by_id($hashtag)->name.
										'</a>';
									}
								}
							}
						?>
					</div>
					<div>
						<table class="chanel-view-joinchat">
						<tr>
							<td>
								<i class="fas fa-link"></i>
								<a target="_blank" class="link" href="<?php echo $chanel->joinchat_url; ?>"><?php echo $chanel->joinchat_url; ?></a>
							</td>
						</tr>
						<tr>
							<td>
								<h4 class="noselect">@</h4>
								<a target="_blank" class="link" href="<?php echo $chanel->joinchat_url; ?>"><?php echo substr($chanel_id,1,strlen($chanel_id)-1); ?></a>
							</td>
						</tr>
						</table>
					</div>
				</div>

				<?php 
				$events = get_events($chanel->id);
				if($chanel->upgrade&&count($events)>0){ ?>
					<div class="chanel-view-products">
						<div>
							<h2>پست ها</h2>
							<hr>
						</div>
						<div class="chanel-view-products-box">
							<div class="chanel-view-products-grid">
								<?php
									foreach ($events as $e => $event){ 
										$ev = "";
										$ev.='<div class="event">';
											if(!empty($event->link)) $ev.='<a href="'.$event->link.'">';
												$size = getimagesize(wp_get_upload_dir()['basedir']."/events/".$event->chanel_id."_".$event->id.".jpg");
												$ev.='<div class="event-img-box"><div class="event-img"><img class="';
												if($size[0]>$size[1]){$ev.='event-img-horizontal';} 
												else{$ev.='event-img-vertical';}
												$ev.='" src="'.home_url().'/wp-content/uploads/events/'.$event->chanel_id.'_'.$event->id."?ver=".rand(111,999).'"></div></div>';

												$ev.='<h4>'.$event->title.'</h4>';
												if(!empty($event->text)) $ev.='<p>'.$event->text.'</p>';
											if(!empty($event->link)) $ev.='</a>';
										$ev.='</div>';
										echo $ev;
									}
								?>
							</div>
						</div>
					</div>
				<?php }else{ ?>
					<style>
						.chanel-view{
							width:auto;
							display:block;
						}
						.chanel-view-box-1,
						.chanel-view-box-2{
							justify-content: center;
							margin: 1em 0 0;
						}
						.chanel-view-box{
							flex-direction: column;
						}
						.chanel-view-slider-box{
							width: 100%;
							margin:2em 0 0 0;
						}
						.chanel-view-slider-box-title{
							width: 95%;
							margin:1em auto 0;
							font-size:20px;
						}
						.swiper-slide{
							width:250px;
						}
						.chanel-view-products{
							margin: 1em auto;
						}
						.chanel-view-products-grid .chanel-view-product img{
							width: 200px;
						}
						.chanel-view-products-grid .chanel-view-product p{
							width: 250px;
							text-align: justify;
						}
					</style>
				<?php } ?>
			</div>

			<?php
				$sorted_chanels = array();

				$spical_chanels =  get_chanels(0,"publish",null,1,null);
				$chanels_like_cat = get_chanels(0,"publish",null,2,null,$chanel->cat_id);
				$chanels_like_social_network = get_chanels(0,"publish",null,2,null,null,null,$chanel->social_network_id);
				$chanels_like_city = get_chanels(0,"publish",null,2,null,null,null,null,$chanel->city_id);

				if(!empty($spical_chanels)){$sorted_chanels = array_merge($sorted_chanels,$spical_chanels);}
				if(!empty($chanels_like_cat)){$sorted_chanels = array_merge($sorted_chanels,$chanels_like_cat);}
				if(!empty($chanels_like_social_network)){$sorted_chanels = array_merge($sorted_chanels,$chanels_like_social_network);}
				if(!empty($chanels_like_city)){$sorted_chanels = array_merge($sorted_chanels,$chanels_like_city);}
				
				$sorted_chanels = array_filter($sorted_chanels,'unique_obj');
				
				if(count($sorted_chanels)>0){ 
				    if(!(count($sorted_chanels)==1&&$sorted_chanels[0]->chanel_id==$chanel->chanel_id)){ ?>
				<div class="chanel-view-slider-box">
					<div class="chanel-view-slider-box-title">
						<h4>مطالب مرتب</h4>
						<hr>
					</div>
					<div class="chanel-view-slider  swiper-container">
						<div class="swiper-wrapper">
							<?php foreach (array_slice($sorted_chanels,0,10) as $p => $post){ 
								if ($post->chanel_id!=$chanel->chanel_id) { ?>
								<div class="swiper-slide">
									<div class="chanel_preview <?php if($post->vip_date>time()) echo 'spical-chanel-preview'; else echo 'normal-chanel-preview'; ?>">
									  <div class="chanel_preview_image">
										  <div class="chanel_image_border 
											<?php if(!empty($post->story_time)&&time()<$post->story_time) echo "chanel_image_border_story";
											else echo "chanel_image_border_normal"; ?>" id="<?php echo $post->id.rand(111,999); ?>" onclick="ShowStory(this.id)">
												<img src="<?php echo home_url().'/wp-content/uploads/img/'.$post->chanel_id."?ver=".rand(111,999); ?>" alt="">
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
									  <div>
										  <a href="<?php echo $post->guid; ?>">
											<div class="chanel_preview_name">
											  <h4><?php echo $post->post_title ?></h4>
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
											  <tr class="chanel_preview_time_posted">
												<td><h6><?php echo meks_time_ago($post->post_id); ?></h6></td>
												<td><i class="fas fa-clock"></i></td>
											  </tr>
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
								</div>
							<?php }} ?>
						</div>
						<div class="swiper-button-next"></div>
						<div class="swiper-button-prev"></div>
					</div>
				</div>
			<?php } 
			    }else{ ?>
				<style>
					.chanel-view{
						width:auto;
						display:block;
					}
					.chanel-view-box-1,
					.chanel-view-box-2{
						justify-content: center;
						margin: 1em 0 0;
					}
					.chanel-view-box{
						flex-direction: column-reverse;
					}
					.chanel-view-slider-box{
						width: 100%;
						margin:2em 0 0 0;
					}
					.chanel-view-slider-box-title{
						width: 95%;
						margin:1em auto 0;
						font-size:20px;
					}
					.swiper-slide{
						width:250px;
					}
					.chanel-view-products{
						margin: 1em auto;
					}
					.chanel-view-products-grid .chanel-view-product img{
						width: 200px;
					}
					.chanel-view-products-grid .chanel-view-product p{
						width: 250px;
						text-align: justify;
					}
				</style>
			<?php }?>
		</div>
		
		
<script src="<?php echo get_template_directory_uri() ?>/js/jquery-3.3.1.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri() ?>/lib/ritebar/jquery.star-rating-svg.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/lib/swiper/js/swiper.js"></script>
<script>
	var window_width = $(window).width();
		
	$(".chanel_ritebar").starRating({
		totalStars: 5,
		emptyColor: 'lightgray',
		<?php if($vip_avaible){
			echo "activeColor:'#2888d2',";
			echo "hoverColor:'#2888d2',";
			echo "ratedColor:'#2888d2',";
		}else{
			echo "activeColor:'#333',";
			echo "hoverColor:'#333',";
			echo "ratedColor:'#333',";
		} ?>
		initialRating: <?php echo (!empty($chanel->rate)) ? $chanel->rate : 0;  ?>,
		strokeWidth: 0,
		useGradient: false,
		readOnly : false,
		starSize: 20,
		callback: function(currentRating, $el){
		  post('<?php echo $_SERVER['REQUEST_URI'];?>',{rate:currentRating});
		}
	});
		
	/******Rite Bar******/
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
	/******Rite Bar******/

	swiper = new Swiper('.chanel-view-slider', {
		slidesPerView:'auto',
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		autoplay: {
			delay: 2500,
			disableOnInteraction: false,
		}
	});
	if(window_width<=550){
		swiper.params.slidesPerView = 1;
	}else if(window_width>550){
		swiper.params.slidesPerView = 'auto';
	}
	swiper.update();



	$(window).resize(function() {
		window_width = $(window).width();
		if(window_width<=550){
			swiper.params.slidesPerView = 1;
		}else if(window_width>550){
			swiper.params.slidesPerView = 'auto';
		}
		swiper.update();
	});

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

<?php }else{
  global $wp_query;
  $wp_query->set_404();
  status_header( 404 );
  get_template_part( 404 ); exit();
}

get_footer(); 
?>
