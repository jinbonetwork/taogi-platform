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
			self.value = jQuery(this).val();
			console.log('VCARD: new user '+self.context+' => '+self.value);

			self.classString = 'default_image_container default_user_'+self.context;
			self.classActionCode = self.value!=''?'remove':'add';
			self.value = self.value!=''?self.value:self.attr('data-preview-default');

			jQuery.ajax({
				type: "POST",
				url: base_uri+"",
				data: "uid="+self.uid+"&file="+self.value+"&crop="+(self.context=='portrait'?1:0)
			})
			//.done(function(data,stat,jqXHR){
			.always(function(data,stat,jqXHR){
				switch(self.attr('data-preview-property')){
					case 'src':
						self.statement = self.value;
						self.classActionScope = 'parent';
					break;
					case 'style':
						self.statement = 'background-image:url("'+self.value+'")';
						self.classActionScope = '';
					break;
				}
				console.log('VCARD: filtered user '+self.context+' => '+self.value);

				self.classActionStatement = 'self.preview.'+(self.classActionScope!=''?self.classActionScope+'().':'')+self.classActionCode+'Class("'+self.classString+'");';
				console.log('VCARD: class action => '+self.classActionStatement);

				eval(self.classActionStatement);
				self.preview.attr(self.attr('data-preview-property'),self.statement);
				console.log('VCARD: set user '+self.context+' => '+self.value);
			})
			.fail(function(jqXHR,stat,error){
				alert('error!');
			//})
			//.always(function(){
			});
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
