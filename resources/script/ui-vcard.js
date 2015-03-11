jQuery(document).ready(function(e){
	jQuery('.userImageController').each(function(index){
		var self = jQuery(this);

		jQuery.extend(self,{	
			uid: jQuery('#userIdInput').val(),
			context: self.find('.userImageInput').attr('name'),
			preview: jQuery(self.attr('data-preview-selector')),
			input: self.find('.userImageInput'),
			uploader: self.find('.uploader'),
			remover: self.find('.remover')
		});

		self.input.on('change',function(e){
			var $value = jQuery(this).val();
			var $statement;
			var $class;
			var $classActionScope;
			var $classActionCode;
			var $classActionStatement;

			$class = 'default_image_container default_user_'+self.context;
			$classActionCode = $value!=''?'remove':'add';
			console.log('VCARD: new user '+self.context+' => '+$value);
			$value = $value!=''?$value:self.attr('data-preview-default');

			/*
			jQuery.ajax({
				type: "POST",
				url: base_uri+"",
				data: {
					uid: self.uid,
					file: $value,
					crop: (self.context=='portrait'?1:0)
				}.join()
			})
			.done(function(data,stat,jqXHR){
				switch(self.attr('data-preview-property')){
					case 'src':
						$statement = $value;
						$classActionScope = 'parent';
					break;
					case 'style':
						$statement = 'background-image:url("'+$value+'")';
						$classActionScope = '';
					break;
				}
				console.log('VCARD: filtered user '+self.context+' => '+$value);

				$classActionStatement = 'self.preview.'+($classActionScope!=''?$classActionScope+'().':'')+$classActionCode+'Class("'+$class+'");';
				console.log('VCARD: class action => '+$classActionStatement);

				eval($classActionStatement);
				self.preview.attr(self.attr('data-preview-property'),$statement);
				console.log('VCARD: set user '+self.context+' => '+$value);
			})
			.fail(function(jqXHR,stat,error){
				alert('error!');
			})
			.always(function(){
			});
			*/
		});

		self.uploader.on('click',function(e){
			e.preventDefault();
			var options = jQuery.extend({},fancyboxFilemanagerOptions,{
				href: self.uploader.attr('href'),
				afterClose: function(){
					self.input.trigger('change');
				}
			});
			jQuery.fancybox.open(options);
		});

		self.remover.on('click',function(e){
			e.preventDefault();
			self.input.val('');
			self.input.trigger('change');
		});
	});
});
