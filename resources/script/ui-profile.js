jQuery(document).ready(function(e){
	jQuery('.image').each(function(index){
		var $this = jQuery(this);
		var $value = $this.find('.value');
		var src = $value.text();
		$value.html('<img src="'+src+'">');
	});

	jQuery('.button.edit').on('click',function(e){
		jQuery('.user-profile').addClass('mode-edit');
		jQuery('[data-editable="1"]').attr('contenteditable','true');
	});

	jQuery('.button.save').on('click',function(e){
		jQuery('.user-profile').removeClass('mode-edit');
		jQuery('[data-editable="1"]').attr('contenteditable','false');
	});
});
