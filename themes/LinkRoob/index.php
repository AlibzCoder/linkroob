<?php get_header(); ?>
<div id="article">
	<div id="continer">
		<?php 
			$special_posts = get_chanels(0,"publish",15,1);
		    if(count($special_posts)>0){ ?>
        	    <!--Specials-->
            		<div class="slider_box noselect">
            		  <div class="slider_box_title">
            			<div>
            			  <h2>کانال های پیشنهادی</h2>
            			</div>
            			<hr>
            		  </div>
            		  <div class="specials-swiper-container swiper-container">
            		  	<div class="swiper-wrapper">
            			  	<?php foreach ($special_posts as $p => $chanel){ ?>
            					<div class="swiper-slide">
            						<div class="chanel_preview <?php if($chanel->vip_date>time()) echo 'spical-chanel-preview'; else echo 'normal-chanel-preview'; ?>">
            						  <div class="chanel_preview_image">
            							  <div class="chanel_image_border 
            								<?php if(!empty($chanel->story_time)&&time()<$chanel->story_time) echo "chanel_image_border_story";
            								else echo "chanel_image_border_normal"; ?>" id="<?php echo $chanel->id.rand(111,999); ?>" onclick="ShowStory(this.id)">
            									<img src="<?php echo home_url().'/wp-content/uploads/img/'.$chanel->chanel_id.".jpg"; ?>" alt="">
            								</div>
            								<?php if(!empty($chanel->story_time)&&time()<$chanel->story_time){ ?>
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
            						  <div>
            							  <a href="<?php echo $chanel->guid; ?>">
            								<div class="chanel_preview_name">
            								  <h4><?php echo $chanel->post_title ?></h4>
            								</div>
            								<div class="chanel_preview_description">
            								  <span><?php echo $chanel->description; ?></span>
            								</div>
            							  </a>
            							  <table class="chanel_preview_details">
            								  <tr>
            									<td>
            										<a target="_blank" href="<?php echo home_url('cat-view').'/?social_network_id='.$chanel->social_network_id; ?>">
            											<h6><?php echo get_social_network_by_id($chanel->social_network_id); ?></h6>
            										</a>
            									</td>
            									<td><i class="fab fa-telegram-plane"></i></td>
            								  </tr>
            								  <tr class="chanel_preview_time_posted">
            									<td><h6><?php echo meks_time_ago($chanel->post_id); ?></h6></td>
            									<td><i class="fas fa-clock"></i></td>
            								  </tr>
            							  </table>
            							  <div class="chanel_preview_rite">
            								  <?php
            										$rate = floatval($chanel->rate);
            										post_rate($rate);
            									?>
            							  </div>
            							  <hr/>
            							  <a href="<?php echo $chanel->guid; ?>">
            								<div class="chanel_preview_goto">
            									<span class="<?php if($chanel->vip_date>time()) echo 'btn btn-blue'; else echo 'btn btn-black'; ?>">پیوستن</span>
            								</div>
            							  </a>
            						  </div>
            						</div>
            					</div>
            				<?php } ?>
            			</div>
            		  </div>
            		  <div id="specials_swiper_pagination"></div>
            		</div>
		<?php } ?>
	<!--News-->
		<?php $posts = get_chanels(0,"publish",15); ?>
		<div class="slider_box noselect">
		  <div class="slider_box_title">
			<div>
			  <h2>جدیدترین</h2>
			  <a href="<?php echo home_url('cat-view').'?order_id=0' ?>" class="btn btn-blue"><i class="fas fa-angle-double-left"></i>نمایش بیشتر</a> 
			</div>
			<hr>
		  </div>
		  <div class="normal-swiper-container swiper-container">
			<div class="swiper-wrapper">
					<?php
						foreach ($posts as $p => $chanel){ ?>
						  <div class="swiper-slide">
							<div class="chanel_preview <?php if($chanel->vip_date>time()) echo 'spical-chanel-preview'; else echo 'normal-chanel-preview'; ?>">
							  <div class="chanel_preview_image">
									<div class="chanel_image_border 
									<?php if(!empty($chanel->story_time)&&time()<$chanel->story_time) echo "chanel_image_border_story";
									else echo "chanel_image_border_normal"; ?>" id="<?php echo $chanel->id.rand(111,999); ?>" onclick="ShowStory(this.id)">
										<img src="<?php echo home_url().'/wp-content/uploads/img/'.$chanel->chanel_id.".jpg" ?>" alt="">
									</div>
									<?php if(!empty($chanel->story_time)&&time()<$chanel->story_time){ ?>
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
							  <div>
								  <a href="<?php echo $chanel->guid; ?>">
									<div class="chanel_preview_name">
									  <h4><?php echo $chanel->post_title ?></h4>
									</div>
									<div class="chanel_preview_description">
									  <span><?php echo $chanel->description; ?></span>
									</div>
								  </a>
								  <table class="chanel_preview_details">
									  <tr>
										<td>
											<a target="_blank" href="<?php echo home_url('cat-view').'/?social_network_id='.$chanel->social_network_id; ?>">
												<h6><?php echo get_social_network_by_id($chanel->social_network_id); ?></h6>
											</a>
										</td>
										<td><i class="fab fa-telegram-plane"></i></td>
									  </tr>
									  <tr class="chanel_preview_time_posted">
										<td><h6><?php echo meks_time_ago($chanel->post_id); ?></h6></td>
										<td><i class="fas fa-clock"></i></td>
									  </tr>
								  </table>
								  <div class="chanel_preview_rite">
									  <?php
											$rate = floatval($chanel->rate);
											post_rate($rate);
										?>
								  </div>
								  <hr/>
								  <a href="<?php echo $chanel->guid; ?>">
									<div class="chanel_preview_goto">
										<span class="<?php if($chanel->vip_date>time()) echo 'btn btn-blue'; else echo 'btn btn-black'; ?>">پیوستن</span>
									</div>
								  </a>
							  </div>
							</div>
						  </div>
					<?php } ?>
			</div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
		  </div>
		</div>
	<!--Hottest-->
		<div class="slider_box noselect">
		  <div class="slider_box_title">
			<div>
			  <h2>پرطرفدارترین</h2>
			  <a href="<?php echo home_url('cat-view').'?order_id=1' ?>" class="btn btn-blue"><i class="fas fa-angle-double-left"></i>نمایش بیشتر</a> </div>
			<hr>
		  </div>
		  <div class="normal-swiper-container swiper-container">
			<div class="swiper-wrapper">
				<?php
					$posts = get_chanels(1,"publish",15);
					foreach ($posts as $p => $chanel){ ?>
						  <div class="swiper-slide">
							<div class="chanel_preview <?php if($chanel->vip_date>time()) echo 'spical-chanel-preview'; else echo 'normal-chanel-preview'; ?>">
							  <div class="chanel_preview_image">
								<div class="chanel_image_border 
								<?php if(!empty($chanel->story_time)&&time()<$chanel->story_time) echo "chanel_image_border_story";
								else echo "chanel_image_border_normal"; ?>" id="<?php echo $chanel->id.rand(111,999); ?>" onclick="ShowStory(this.id)">
									<img src="<?php echo home_url().'/wp-content/uploads/img/'.$chanel->chanel_id.".jpg" ?>" alt="">
								</div>
								<?php if(!empty($chanel->story_time)&&time()<$chanel->story_time){ ?>
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
							  <div>
								  <a href="<?php echo $chanel->guid; ?>">
									<div class="chanel_preview_name">
									  <h4><?php echo $chanel->post_title ?></h4>
									</div>
									<div class="chanel_preview_description">
									  <span><?php echo $chanel->description; ?></span>
									</div>
								  </a>
								  <table class="chanel_preview_details">
									  <tr>
										<td>
											<a target="_blank" href="<?php echo home_url('cat-view').'/?social_network_id='.$chanel->social_network_id; ?>">
												<h6><?php echo get_social_network_by_id($chanel->social_network_id); ?></h6>
											</a>
										</td>
										<td><i class="fab fa-telegram-plane"></i></td>
									  </tr>
									  <tr class="chanel_preview_time_posted">
										<td><h6><?php echo $chanel->views; ?> بازدید</h6></td>
										<td><i class="fas fa-eye"></i></td>
									  </tr>
								  </table>
								  <div class="chanel_preview_rite">
									  <?php
											$rate = floatval($chanel->rate);
											post_rate($rate);
										?>
								  </div>
								  <hr/>
								  <a href="<?php echo $chanel->guid; ?>">
									<div class="chanel_preview_goto">
										<span class="<?php if($chanel->vip_date>time()) echo 'btn btn-blue'; else echo 'btn btn-black'; ?>">پیوستن</span>
									</div>
								  </a>
							  </div>
							</div>
						  </div>
					<?php } ?>
			</div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
		  </div>
		</div>
	<!--Most visited-->
		<div class="slider_box noselect">
		  <div class="slider_box_title">
			<div>
			  <h2>پربازدیدترین</h2>
			  <a href="<?php echo home_url('cat-view').'?order_id=2' ?>" class="btn btn-blue"><i class="fas fa-angle-double-left"></i>نمایش بیشتر</a> </div>
			<hr>
		  </div>
		  <div class="normal-swiper-container swiper-container">
			  <div class="swiper-wrapper">
				<?php
					$posts = get_chanels(2,"publish",15);
					foreach ($posts as $p => $chanel){ ?>
						  <div class="swiper-slide">
							<div class="chanel_preview <?php if($chanel->vip_date>time()) echo 'spical-chanel-preview'; else echo 'normal-chanel-preview'; ?>">
							  <div class="chanel_preview_image">
								<div class="chanel_image_border 
								<?php if(!empty($chanel->story_time)&&time()<$chanel->story_time) echo "chanel_image_border_story";
								else echo "chanel_image_border_normal"; ?>" id="<?php echo $chanel->id.rand(111,999); ?>" onclick="ShowStory(this.id)">
									<img src="<?php echo home_url().'/wp-content/uploads/img/'.$chanel->chanel_id.".jpg" ?>" alt="">
								</div>
								<?php if(!empty($chanel->story_time)&&time()<$chanel->story_time){ ?>
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
							  <div>
								  <a href="<?php echo $chanel->guid; ?>">
									<div class="chanel_preview_name">
									  <h4><?php echo $chanel->post_title ?></h4>
									</div>
									<div class="chanel_preview_description">
									  <span><?php echo $chanel->description; ?></span>
									</div>
								  </a>
								  <table class="chanel_preview_details">
									  <tr>
										<td>
											<a target="_blank" href="<?php echo home_url('cat-view').'/?social_network_id='.$chanel->social_network_id; ?>">
												<h6><?php echo get_social_network_by_id($chanel->social_network_id); ?></h6>
											</a>
										</td>
										<td><i class="fab fa-telegram-plane"></i></td>
									  </tr>
									  <tr class="chanel_preview_time_posted">
										<td><h6><?php echo $chanel->views; ?> بازدید</h6></td>
										<td><i class="fas fa-eye"></i></td>
									  </tr>
								  </table>
								  <div class="chanel_preview_rite">
									  <?php
											$rate = floatval($chanel->rate);
											post_rate($rate);
										?>
								  </div>
								  <hr/>
								  <a href="<?php echo $chanel->guid; ?>">
									<div class="chanel_preview_goto">
										<span class="<?php if($chanel->vip_date>time()) echo 'btn btn-blue'; else echo 'btn btn-black'; ?>">پیوستن</span>
									</div>
								  </a>
							  </div>
							</div>
						  </div>
					<?php } ?>
			</div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
		  </div>
		</div>




<script src="<?php echo get_template_directory_uri() ?>/js/jquery-3.3.1.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri() ?>/lib/ritebar/jquery.star-rating-svg.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/lib/swiper/js/swiper.js"></script>
<script>
$(document).ready(function() {
	var window_width = $(window).width();


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

	/*******Normal Swiper******/
	var swiper = new Swiper('.normal-swiper-container', {
	  slidesPerView :6,
	  spaceBetween:30,
	  navigation: {
		nextEl: '.swiper-button-next',
		prevEl: '.swiper-button-prev',
	  },
	  autoplay: {
		delay: 2500,
		disableOnInteraction: false,
	  },
	});
	/******Normal Swiper******/

    <?php if(count($special_posts)>0){ ?>
	/******Specials Swiper******/
	var Pagination = {

		code: '',

		// --------------------
		// Utility
		// --------------------

		// converting initialize data
		Extend: function(data) {
			data = data || {};
			Pagination.size = data.size || 300;
			Pagination.page = data.page || 1;
			Pagination.step = data.step || 3;
		},

		// add pages by number (from [s] to [f])
		Add: function(s, f) {
			for (var i = s; i < f; i++) {
				Pagination.code += '<a>' + i + '</a>';
			}
		},

		// add last page with separator
		Last: function() {
			Pagination.code += '<i>...</i><a>' + Pagination.size + '</a>';
		},

		// add first page with separator
		First: function() {
			Pagination.code += '<a>1</a><i>...</i>';
		},



		// --------------------
		// Handlers
		// --------------------

		// change page
		Click: function() {
			Pagination.page = +this.innerHTML;
			Pagination.Start();

			specials_swiper.slideTo(Pagination.page-1);
		},

		// previous page
		Prev: function() {
			Pagination.page--;
			if (Pagination.page < 1) {
				Pagination.page = 1;
			}
			Pagination.Start();
			specials_swiper.slidePrev();
		},

		// next page
		Next: function() {
			Pagination.page++;
			if (Pagination.page > Pagination.size) {
				Pagination.page = Pagination.size;
			}
			Pagination.Start();
			specials_swiper.slideNext();
		},



		// --------------------
		// Script
		// --------------------

		// binding pages
		Bind: function() {
			var a = Pagination.e.getElementsByTagName('a');
			for (var i = 0; i < a.length; i++) {
				if (+a[i].innerHTML === Pagination.page) a[i].className = 'current';
				a[i].addEventListener('click', Pagination.Click, false);
			}
		},

		// write pagination
		Finish: function() {
			Pagination.e.innerHTML = Pagination.code;
			Pagination.code = '';
			Pagination.Bind();
		},

		// find pagination type
		Start: function() {
			if (Pagination.size < Pagination.step * 2 + 6) {
				Pagination.Add(1, Pagination.size + 1);
			}
			else if (Pagination.page < Pagination.step * 2 + 1) {
				Pagination.Add(1, Pagination.step * 2 + 4);
				Pagination.Last();
			}
			else if (Pagination.page > Pagination.size - Pagination.step * 2) {
				Pagination.First();
				console.log(Pagination.size);
				console.log(Pagination.step);
				console.log(Pagination.step * 2 - 2);
				console.log(Pagination.size - Pagination.step * 2 - 2);
				Pagination.Add(Pagination.size - Pagination.step * 2 - 2, Pagination.size + 1);
			}
			else {
				Pagination.First();
				Pagination.Add(Pagination.page - Pagination.step, Pagination.page + Pagination.step + 1);
				Pagination.Last();
			}
			Pagination.Finish();
		},



		// --------------------
		// Initialization
		// --------------------

		// binding buttons
		Buttons: function(e) {
			var nav = e.getElementsByTagName('a');
			nav[0].addEventListener('click', Pagination.Prev, false);
			nav[1].addEventListener('click', Pagination.Next, false);
		},

		// create skeleton
		Create: function(e) {

			var html = [
				'<a class="fas fa-angle-left"></a>', // previous button
				'<span></span>',  // pagination container
				'<a class="fas fa-angle-right"></a>'  // next button
			];

			e.innerHTML = html.join('');
			Pagination.e = e.getElementsByTagName('span')[0];
			Pagination.Buttons(e);
		},

		// init
		Init: function(e, data) {
			Pagination.Extend(data);
			Pagination.Create(e);
			Pagination.Start();
		}
	};
	var specials_swiper = new Swiper('.specials-swiper-container', {
		slidesPerView :5,
	  	spaceBetween:15,
		on: {
			slideChange: function () {
				Pagination.page = specials_swiper.activeIndex+1;
				Pagination.Start();
			}
		}
	});
	Pagination.Init(document.getElementById('specials_swiper_pagination'), {
		size: specials_swiper.slides.length, // pages size
		page: 1,  // selected page
		step: 1   // pages before and after current
	});
	/******Specials Swiper******/
	if(window_width<=545){
	  specials_swiper.params.spaceBetween = 0;
	  specials_swiper.params.slidesPerView = 1;
	  specials_swiper.update();
	}else if(window_width<=680){
	  specials_swiper.params.slidesPerView = 2;
	  specials_swiper.params.spaceBetween = 15;
	  specials_swiper.update();
	}else if(window_width<=880){
	  specials_swiper.params.slidesPerView = 3;
	  specials_swiper.params.spaceBetween = 15;
	  specials_swiper.update();
	}else if(window_width<=1100){
	  specials_swiper.params.slidesPerView = 4;
	  specials_swiper.params.spaceBetween = 15;
	  specials_swiper.update();
	}else if(window_width<=1290){
	  specials_swiper.params.slidesPerView = 5;
	  specials_swiper.params.spaceBetween = 15;
	  specials_swiper.update();
	}else if(window_width>1290) {
	  specials_swiper.params.spaceBetween = 15;
      specials_swiper.update();
	}
	$( window ).resize(function() {
		var window_width = $(window).width();
	  	if(window_width<=545){
		  specials_swiper.params.spaceBetween = 0;
		  specials_swiper.params.slidesPerView = 1;
		  specials_swiper.update();
		}else if(window_width<=680){
		  specials_swiper.params.slidesPerView = 2;
		  specials_swiper.params.spaceBetween = 15;
		  specials_swiper.update();
		}else if(window_width<=880){
		  specials_swiper.params.slidesPerView = 3;
		  specials_swiper.params.spaceBetween = 15;
		  specials_swiper.update();
		}else if(window_width<=1100){
		  specials_swiper.params.slidesPerView = 4;
		  specials_swiper.params.spaceBetween = 15;
		  specials_swiper.update();
		}else if(window_width<=1290){
		  specials_swiper.params.slidesPerView = 5;
		  specials_swiper.params.spaceBetween = 15;
		  specials_swiper.update();
		}else if(window_width>1290) {
		  specials_swiper.params.spaceBetween = 15;
          specials_swiper.update();
		}
	});
	<?php } ?>

	if(window_width<=545){
	  swiper[0].params.slidesPerView = 1;
	  swiper[1].params.slidesPerView = 1;
	  swiper[2].params.slidesPerView = 1;
	  swiper[0].params.spaceBetween = 0;
	  swiper[1].params.spaceBetween = 0;
	  swiper[2].params.spaceBetween = 0;
	  swiper[0].update();
	  swiper[1].update();
	  swiper[2].update();
	}
	else if(window_width<=680){
	  swiper[0].params.slidesPerView = 2;
	  swiper[1].params.slidesPerView = 2;
	  swiper[2].params.slidesPerView = 2;
	  swiper[0].update();
	  swiper[1].update();
	  swiper[2].update();
	}
	else if(window_width<=880){
	  swiper[0].params.slidesPerView = 3;
	  swiper[1].params.slidesPerView = 3;
	  swiper[2].params.slidesPerView = 3;
	  swiper[0].update();
	  swiper[1].update();
	  swiper[2].update();
	}
	else if(window_width<=1100){
	  swiper[0].params.slidesPerView = 4;
	  swiper[1].params.slidesPerView = 4;
	  swiper[2].params.slidesPerView = 4;
	  swiper[0].update();
	  swiper[1].update();
	  swiper[2].update();
	}
	else if(window_width<=1290){
	  swiper[0].params.slidesPerView = 5;
	  swiper[1].params.slidesPerView = 5;
	  swiper[2].params.slidesPerView = 5;
	  swiper[0].update();
	  swiper[1].update();
	  swiper[2].update();
	}
	else if(window_width>1290) {
	  swiper[0].params.slidesPerView = 6;
	  swiper[1].params.slidesPerView = 6;
	  swiper[2].params.slidesPerView = 6;
	  swiper[0].update();
	  swiper[1].update();
	  swiper[2].update();
	}
	$( window ).resize(function() {
		var window_width = $(window).width();
	  	if(window_width<=545){
		  swiper[0].params.slidesPerView = 1;
		  swiper[1].params.slidesPerView = 1;
		  swiper[2].params.slidesPerView = 1;
		  swiper[0].params.spaceBetween = 0;
		  swiper[1].params.spaceBetween = 0;
		  swiper[2].params.spaceBetween = 0;
		  swiper[0].update();
		  swiper[1].update();
		  swiper[2].update();
		}else if(window_width<=680){
		  swiper[0].params.slidesPerView = 2;
		  swiper[1].params.slidesPerView = 2;
		  swiper[2].params.slidesPerView = 2;
		  swiper[0].update();
		  swiper[1].update();
		  swiper[2].update();
		}else if(window_width<=880){
		  swiper[0].params.slidesPerView = 3;
		  swiper[1].params.slidesPerView = 3;
		  swiper[2].params.slidesPerView = 3;
		  swiper[0].update();
		  swiper[1].update();
		  swiper[2].update();
		}else if(window_width<=1100){
		  swiper[0].params.slidesPerView = 4;
		  swiper[1].params.slidesPerView = 4;
		  swiper[2].params.slidesPerView = 4;
		  specials_swiper.params.slidesPerView = 4;
		  specials_swiper.params.spaceBetween = 15;
		  specials_swiper.update();
		  swiper[0].update();
		  swiper[1].update();
		  swiper[2].update();
		}else if(window_width<=1290){
		  swiper[0].params.slidesPerView = 5;
		  swiper[1].params.slidesPerView = 5;
		  swiper[2].params.slidesPerView = 5;
		  swiper[0].update();
		  swiper[1].update();
		  swiper[2].update();
		}else if(window_width>1290) {
		  swiper[0].params.slidesPerView = 6;
		  swiper[1].params.slidesPerView = 6;
		  swiper[2].params.slidesPerView = 6;
		  swiper[0].update();
		  swiper[1].update();
		  swiper[2].update();
		}
	});

});
</script>


<?php get_footer(); ?>
