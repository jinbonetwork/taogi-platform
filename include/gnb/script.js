jQuery(document).ready(function(e){
	jQuery('.ui-controls.entrySidebar h3').on('click',function(e) {
		jQuery(this).closest('.ui-controls.entrySidebar').toggleClass('active');
	});
});
