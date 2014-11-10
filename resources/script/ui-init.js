// jQuery(selector).keepRatio()
(function($){
	$.fn.keepRatio = function(options){
		this.each(function(index){
			var $this = jQuery(this);	
			var ratio = $this.attr('data-width')/$this.attr('data-height');
			$this.css({
				height:$this.width()/ratio,
			});
		});
	};
})(jQuery);

// jQuery(selector).keepCenter()
(function($){
	$.fn.keepCenter = function(options){
		this.each(function(index){
			var $this = $(this);
			$this.css({
				position:'absolute',
				top:'50%',
				left:'50%',
				right:'none',
				bottom:'none',

				display:'block',
				marginTop:0-$this.height()/2,
				marginLeft:0-$this.width()/2,
				marginBottom:'none',
				marginRight:'none'
			});
		});
	};
})(jQuery);

// jQuery(selector).onScrollFade()
(function($){
	$.fn.onScrollFade = function(options){
		var $scrollers = {};
		this.each(function(index){
			var	$this = jQuery(this);
			var scroller = $this.attr('data-on-scroll-fade-scroller'),
				fader = '#'+$this.attr('id'),
				start = $this.attr('data-on-scroll-fade-start-position') || 0.2;
				init = false;

			if(typeof $scrollers[scroller] != 'object') {
				$scrollers[scroller] = new Array();
			}

			$scrollers[scroller].push({
				fader:fader,
				start:start,
				init:init
			});
		});
		for(var scrollerSelector in $scrollers) {
			var $scroller = jQuery(scrollerSelector);
			$scroller.each(function(index){
				$scroller.off('scroll');
				$scroller.on('scroll',function(e){
					var $item,margin,position,start,offset,opacity;
					for(var i in $scrollers[scrollerSelector]) {
						$fader = jQuery($scrollers[scrollerSelector][i].fader);

						var position,start;
						if(!$scrollers[scrollerSelector][i].init) {
							margin = $fader.outerHeight()-$fader.innerHeight()/3;
							position = Math.floor($fader.position().top-margin);
							start = Math.floor($scroller.height()*$scrollers[scrollerSelector][i].start);
							if(start>position) {
								start = position - 1;
							}
							$scrollers[scrollerSelector][i].start = start;
							$scrollers[scrollerSelector][i].init = true;
							
							//console.log(position);
						} else {
							start = $scrollers[scrollerSelector][i].start;
						}

						offset = Math.floor($fader.offset().top);
						opacity = offset/start;
						opacity = opacity>0 ? opacity : 0;

						$fader.css('opacity',opacity);

						//console.log(opacity+'='+offset+'/'+start);
					}
				});
				$scroller.trigger('scroll');
			});
		}
	};
})(jQuery);

//-------------------------------------------------------------------------------
//	catchResize
//-------------------------------------------------------------------------------
/** Initial register **/
(function($catchResize){
	// loader
	$catchResize.load = function(options){
		$catchResize.init();
		for(callback in $catchResize){
			if(callback=='load'||callback=='init'){
				continue;
			} else {
				eval('$catchResize.'+callback+'();');
			}
		}
	};
	// initial callback
	$catchResize.init = function(options){
		//	Device Consideration
		if( $window.width() > 1024 ){
			var _class = 'desktop';
		} else if( $window.width() > 480 ){
			var _class = 'tablet';
		} else {
			var _class = 'mobile';
		}

		//	Redraw entries
		jQuery('.keepRatio').keepRatio();
		jQuery('.keepCenter').keepCenter();
		jQuery('.keepCover').imagefill({runOnce:true});
	};
})($catchResize);
/** Execute **/
jQuery(document).ready(function(e){
	jQuery.getScript('/contribute/imagefill/js/jquery-imagefill.js')
		.success(function(e){
			$catchResize.load();
			jQuery(window).on('resize',function(e){
				$catchResize.load();
			});
		});

	jQuery('[data-use-hover-class]').hover(
		function(e){jQuery(this).addClass('hover');},
		function(e){jQuery(this).removeClass('hover');}
	);
	jQuery('[data-use-box-click]').on('click',function(e){
		if(e.target.nodeName!='A') {
			var $field = jQuery(this).attr('data-use-box-click');
			jQuery(this).find($field+' a').trigger('click');
			return;
		}
	});
	jQuery('.ui-checkbox-switch').on('click',function(e){
		var $checkbox = jQuery(this).closest('.ui-checkbox-group').find('.ui-checkbox');
		var $checked = jQuery(this).prop('checked');
		$checkbox.each(function(index){
			jQuery(this).prop('checked',$checked);
		});
	});
});
