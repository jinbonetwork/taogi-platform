// jQuery(selector).buildControls();
(function($){
	$.fn.buildControls = function(options){
		this.each(function(index){
			var $this = jQuery(this);
			$this.find('li.status a').each(function(index){
				jQuery(this).on('click',function(e){
					e.preventDefault();
					e.stopPropagation();
					var $trigger = jQuery(this);
					var $parent = $trigger.closest('.ui-controls');
					var $instance = $parent.attr('data-object-instance');
					var $controls = jQuery('.ui-controls[data-object-instance="'+$instance+'"]');
					var $json = $trigger.attr('href');
					jQuery.getJSON($json)
						.done(function(data){
							if(data.error) {
								alert('Can not change status!');
							} else {
								$controls.find('li.status').removeClass('current');
								switch(data.status) {
								case '0':
									$controls.find('li.status.private').addClass('current');
									break;
								case '1':
									$controls.find('li.status.open').addClass('current');
									break;
								case '2':
									$controls.find('li.status.public').addClass('current');
									break;
								}
								if(data.forkable=='1') {
									$controls.find('li.status.forkable').addClass('current');
								} else {
									$controls.find('li.status.notForkable').addClass('current');
								}
							}
						});
				});
			});
			$this.find('li.critical a').each(function(index){
				jQuery(this).on('click',function(e){
					if(confirm('This action cannot be undone!')) {
						alert('done');
					}else{
						alert('canceled');
						e.stopPropagation();
						e.preventDefault();
					}
				});
			});
		});
	};
})(jQuery);

// jQuery(selector).buildControlsSwitch()
(function($){
	$.fn.buildControlsSwitch = function(options){
		this.each(function(index){
			var $this = jQuery(this);
			$this.on('click',function(e){
				e.preventDefault();
				var $trigger = jQuery(this);
				var $controls = jQuery($trigger.attr('href'));
				var is_active = $controls.hasClass('active');

				$trigger.closest('.ui-items').find('.ui-controls').removeClass('active');
				if(is_active) {
					$controls.removeClass('active');
				} else {
					$controls.addClass('active');
				}
			});
		});
	};
})(jQuery);

jQuery(document).ready(function(e){
	jQuery('.ui-checkbox').each(function(index){
		var $this = jQuery(this);
		$this.on('click',function(e){
			e.stopPropagation();
		});
	});
	jQuery('.ui-controls').buildControls();
	jQuery('.ui-controls-switch').buildControlsSwitch();
	jQuery('.ui-controls a[title]').tooltip();
	/*
	jQuery('.ui-controls li.overlay a').on('click',function(e){
		e.preventDefault();
		var $trigger = jQuery(this);
		jfe_popup($trigger.attr('href'),'','');
	});
	*/
	jQuery('.ui-controls.bulk a').on('click',function(e){
		e.preventDefault();
		var $trigger = jQuery(this);
		var $form = $trigger.closest('form');
		var $action = $form.attr('action');
		var $_action = $trigger.attr('data-action');
		var $_key = $trigger.attr('data-key');
		var $_value = $trigger.attr('data-value');

		var $newAction = $action+$_action;
		$form.find('input#userActionKey').attr('name',$_key);
		$form.find('input#userActionKey').val($_value);
		$form.attr('action',$newAction);
		//console.log($form);
		$form.submit();
	});

});
