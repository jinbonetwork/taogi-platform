// ui-form.js

(function($){
	jQuery.fn.displayError = function(options) {
		this.each(function(index) {
			var $this = jQuery(this);
			var p = $this.parent();
			$this.addClass('focus');
			p.addClass('focus');
			var e = p.find('span.ui-form-focus');
			if( e.length > 0 ) {
				e.html(options.message);
			} else {
				var e = jQuery('<span class="ui-form-focus">'+options.message+'</span>');
				e.css({
					'top' : ( $this.position().top + $this.outerHeight() + 8 )+'px',
					'left' : $this.position().left+'px'
				});
				p.append(e);
			}
			$this.focus();
			if(options.shake) {
				$this.addClass('shake');
				setTimeout(function() { $this.removeClass('shake'); }, 1000);
			}
		});
	};

	jQuery.fn.removeError = function(options) {
		this.each(function(index) {
			var $this = jQuery(this);
			$this.removeClass('focus');
			$this.parent().removeClass('focus');
			$this.parent().find('span.ui-form-focus').remove();
		});
	};

	jQuery.fn.okBox = function(options) {
		this.each(function(index) {
			var $this = jQuery(this);
			var p = $this.parent();
			$this.removeClass('focus');
			p.addClass('focus');
			var e = p.find('span.ui-form-focus');
			if( e.length > 0 ) {
				e.html(options.message).addClass('ok');
			} else {
				var e = jQuery('<span class="ui-form-focus ok">'+options.message+'</span>');
				e.css({
					'top' : ( $this.position().top + $this.outerHeight() + 8 )+'px',
					'left' : $this.position().left+'px'
				});
				p.append(e);
				setTimeout(function() {
					e.css({ opacity : 0 });
					setTimeout(function() {
						e.remove();
					},800);
				},1000);
			}
		});
	};

	jQuery.fn.dupCheck = function(options) {
		var $this = jQuery(this);
		if( !$this.val() ) {
			$this.displayError({
				message : options.label+'를 입력하세요',
				shake : 1
			});
			return;
		}
		var tname = $this.val();
		if( options.spell == 'alpha' && !tname.match(/^[0-9a-zA-Z\.\-_]+$/g) ) {
			$this.displayError({
				message : options.label+'는 영문, 숫자 그리고 .-_ 조합으로만 사용하실 수 있습니다.',
				shake : 1
			});
			return;
		} else if( options.spell == 'email' && !tname.match(/\S+@\S+\.\S+$/g) ) {
			$this.displayError({
				message : options.label+'는 E-Mail 형식으로 입력해주세요.',
				shake : 1
			});
			return;
		}
		var url = base_uri+"common/duplicate";
		var params = $this.attr('name')+"="+tname;
		jQuery.ajax({
			type: 'POST',
			url: url,
			data: params,
			dataType: 'json',
			beforeSend: function() {
				jfe_Block_onRequest(options.label+' 중복 검사중입니다. 잠시만 기다려주세요.');
			},
			success: function(json) {
				jfe_unBlock_afterRequest();
				var error = parseInt(json.error);
				var message = json.message;
				if(error != 0) {
					$this.displayError({
						message: message,
						shake : 1
					});
				} else {
					$this.okBox({
						message: message
					});
				}
			},
			error: function(xhr, status, errors) {
				jfe_unBlock_afterRequest();
				jQuery('#join_form').append('<div class="alert">'+errors+'</div>');
			}
		});
	};

	jQuery.fn.toggleCollapsed = function(options) {
		this.each(function(index) {
			var $this = jQuery(this);
			var collapse_element = jQuery(options.target);
			$this.attr('actual-height',collapse_element.actual('height'));
			if(options.button) {
				var bt = jQuery(options.button);
			} else {
				var bt = $this.find('.button.toggle');
			}
			bt.bind('click.taogi-profile',function(e) {
				e.preventDefault();
				if( $this.hasClass('collapsed') ) {
					$this.removeClass('collapsed');
					collapse_element.css( { 'height' : $this.attr('actual-height')+'px' } );
					collapse_element.find('input:first').focus();
				} else {
					$this.addClass('collapsed');
					collapse_element.css( { 'height' : '0' } );
				}
			});
			collapse_element.css( { 'height' : '0' } );
		});
	};

	jQuery.fn.taogifileupload = function(options) {
		this.each(function(index) {
			var $this = jQuery(this);
			var target = $this.attr('for');
			var container = $this.attr('ui-form-item-control-image');
			var img = container.find('img.ui-form-item-image-preview');
			$this.fileupload({
				dataType: 'json',
				done: function (e, data) {
					jQuery.each(data.result.files, function (index, file) {
						img.attr('src',file.url);
					});
				}
			});
		});
	};

	jQuery.fn.profileForm = function(options) {
		this.each(function(index) {
			var $this = jQuery(this);

			if( $this.hasClass('collapsed') ) {
				$this.toggleCollapsed({
					target : '.collapse_container',
					button : '.button.toggle'
				});
			}
			$this.find('.collapsed').toggleCollapsed({
				target : '.collapse_container',
				button : '.button.toggle'
			});

			var e1 = $this.find('input#email_id');
			var e2 = $this.find('input#email_id_confirm');
			if( e1.length > 0 && e2.length > 0 ) {
				e1.keyup(function(event) {
					if( jQuery(this).hasClass('focus') ) {
						if( jQuery(this).val() ) jQuery(this).removeError();
					}
				});
				e2.keyup(function(event) {
					if( jQuery(this).val() ) {
						if( e1.val().substr(0,jQuery(this).val().length) != jQuery(this).val() ) {
							jQuery(this).displayError({
								message : '이메일 아이디가 일치하지 않습니다.',
								shake : 0
							});
						} else {
							jQuery(this).removeError();
						}
					}
				});
			}
			if( e1.length > 0 ) {
				e1.next('.button.check').click(function(e) {
					e.preventDefault();
					e1.dupCheck({
						label : '이메일 주소',
						spell : 'email'
					});
				});
			}

			$this.find('input#name').keyup(function(event) {
				if( jQuery(this).hasClass('focus') ) {
					if( jQuery(this).val() ) jQuery(this).removeError();
				}
			});

			if($this.find('#taoginame').length > 0) {
				$this.find('#taoginame').next('.button.check').click(function(e) {
					e.preventDefault();
					$this.find('#taoginame').dupCheck({
						label : '따오기 주소',
						spell : 'alpha'
					});
				});
			}

			var p1 = $this.find('input#password');
			var p2 = $this.find('input#password_confirm');
			if( p1.length > 0 && p2.length > 0) {
				p1.keyup(function(event) {
					if( jQuery(this).hasClass('focus') ) {
						if( jQuery(this).val() ) jQuery(this).removeError();
					}
				});
				p2.keyup(function(event) {
					if( jQuery(this).val() ) {
						if( p1.val().substr(0,jQuery(this).val().length) != jQuery(this).val() ) {
							jQuery(this).displayError({
								message : '비밀번호가 일치하지 않습니다.',
								shake : 0
							});
						} else {
							jQuery(this).removeError();
						}
					}
				});
			}

			if(typeof(options.submit) != 'undefined') {
				var ret = true;
				$this.submit(function(e) {
					e.preventDefault();
					$this.find('input.necessary, textarea.necessary').each(function(index) {
						var $self = jQuery(this);
						if(!$self.val()) {
							var lv = $self.parents('.ui-form-item').find('label').text();
							$self.displayError({
								message : lv+"를 입력하세요",
								shake : 1
							});
							ret = false;
							return false;
						}
						if( $self.attr('name') == 'email_id_confirm' ) {
							if(jQuery('#email_id').val() != $self.val()) {
								$self.displayError({
									message : '이메일 아이디가 일치하지 않습니다.',
									shake : 0
								});
								ret = false;
								return false;
							}
						}
						if( $self.attr('name') == 'password' ) {
							if( $self.val().length < 8 ) {
								$self.displayError({
									message : '비밀번호는영문 숫자 조합 8자 이상입니다.',
									shake : 0
								});
								ret = false;
								return false;
							}
						}
						if( $self.attr('name') == 'password_confirm' ) {
							if(jQuery('#password').val() != $self.val()) {
								$self.displayError({
									message : '비밀번호가 일치하지 않습니다.',
									shake : 0
								});
								ret = false;
								return false;
							}
						}
						$self.removeError();
					});
					if(ret === false) return ret;
					if( p1.length > 0 && p1.val() ) {
						if( p1.val() && p1.val().length < 8) {
							p1.displayError({
								message : '비밀번호는영문 숫자 조합 8자 이상입니다.',
								shake : 1
							});
							return false;
						}
					}
					if( p1.length > 0 && p2.length > 0 && p1.val() ) {
						if( p1.val() != p2.val() ) {
							p2.displayError({
								message : '비밀번호가 일치하지 않습니다.',
								shake : 1
							});
							return false;
						}
					}
					if(typeof(options.submit) == 'function') {
						options.submit();
					} else {
						eval(options.submit);
					}
					return false;
				});
			}
		});
	};
})(jQuery);
