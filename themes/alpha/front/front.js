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
});
