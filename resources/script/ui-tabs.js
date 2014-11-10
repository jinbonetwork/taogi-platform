// jQuery(selector).buildTab();
(function($){
	$.fn.buildTab = function(options){
		this.each(function(index){
			var $this = $(this);
			var $tabs = jQuery.type($this.attr('data-tabs'))==='string' ? jQuery($this.attr('data-tabs')) : $this.children('ul:nth-child(1)');
			var $contents = jQuery.type($this.attr('data-contents'))==='string' ? jQuery($this.attr('data-contents')) : $this.children('ul:nth-child(2)');

			if(!$tabs.length||!$contents.length){
				return;
			}
			$tabs.addClass('buildTab_tabs').children('li').addClass('buildTab_tab');
			$contents.addClass('buildTab_contents').children('li').addClass('buildTab_content');
			var $default = jQuery.type($this.attr('data-tab-default'))==='string' ? jQuery($this.attr('data-tab-default')) : $this.find('li.buildTab_tab:nth-child(1),li.buildTab_content:nth-child(1)');
			$default.addClass('current');

			$tabs.find('a').each(function(index){
				jQuery(this).on('click',function(e){
					e.preventDefault();
					$tabs.children('li').removeClass('current');
					$tabs.children('li:nth-child('+(index+1)+')').addClass('current');
					$contents.children('li').removeClass('current');
					$contents.children('li:nth-child('+(index+1)+')').addClass('current');
					$catchResize.load();
				});
			});
		});
	};
})(jQuery);

jQuery(document).ready(function(e){
	jQuery('.ui-tabs-container').buildTab();
});
