// jQuery(selector).buildTable();
(function($){
	$.fn.buildTable = function(options){
		var $datatables = false;
		this.each(function(index){
			var $this = jQuery(this);
			$this.wrap('<div class="ui-table-container"></div>');
			$datatables = true;
		});
		if($datatables) {
			jQuery('<link>',{
				rel: 'stylesheet',
				type: 'text/css',
				href: '/resources/js/datatables/jquery.dataTables.min.css'
			}).appendTo('head');
			jQuery.getScript('/resources/js/datatables/jquery.dataTables.min.js',function(data,textStatus,jqxhr){
				if(textStatus=='success') {
					jQuery('table.datatable').DataTable();
				}
			});
		}
	};
})(jQuery);

jQuery(document).ready(function(e){
	jQuery('.ui-table').buildTable();
});
