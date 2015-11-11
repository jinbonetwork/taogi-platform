jQuery(document).ready(function(e){
	jQuery.fn.buildTagoiEmbedCode = function(width,height) {
		return this.each(function() {
			var $el = jQuery(this);

			if(width.match(/^[0-9]{1,}$/)) width = width+'px';
			if(height.match(/^[0-9]{1,}$/)) height = height+'px';

			var $clone = jQuery('<div id="taogi-embed-code-value"></div>');
			var $div = jQuery('<div style="width:'+width+'; position:relative; padding-bottom: 75%; height: ' + (height == 'auto' ? 0 : height) + '; overflow: hidden;"></div>');
			if(height == 'auto') {
				var $iframe = jQuery('<iframe src="' + $el.attr('data-src') + '" style="position: absolute; top: 0; left: 0; width: 100% !important; height: 100% !important; border: 0;" frameborder="0"></iframe>');
				$div.append( $iframe );
				$clone.append( $div );
			} else {
				var $iframe = jQuery('<iframe src="' + $el.attr('data-src') + '" style="width: ' + width + ' !important; height: ' + height + ' !important; border: 0;" frameborder="0"></iframe>');
				$clone.append( $iframe );
			}
			$el.val($clone.html());
		});
	};

	jQuery.fn.changeTaogiEmbedCode = function() {
		return this.each(function() {
			var $el = jQuery(this);
			var width_input = $el.find('input[name="width"]');
			var ta = $el.find('textarea');
			var height_auto_input = $el.find('input[name="auto"]');
			var height_input_wrapper = $el.find('span.taogi-embed-height-wrap');
			var height_input = $el.find('input[name="height"]');
			if( width_input.data('event-handler') != 1 ) {

				/* init embed code editor */
				width_input.data('event-handler', 1);
				ta.buildTagoiEmbedCode('100%','auto');

				width_input.bind('input.taogi',function(e) {
					if( height_auto_input.is(":checked") ) {
						ta.buildTagoiEmbedCode(jQuery(this).val(),'auto');
					} else {
						ta.buildTagoiEmbedCode(jQuery(this).val(),height_input.val());
					}
				});

				var height_input_wrapper = $el.find('span.taogi-embed-height-wrap');
				height_auto_input.change(function() {
					if( jQuery(this).is(":checked") ) {
						height_input_wrapper.removeClass('show');
						ta.buildTagoiEmbedCode(jQuery(this).val(),'auto');
					} else {
						height_input_wrapper.addClass('show');
						ta.buildTagoiEmbedCode(jQuery(this).val(),height_input.val());
					}
				});

				height_input.bind('input.taogi',function(e) {
					ta.buildTagoiEmbedCode(jQuery(this).val(),jQuery(this).val());
				});

				/* init help tooltip */
				$el.find('a.open-tooltip').click(function(e) {
					e.preventDefault();
					var $tooltip = jQuery(this).parent().find('.help');
					if( $tooltip.hasClass('show') ) $tooltip.removeClass('show');
					else {
						$tooltip.addClass('show');
						if( $tooltip.data('event-handler') != 1 ) {
							$tooltip.find('a.close-tooltip').click(function(e) {
								e.preventDefault();
								jQuery(this).parents('.help').removeClass('show');
							});
							$tooltip.data('event-handler',1)
						}
					}
				});
			}
		});
	};

	jQuery('#taogi-gnb ul.social li.embed a').click(function(event) {
		event.preventDefault();
		var embed = jQuery('#taogi-gnb #taogi-embed-code');
		if(embed.hasClass('collapsed')) {
			embed.taogiSlideDown('500ms','ease-in-out',function() {
				embed.removeClass('collapsed');
				embed.changeTaogiEmbedCode();
			});
		} else {
			embed.taogiSlideUp('500ms','ease-in-out',function() {
				embed.addClass('collapsed');
			});
		}
	});

/*	jQuery('#taogi-gnb #taogi-embed-code input[name="width"]').change(function(e) {
		var ta = jQuery('#taogi-gnb #taogi-embed-code textarea');
		var clone = jQuery('<div id="taogi-embed-code-value">'+ta.val()+'</div>');
		console.log(clone);
		clone.find('div').attr('width',jQuery(this).val());
		ta.val(clone.html());
	}); */

	jQuery('.ui-controls.entrySidebar h3').on('click',function(e) {
		jQuery(this).closest('.ui-controls.entrySidebar').toggleClass('active');
	});
});
