//-------------------------------------------------------------------------------
//	Register resize callback
//-------------------------------------------------------------------------------
(function($catchResize){
	$catchResize.frontAlpha = function(options){
		jQuery('#feature,#cover-background').css({
			width:$container.width(),
			height:$window.height()
		});

		jQuery('#cover-background-video').each(function(index){
			var $master = jQuery(this);
			var $shadow = jQuery('#cover-background-image');
			$master.css({
				top:$shadow.css('top'),
				left:$shadow.css('left'),
				width:$shadow.css('width'),
				height:$shadow.css('height'),
				marginTop:$shadow.css('margin-top'),
				marginLeft:$shadow.css('margin-left')
			});
		});

		jQuery('.on-scroll-fade').onScrollFade();
	};
})($catchResize);

jQuery(document).ready(function(e){
	// Cover Background
	jQuery('#cover-background').prependTo('.taogi-model-wrap');
	jQuery('.cover-background.master').on('load',function(e){
		$catchResize.frontAlpha();
	});
	jQuery('.cover-background.shadow').on('loadeddata',function(e){
		$this = jQuery(this);
		$this.siblings('.master').removeClass('master').addClass('shadow');
		$this.removeClass('shadow').addClass('master');
		$this[0].play();
		$catchResize.frontAlpha();
	});

	jQuery('ul.buttons li a').on('click',function(e){
		/*
		var $trigger = jQuery(this).parent();
		var $href = jQuery(this).attr('href');
		if($trigger.hasClass('join')){
			e.preventDefault();
			jfe_load_css(base_uri+'resources/css/ui-form.css');
			jQuery.getScript(base_uri+'resources/script/ui-form.js',function() {
				jfe_popup($href,null,base_uri+'resources/css/app-regist.css',base_uri+'resources/script/app-regist.js',function() { jQuery('#join_form').profileForm({ submit: function() { return check_regist('#join_form'); } }); });
			});
		}
		*/
	});
});
