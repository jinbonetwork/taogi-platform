function pop_login() {
	jfe_popup_close();
	setTimeout(function() {
		_pop_login();
	}, 200);
}

function _pop_login() {
	var _url = jQuery('.login a.fancybox.ajax').attr('href').split(/\?/);
	var url = _url[0];
	var params = '';
	if(_url.length > 1)
		params = _url[1];

	params += (params ? '&' : '?')+'requestURI='+window.location.pathname;
	jfe_app_popup(url,params,'taogi-app-login',function() { jQuery('#email_id').focus(); });
}

function pop_regist() {
	jfe_popup_close();
	setTimeout(function() {
		_pop_regist();
	}, 200);
}

function _pop_regist(_url) {
	//var _url = jQuery('.register a.fancybox.ajax').attr('href').split(/\?/);
	_url = _url || jQuery('.register a.fancybox.ajax').attr('href').split(/\?/);
	var url = _url[0];
	var params = '';
	if(_url.length > 1)
		params = _url[1];
	params += (params ? '&' : '?')+'requestURI='+window.location.pathname;

	jfe_app_popup(url,params,'taogi-app-regist',function() { jQuery('#join_form').profileForm({ submit: function() { return check_regist('#join_form'); } }); });
}

jQuery(document).ready(function(e){
// BEGIN CODE
	var transEndEventNames = {
		'WebkitTransition' : 'webkitTransitionEnd', 
		'MozTransition'    : 'transitionend',       
		'transition'       : 'transitionend'        
	};

	if(Modernizr.touch) {
		SUPPORTS_TOUCH = true;
	} else {
		SUPPORTS_TOUCH = true;
	}

	if(Modernizr.csstransitions) {
		useWebkit = true;
	} else {
		useWebkit = false;
	}
	if(useWebkit) {
		var transition = Modernizr.prefixedCSS('transition');
		var transform = Modernizr.prefixedCSS('transform')
		var _transform = transform+' 0.3s ease-in';
		var transitionEnd = transEndEventNames[Modernizr.prefixed('transition')];
		if(transitionEnd) transitionEnd = transitionEnd+'.taogi';
	}
	jQuery('#taogi-net-site-main-container .taogi-gnb-switch a.switch').bind('touchstart click',function(e) {
		e.preventDefault();
		var tf = jQuery('.taogi-frame');
		if(tf.hasClass('menu-active')) {
			if(useWebkit) {
				tf.css({transition: _transform, transform: 'translate3d(0,0,0)'});
				tf.bind(transitionEnd,function() {
					jQuery(this).removeClass('menu-active');
					jQuery(this).unbind(transitionEnd);
				});
			} else {
				tf.css({left: '0'}).removeClass('menu-active');
			}
		} else {
			var offset = jQuery('#taogi-gnb').width();
			if(useWebkit) {
				tf.css({transition: _transform, transform: 'translate3d('+offset+'px,0,0)'});
				tf.bind(transitionEnd,function() {
					jQuery(this).addClass('menu-active');
					jQuery(this).unbind(transitionEnd);
				});
			} else {
				tf.css({left: offset+'px'}).addClass('menu-active');
			}
		}
//		jQuery('.taogi-frame').toggleClass('menu-active');
		return false;
	});

	if(!jQuery('.login a.fancybox.ajax').data('login-event')) {
		jQuery('.login a.fancybox.ajax').click(function(e) {
			e.preventDefault();
			_pop_login();
		})
		.data('login-event',true);
	}

	if(!jQuery('.register a.fancybox.ajax').data('regist-event')) {
		jQuery('.register a.fancybox.ajax').click(function(e) {
			e.preventDefault();
			_pop_regist();
		})
		.data('regist-event',true);
	}

	jQuery('.logout a').click(function(e) {
		e.preventDefault();
		var _url = jQuery(this).attr('href').split(/\?/);
		var url = _url[0];
		var params = '';
		if(_url.length > 1)
			params = _url[1];
		params += (params ? '&' : '?')+'requestURI='+window.location.pathname;
		document.location.href=url+params;
	});

// END CODE
});
