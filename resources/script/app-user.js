jQuery(document).ready(function(e){
	jQuery('.app-user .image').each(function(index){
		var $this = jQuery(this);
		var $value = $this.find('.value');
		var src = $value.text();
		$value.html('<img src="'+src+'">');
	});

});
