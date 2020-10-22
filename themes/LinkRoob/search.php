<?php /* Template Name: search-page */ get_header();

	$s = $_GET['srch'];
	$page = isset($_GET['index']) ? $_GET['index'] : 1;

	if(!empty($s)){
		$cat = SearchInCat($s);
		$subcat = SearchInSubCat($s);
		$social_network = SearchInSocialNetworks($s);
		if(!empty($cat)){wp_redirect(home_url('cat-view/?cat_id='.$cat->id));exit;}
		else if(!empty($subcat)){wp_redirect(home_url('cat-view/?cat_id='.$subcat->cat_id.'&subcat_id='.$subcat->id));exit;}
		else if(!empty($social_network)){wp_redirect(home_url('cat-view/?social_network_id='.$social_network->id));exit;}
		else{
			$results = array();
			$wp_post_srch = SearchInWpPost($s);
			$chanels_ditails_srch = SearchInChanelsDitails($s);
			$hashtags_srch = SearchInHashtags($s);
			$event_srch = SearchInEvents($s);


			if(!empty($wp_post_srch[0]->post_content)){$results = array_merge($results,$wp_post_srch);}
			if(!empty($chanels_ditails_srch)&&!empty($chanels_ditails_srch[0]->chanel_id)){$results = array_merge($results,$chanels_ditails_srch);}
			if(!empty($hashtags_srch)&&!empty($hashtags_srch[0]->chanel_id)){$results = array_merge($results,$hashtags_srch);}
			if(!empty($event_srch)&&!empty($event_srch[0]->chanel_id)){$results = array_merge($results,$event_srch);}


			//Remove Duplicate Value
			$results = array_map('json_encode', $results);
			$results = array_unique($results);
			$results = array_map('json_decode', $results);

			//Sort Values by date
			usort($results, function($a, $b) { return strcmp($a->post_date,$b->post_date); });

		}
?>
<div id="article">
	<div id="continer">
		<div class="catview search-title">
			<div><h2><?php echo 'نتایج برای جستجوی  :&nbsp; <span> '.$s.' </span>'; ?></h2></div>
			<hr>
		</div>
		<?php
			$page_post_count = 20;
			if(count($results)>0){
				$pages_count = count($results) / $page_post_count;
				if($pages_count-(int)$pages_count>0){
					$pages_count = (int)$pages_count +1;
				}
				$page_posts_index = $page * $page_post_count - $page_post_count;
		?>
			<div class="chanel-previews-gridview">
				<?php foreach (array_slice($results,$page_posts_index,$page_post_count) as $p => $post){ 
					$chanel = get_chanel($post->chanel_id);
					?>
					<div class="chanel_preview <?php if($chanel->vip_date>time()) echo 'spical-chanel-preview'; else echo 'normal-chanel-preview'; ?>">
					  <div class="chanel_preview_image">
						  <div class="chanel_preview_image">
							  <div class="chanel_image_border 
							  <?php if(!empty($chanel->story_time)&&time()<$chanel->story_time) echo "chanel_image_border_story";
							  else echo "chanel_image_border_normal"; ?>" id="<?php echo $chanel->id.rand(111,999); ?>" onclick="ShowStory(this.id)">
								<img src="<?php echo home_url().'/wp-content/uploads/img/'.$chanel->chanel_id.".jpg" ?>" alt="">
							  </div>
							  <?php if(!empty($chanel->story_time)&&time()<$chanel->story_time){ ?>
								  <div class="story-box">
									<div class="story-duration" <?php if(!empty($chanel->story_duration)) echo 'style="transition:'.$chanel->story_duration.'s all"' ?>><hr/></div>
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
					  <div>
						  <a href="<?php echo $chanel->guid; ?>">
							<div class="chanel_preview_name">
							  <h4><?php echo $chanel->post_title; ?></h4>
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
			echo '<div class="chanels-not-found"><h3>هیچ نتیجه ای یافت نشد</h3></div>';
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
    });
</script>
<?php }else{wp_redirect(home_url('cat-view'));exit;}
get_footer(); ?>


