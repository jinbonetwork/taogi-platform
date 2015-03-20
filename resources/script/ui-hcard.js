var userImageController = {};
jQuery(document).ready(function(e){
	jQuery('.userImageController').each(function(index){
		var uid = jQuery('#userIdInput').val();
		var context = jQuery(this).find('.userImageInput').attr('name');
		var self = userImageController[context] = {
			env: 'product', // product, test, ...
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
			error: null,

			preserve: function(){
				self.legacy = self.input.val();
				console.log('VCARD: preserve user '+self.context+' -- '+self.legacy);
			},

			restore: function(){
				self.value = self.input.val(self.legacy).val();
				console.log('VCARD: restore user '+self.context+' -- '+self.legacy);
			},

			process: function(){
				self.action.error = null;
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

			buildCropper: function(options){
				var options = jQuery.extend({},cropperOptions,{
					src: self.value,
					aspectRatio: taogi.portrait.width/taogi.portrait.height,
					minContainerWidth: taogi.portrait.width,
					minContainerHeight: taogi.portrait.height,
					wrap: '.cropperWrap',
					canvas: '.cropperCanvas',
					preview: '.cropperPreview',
					save: '.button.save'
				});

				self.cropper = jQuery('.cropper');
				jQuery.extend(self.cropper,{
					wrap: self.cropper.find(options.wrap),
					canvas: self.cropper.find(options.canvas),
					preview: self.cropper.find(options.preview),
					save: self.cropper.find(options.save)
				});
				
				self.cropper.canvas.image = self.cropper.canvas.find('img').attr('src',options.src);
				self.cropper.canvas.width = self.cropper.canvas.image.width();
				self.cropper.canvas.height = self.cropper.canvas.image.height();
				self.cropper.canvas.aspectRatio = self.cropper.canvas.width/self.cropper.canvas.height;
				self.cropper.canvas.css({
					'width': options.canvasWidth,
					'height': options.canvasHeight
				});

				self.cropper.preview.image = self.cropper.preview.find('img').attr('src',options.src);
				self.cropper.preview.width = options.previewWidth || 150;
				self.cropper.preview.aspectRatio = (typeof options.aspectRatio=='undefined')?self.cropper.canvas.aspectRatio:options.aspectRatio;
				self.cropper.preview.height = options.previewHeight || self.cropper.preview.width*self.cropper.preview.aspectRatio;
				self.cropper.preview.css({
					'width': options.previewWidth,
					'height': options.previewHeight
				});

				self.cropper.save.on('click',function(e){
					self.cropper.trigger('save');
				});

				self.cropper.on('save',function(e){
					self.cropper.data = self.cropper.canvas.image.cropper('getData');
					jQuery.extend(self.cropper.data,{
						context: self.context,
						origin: self.value
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
							self.action.error = null;
						},
						error: function(jqXHR,textStatus,errorThrown){
							var message = textStatus+' -- '+errorThrown;
							console.log('CROP: '+message);
							alert(message);
						},
						complete: function(jqXHR,textStatus){
							//console.log(jqXHR);
							jQuery.fancybox.close();
						}
					});
				});

				self.cropper.canvas.image.cropper(options);
			},

			crop: function(doUpdate){
				self.action.error = 'crop'; // all cases except successful crop are considered as errors
				doUpdate = doUpdate || false;
				var options = jQuery.extend({},fancyboxOptions,{
					type: 'ajax',
					href: base_uri+'include/user/forms/profile.cropper.html',
					beforeShow: function(){
						self.action.buildCropper();
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
				if(self.action.error){
					console.log('VCARD: skip update query -- '+self.action.error+' error');
					self.action.display();
				}else{
					self.query = {
						context: 'user_'+self.context,
						value: self.value
					};
					var url = base_uri+'common/update?'+decodeURIComponent(jQuery.param(self.query));
					console.log('VCARD: update query -- '+url);
					//$body.isLoading({position:'overlay'});
					switch(self.env){
						case 'product':
							var result;
							var message;
							jQuery.ajax(url,{
								dataType: 'json',
								success: function(data,textStatus,jqXHR){
									result = data.result?textStatus:'error';
									message = 'update '+result+' -- '+data.message;
									switch(result){
										default:
										break;
										case 'error':
											alert(message);
											self.action.error = 'update (server)';
										break;
									}
								},
								error: function(jqXHR,textStatus,errorThrown){
									message = 'update '+textStatus+' -- '+errorThrown;
									alert(message);
									self.action.error = 'update (client)';
								},
								complete: function(jqXHR,textStatus){
									console.log('VCARD: '+message);
									self.action.display();
								}
							});
						break;
						default:
							console.log('VCARD: skip update query -- '+self.env+'');
							self.action.display();
						break;
					}
				}
			},

			display: function(){
				if(self.action.error){
					self.action.restore();
				}
				self.display.valueFiltered = self.value==''?self.display.placeholder:self.value;
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

				self.display.evaluationMethod = self.display.valueFiltered==self.display.placeholder?'add':'remove';
				self.display.evaluationStatement = 'self.display.'+(self.display.evaluationScope!=''?self.display.evaluationScope+'().':'')+self.display.evaluationMethod+'Class("'+self.display.evaluationValue+'");';
				console.log('VCARD: class evaluation => '+self.display.evaluationStatement);
				eval(self.display.evaluationStatement);

				console.log('VCARD: set user '+self.context+' => '+self.display.valueSuffixed);
				if(self.action.error){
					console.log('VCARD: process failed -- '+self.action.error+' error');
				}
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
