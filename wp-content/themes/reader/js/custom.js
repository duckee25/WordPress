var $ = jQuery.noConflict();
(function($) {
'use strict';
$(document).ready(function() {
  'use strict';
  
  /* FIT VIDEOS WITH SCREEN SIZE */
   $(".video-player").fitVids();

   /* CONTENT LOADER */
   $('.content-to-load').jscroll({
      nextSelector: 'a.jscroll-load-more',
     loadingHtml: '<div class="progress"> <div class="progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%;"><span> 75% </span></div></div>',
     autoTrigger: true,
     callback: function() {
      $(".video-player").fitVids();
      $('.jscroll-added').last().find('.mejs-player').mediaelementplayer();
     }
   });
   $('.searchform input[type="text"]').attr('placeholder','Search for');
   $('.mc_input').addClass('form-control').attr('placeholder','@yourmail.com');
   $('.mc_merge_var').addClass('input-group');
   $('#mc_signup_submit').addClass('btn-prime').val('go');
   $('.mc_signup_submit').addClass('input-group-btn');
   if($('.blog-style-one,.blog-style-two').length&&($('.left-sidebar').length)){
    var height_b = $('.blog-style-one').height();
    var height_s = $('.right-sidebar').height();
	if(height_s>(height_b)){
		$('.footer').css('padding-top','100px');
		$('.right-sidebar').addClass('right-sidebar-ok');
		
	}
	if($('.left-sidebar').length){
		var height_l = $('.left-sidebar>div').height();
		if(height_l>height_b){
		$('.footer').css('padding-top','80px');
		$('.footer').css('margin-top','50px');
		
		}	
	}
   }
});

/* LITTLE ANIMATION ON NAVBAR ON-SCROLL */
$(window).scroll(function() {    
  'use strict';
      var scroll = $(window).scrollTop();

    if (scroll >= 100) {
        $("header .navbar").addClass("sticky");
    } else {
        $("header .navbar").removeClass("sticky");
    }
});

/* TWEETER FEED */
if($('.tweet').length){
  'use strict';
$('.tweet').each(function(){

			var ele = $(this);
			var user_t = $(this).data('username');
			var count_t = $(this).data('count');
			$(ele).twittie({
  dateFormat: "%B %d, %Y",
  template: '<p class="twt">{{tweet}}</p> <p class="date">{{date}}</p>',
  hideReplies: true,
  username:user_t,
  count:count_t
});
});
}
 })(jQuery);