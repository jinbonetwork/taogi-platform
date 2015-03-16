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

			if(self.value==''){
				self.value = self.attr('data-preview-default');
				self.update();
			}else{
				if(self.context=='portrait'){
					var dummy = jQuery(new Image()).attr({
						id: 'updatedPortraitDummy',
						style: 'display:none;',
						src: self.value
					});
					dummy.appendTo('body');
					dummy.on('load',function(e){
						if(dummy.width()!=dummy.height()){
							self.crop();
						}
						dummy.remove();
					});
				}else{
					self.update();
				}
			}
		});

		self.crop = function(){
			var options = jQuery.extend({},fancyboxOptions,{
				type: 'ajax',
				href: base_uri+'include/user/forms/profile.cropper.html',
				beforeShow: function(){
					var options = jQuery.extend({},cropperOptions,{
						crop: function(data){
						}
					});
					jQuery('#cropperImage').attr('src',self.value).cropper(options);
				}
			});
			jQuery.fancybox.open(options);
		};

		self.update = function(){
			console.log('VCARD: filtered user '+self.context+' => '+self.value);

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
			self.classString = 'default_image_container default_user_'+self.context;
			self.classActionCode = self.value!=self.attr('data-preview-default')?'remove':'add';
			self.classActionStatement = 'self.preview.'+(self.classActionScope!=''?self.classActionScope+'().':'')+self.classActionCode+'Class("'+self.classString+'");';
			console.log('VCARD: class action => '+self.classActionStatement);

			eval(self.classActionStatement);
			self.preview.attr(self.attr('data-preview-property'),self.statement);
			console.log('VCARD: set user '+self.context+' => '+self.value);
		};

		self.uploader.on('click',function(e){
			e.preventDefault();
			var options = jQuery.extend({},fancyboxOptions,{
				type: 'iframe',
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
