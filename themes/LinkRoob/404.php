<?php get_header(); ?>

<style>
	#article{
		display:flex;
	}
	#continer{
		padding-bottom:1.5em;
	}
	h1{
		line-height:160px;
	}
  .box-404{
	width: 100%;
	height: 100%;
	font-size: 90px;
	font-family: sans-serif;
	transition:all 400ms linear;
  }
  .box-404,.E404{
	display: flex;
	justify-content: center;
	align-items: center;
  }
  .logo-text{
	color: transparent;
  }
  .logo-text h1{
	margin: 0;
  }
  .logo-text-1 h1{
	letter-spacing: 25.5px;
	transition:all 400ms;
  }
  .logo-text-2{
	display: flex;
  }
  .logo-text-2 h1{
	transition:all 400ms;
  }
  .logo-text-2 h1:nth-child(2),
  .logo-text-2 h1:nth-child(3){
	color: transparent;
  }
  .error-code-box{
	position: absolute;
	color: #2888d2;
	display: flex;
  }
  .error-code-box h1{
	transition:all 400ms;
  }
  .error-code-box h1:nth-child(2){
	color: transparent;
  }
  .error-code-box *{
	margin: 0;
  }
  .o{
	transition:all 400ms;
	position: absolute;
	color: #2888d2;
	margin: 0;
  }
  h3{
	  transition:all 400ms;
	  text-align:center;
	  color:#2888d2;
  }
  
  @media screen and (max-width: 900px) {
	  .box-404{
		  font-size:80px;
	  }
	  .logo-text-1 h1{
		letter-spacing: 22.5px;
	  }
	  h1{
		line-height:150px;
	  }
  }
   @media screen and (max-width: 700px) {
	  .box-404{
		  font-size:70px;
	  }
	  .logo-text-1 h1{
		letter-spacing: 20px;
	  }
	  h1{
		line-height:140px;
	  }
  }
  @media screen and (max-width: 500px) {
	  .box-404{
		  font-size:50px;
	  }
	  .logo-text-1 h1{
		letter-spacing: 14px;
	  }
	  h1{
		line-height:100px;
	  }
  }
  
</style>
<div id="article">
	<div id="continer">
		<div class="box-404 noselect">
		  <a href="<?php bloginfo("url"); ?>" class="E404">
			<div class="logo-text">
			  <div class="logo-text-1">
				<h1>Link</h1>
			  </div>
			  <div class="logo-text-2">
				<h1>R</h1>
				<h1>o</h1>
				<h1>o</h1>
				<h1>b</h1>
			  </div>
			</div>
			<div class="error-code-box">
			  <h1>4</h1>
			  <h1>o</h1>
			  <h1>4</h1>
			</div>
			<h1 class="o o1">o</h1>
			<h1 class="o o2">o</h1>
		  </a>
		</div>
		<h3>متاسفانه صفحه مورد نظر پیدا نشد</h3>
<script src="<?php echo get_template_directory_uri() ?>/js/jquery-3.3.1.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri() ?>/lib/chosen/chosen.jquery.js"></script>
<script>
	var top404 = $(".error-code-box").offset().top + $(".error-code-box").height();
	var left404 = $(".error-code-box").offset().left;
	$("h3").css({top:top404,left:left404,position:'absolute',width:$(".error-code-box").width()});
	
	
	var o_top = $(".error-code-box h1:nth-child(2)").offset().top;
	var o_left = $(".error-code-box h1:nth-child(2)").offset().left;
    $(".o1").css({top:o_top,left:o_left});
    $(".o2").css({top:o_top,left:o_left});

      $(".E404").hover(
        function() {
          //o's
          var o1_top = $(".logo-text-2 h1:nth-child(2)").offset().top;
          var o1_left = $(".logo-text-2 h1:nth-child(2)").offset().left;
          var o2_top = $(".logo-text-2 h1:nth-child(3)").offset().top;
          var o2_left = $(".logo-text-2 h1:nth-child(3)").offset().left;
          $(".o1").css({top:o1_top,left:o1_left});
          $(".o2").css({top:o2_top,left:o2_left});

          //other's
          $(".error-code-box h1:nth-child(1)").css({color:'transparent',marginRight:20});
          $(".error-code-box h1:nth-child(3)").css({color:'transparent',marginLeft:20});
          $(".logo-text-2 h1:nth-child(1)").css({color:'#2888d2'});
          $(".logo-text-2 h1:nth-child(4)").css({color:'#2888d2'});
          $(".logo-text-1 h1").css({color:'#2888d2'});  
		  $("h3").css({color:'transparent'});

        }, function() {
          var o_top = $(".error-code-box h1:nth-child(2)").offset().top;
          var o_left = $(".error-code-box h1:nth-child(2)").offset().left;
          $(".o1").css({top:o_top,left:o_left});
          $(".o2").css({top:o_top,left:o_left});


          $(".error-code-box h1:nth-child(1)").css({color:'#2888d2',marginRight:0});
          $(".error-code-box h1:nth-child(3)").css({color:'#2888d2',marginLeft:0});
          $(".logo-text-2 h1:nth-child(1)").css({color:'transparent'});
          $(".logo-text-2 h1:nth-child(4)").css({color:'transparent'});
          $(".logo-text-1 h1").css({color:'transparent'});
		  $("h3").css({color:'#2888d2'});
		  
        }
      );
	  
	  $( window ).resize(function() {
		  
			var top404 = $(".error-code-box").offset().top + $(".error-code-box").height();
			var left404 = $(".error-code-box").offset().left;
			$("h3").css({top:top404,left:left404,position:'absolute',width:$(".error-code-box").width()});
			
			
			var o_top = $(".error-code-box h1:nth-child(2)").offset().top;
			var o_left = $(".error-code-box h1:nth-child(2)").offset().left;
			$(".o1").css({top:o_top,left:o_left});
			$(".o2").css({top:o_top,left:o_left});
		  
	  });
	  
	  

</script>

<?php get_footer(); ?>