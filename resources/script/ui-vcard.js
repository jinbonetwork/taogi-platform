var userImageController = {};
jQuery(document).ready(function(e){
	jQuery('.userImageController').each(function(index){
		var uid = jQuery('#userIdInput').val();
		var context = jQuery(this).find('.userImageInput').attr('name');
		var self = userImageController[context] = {
			env: 'test',
			uid: uid,
			context: context,

			container: jQuery(this),
			input: jQuery(this).find('.userImageInput'),
			display: jQuery.extend(jQuery(jQuery(this).attr('data-display-selector')),{
				placeholder: jQuery(this).attr('data-display-default'),
				property: jQuery(this).attr('data-display-property'),
				width: (typeof jQuery(this).attr('data-file-width')!='undefined'?jQuery(this).attr('data-file-width'):null),
				height: (typeof jQuery(this).attr('data-file-height')!='undefined'?jQuery(this).attr('data-file-height'):null),
				evaluationValue: 'default_image_container default_user_'+context
			}),
			uploader: jQuery(this).find('a.uploader'),
			remover: jQuery(this).find('a.remover')
		};

		self.action = {
			preserve: function(){
				self.legacy = self.input.val();
				console.log('VCARD: preserve user '+self.context+' -- '+self.legacy);
			},

			restore: function(){
				self.input.val(self.legacy);
				console.log('VCARD: restore user '+self.context+' -- '+self.legacy);
			},

			process: function(){
				if(self.value!=''&&self.value!=self.display.placeholder&&self.display.width&&self.display.height){
					var dummy = jQuery(new Image()).attr({
						id: 'userImageDummy',
						style: 'display:none;',
						src: self.value
					}).appendTo('body').on('load',function(e){
						self.ratio = self.display.width/self.display.height;
						dummy.ratio = dummy.width()/dummy.height();
						if(dummy.ratio!=self.ratio){
							self.action.crop(true);
						}else{
							self.action.update();
						}
						dummy.remove();
					});
				}else{
					self.action.update();
				}
			},

			crop: function(doUpdate){
				doUpdate = doUpdate || false;
				var options = jQuery.extend({},fancyboxOptions,{
					type: 'ajax',
					href: base_uri+'include/user/forms/profile.cropper.html',
					beforeShow: function(){
						var options = jQuery.extend({},cropperOptions,{
						});
						self.cropper = jQuery('#cropper');
						self.cropper.image = self.cropper.find('#cropperImage').attr('src',self.value).cropper(options);
						self.cropper.save = self.cropper.find('.button.save').on('click',function(e){
							self.cropper.data = jQuery.extend({},self.cropper.image.cropper('getData'),{
								mode: 'portrait',
								origin: self.value,
							});
							var url = base_uri+'common/crop?'+decodeURIComponent(jQuery.param(self.cropper.data));
							console.log('CROP: query -- '+url);
							self.cropper.isLoading({position:'overlay'});
							jQuery.ajax(url,{
								dataType: 'json',
								success: function(data,textStatus,jqXHR){
									console.log('CROP: '+textStatus+' -- '+self.value+' => '+data.cropped);
									self.value = data.cropped;
									self.input.val(self.value);
								},
								error: function(jqXHR,textStatus,errorThrown){
									var message = textStatus+' -- '+errorThrown;
									console.log('CROP: '+message);
									alert(message);
									self.action.restore();
								},
								complete: function(jqXHR,textStatus){
									//console.log(jqXHR);
									jQuery.fancybox.close();
								}
							});
						});
					},
					afterClose: function(){
						if(doUpdate){
							self.action.update();
						}
					}
				});
				jQuery.fancybox.open(options);
			},

			update: function(){
				self.query = {
					context: self.context,
					value: self.value
				};
				var url = base_uri+'common/update?'+decodeURIComponent(jQuery.param(self.query));
				console.log('VCARD: update query -- '+url);
				//$body.isLoading({position:'overlay'});
				if(self.env=='product'){
					jQuery.ajax(url,{
						dataType: 'json',
						success: function(data,textStatus,jqXHR){
							console.log('VCARD: '+data.result);
						},
						error: function(jqXHR,textStatus,errorThrown){
							var message = textStatus+' ('+errorThrown+')';
							console.log('VCARD: '+message);
							alert(message);
							if(self.legacy!=self.value){
								self.action.restore();
							}
						},
						complete: function(jqXHR,textStatus){
							console.log('VCARD: update query '+textStatus);
							//console.log(jqXHR);
						}
					});
				}
				self.action.display();
			},

			display: function(){
				self.display.valueFiltered = self.value!=''?self.value:self.display.placeholder;
				self.display.valueSuffixed = self.display.valueFiltered+'?v='+(new Date()).getTime();
				console.log('VCARD: filtered user '+self.context+' => '+self.display.valueFiltered);

				switch(self.display.property){
					case 'src':
						self.display.statement = self.display.valueSuffixed;
						self.display.evaluationScope = 'parent';
					break;
					case 'style':
						self.display.statement = 'background-image:url("'+self.display.valueSuffixed+'")';
						self.display.evaluationScope = '';
					break;
				}
				self.display.attr(self.display.property,self.display.statement);

				self.display.evaluationMethod = self.display.valueFiltered!=self.display.placeholder?'remove':'add';
				self.display.evaluationStatement = 'self.display.'+(self.display.evaluationScope!=''?self.display.evaluationScope+'().':'')+self.display.evaluationMethod+'Class("'+self.display.evaluationValue+'");';
				console.log('VCARD: class evaluation => '+self.display.evaluationStatement);
				eval(self.display.evaluationStatement);

				console.log('VCARD: set user '+self.context+' => '+self.display.valueSuffixed);
			}
		};

		self.input.on('change',function(e){
			self.value = self.input.val();
			console.log('VCARD: new user '+self.context+' => '+self.value);
			self.action.process();
		});

		self.uploader.on('click',function(e){
			e.preventDefault();
			self.action.preserve();
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
			self.action.preserve();
			self.input.val('').trigger('change');
		});
	});
});
