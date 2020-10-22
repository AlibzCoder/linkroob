</div>
</div>
<div id="footer">
	<div class="footer-right">
		<div class="logo">LinkRoob</div>
		<div class="cupyrite">
			<div> <span> © <?php echo date("Y"); ?> </span> <span>کلیه حقوق متعلق است به وب سایت لینک روب</span> </div>
		</div>
	</div>
	<div class="footer-left">
		<div class="about-contact"> 
			<a href="" class="btn btn-white">تماس با ما</a>
			<a href="#" class="btn btn-white">درباره ما</a>
		</div>
		<div class="trustlogo"><a referrerpolicy="origin" target="_blank" href="https://trustseal.enamad.ir/?id=175906&amp;Code=tvXmhdFgoHctbSDcBgbe"><img referrerpolicy="origin" src="https://Trustseal.eNamad.ir/logo.aspx?id=175906&amp;Code=tvXmhdFgoHctbSDcBgbe" alt="" style="cursor:pointer;filter: drop-shadow(0px 10px 10px #FFF);" id="tvXmhdFgoHctbSDcBgbe"></a></div>
	</div>
	<div class="back-to-up btn btn-black"> <i class="fas fa-arrow-up"></i> </div>
</div>
<script>
	$(document).ready(function() {
        /******Back to up button******/
		$(window).scroll(function() {
			if ($(this).scrollTop() > 200) {$('.back-to-up').fadeIn(200);}
			else {$('.back-to-up').fadeOut(200);}
			if($(window).scrollTop() + $(window).height() == $(document).height()) {
				$('.back-to-up').removeClass("btn-black");$('.back-to-up').addClass("btn-white");}
			else{$('.back-to-up').removeClass("btn-white");$('.back-to-up').addClass("btn-black");}
		});
		if ($(this).scrollTop() > 200) {$('.back-to-up').fadeIn(200);}
		else {$('.back-to-up').fadeOut(200);}
		if($(window).scrollTop() + $(window).height() == $(document).height()) {
				$('.back-to-up').removeClass("btn-black");$('.back-to-up').addClass("btn-white");}
			else{$('.back-to-up').removeClass("btn-white");$('.back-to-up').addClass("btn-black");}
		$('.back-to-up').click(function(event) {
			event.preventDefault();
			$('html, body').animate({scrollTop: 0}, 300);
		});
        /******Back to up button******/



		/******Navigation Button******/
		$(".mobile_button").on( "click", function(){
			if($(".mobile_button:checked").val()){
				$(".nav-icon").addClass("nav-icon-close");
				$('body').append('<div id="overlay" class="overlay"><div>');
			}else{
				$(".nav-icon").removeClass("nav-icon-close");
				$(".overlay").remove();
			}
			$(".overlay").css({'z-index':998});
			setTimeout(function(){$(".overlay").css({'background':'rgba(28, 71, 105, 0.4)'});},2);
			$(".overlay").click(function(){
				$(".nav-icon").removeClass("nav-icon-close");
				$(".mobile_button").prop('checked', false);
				$(".overlay").remove();
			});
		});
		/******Navigation Button******/

		$(".menu-dropdown-icon").on( "click", function(){
			$(this).parent().toggleClass("expanded");
		});

    });
    function ShowStory(Id){
		var temp_boxID = `story-${Id}`;
		$('body').append(`<div  id="${temp_boxID}"></div>`);
		$("#"+Id+" + .story-box").appendTo(`#${temp_boxID}`);

		$("#"+temp_boxID+" .story-box").css({display:'block'});
		$("#"+temp_boxID+" .story-box .story").addClass('img-story-size');

		setTimeout(function(){
			$("#"+temp_boxID+" .story-box .story-duration hr").css({width:"99.5%"});
			$("#"+temp_boxID+" .story-box .close-story").click(function(){
				$("#"+temp_boxID+" .story-box").css({display:'none'});
				$("#"+temp_boxID+" .story-box .story-duration hr").css({width:0});
				$("#"+temp_boxID+" .story-box .story").removeClass('img-story-size');
				$("#"+temp_boxID+" .story-box").appendTo($("#"+Id).parent());
				$("#"+temp_boxID).remove();
			});
		},200);
		var intervalId = null;
		var check = function(){
			var hr_width = ($("#"+temp_boxID+" .story-box .story-duration hr").width()) | 0;
			var all = (($("#"+temp_boxID+" .story-box .story-duration").width() * 99)/100) | 0;
			if(hr_width>=all){
				$("#"+temp_boxID+" .story-box").css({display:'none'});
				$("#"+temp_boxID+" .story-box .story-duration hr").css({width:0});
				$("#"+temp_boxID+" .story-box .story").removeClass('img-story-size');
				$("#"+temp_boxID+" .story-box").appendTo($("#"+Id).parent());
				$("#"+temp_boxID).remove();
				clearInterval(intervalId);
			}
		};
		intervalId = setInterval(check ,1000);
	}
</script>
</body>
</html>
