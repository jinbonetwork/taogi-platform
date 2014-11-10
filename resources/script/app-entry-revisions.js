jQuery(document).ready(function(e){
	var $itembox = jQuery('.entry-revisions');
	var $eid = $itembox.attr('data-eid');
	var $vid = $itembox.attr('data-vid');
	var $citem = jQuery('.ui-checkbox[value="'+$vid+'"]').closest('.ui-item').addClass('current-vid');

	$citem.find('.ui-checkbox,.critical a').remove();
});
