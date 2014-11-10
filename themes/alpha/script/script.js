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
	jfe_popup(url,params,base_uri+'resources/css/app-login.css',base_uri+'resources/script/app-login.js',function() { jQuery('#email_id').focus(); });
}

function pop_regist() {
	jfe_popup_close();
	setTimeout(function() {
		_pop_regist();
	}, 200);
}

function _pop_regist() {
	var _url = jQuery('.register a.fancybox.ajax').attr('href').split(/\?/);
	var url = _url[0];
	var params = '';
	if(_url.length > 1)
		params = _url[1];
	params += (params ? '&' : '?')+'requestURI='+window.location.pathname;
	jfe_load_css(base_uri+'resources/css/app-regist.css');
	jfe_popup(url,params,base_uri+'themes/alpha/regist/style.css',base_uri+'resources/script/app-regist.js',function() { init_keyEvent(); });
}

jQuery(document).ready(function(e){
// BEGIN CODE
	if(useWebkit) {
		var transition = _getVendorPropertyName('transition');
		var transform = _getVendorPropertyName('transform');
		var _transform = transform+' 0.3s ease-in';
		var transitionEnd = TransitionEndeventNames[transition] || null;
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

	jQuery('.login a.fancybox.ajax').click(function(e) {
		e.preventDefault();
		_pop_login();
	});

	jQuery('.register a.fancybox.ajax').click(function(e) {
		e.preventDefault();
		_pop_regist();
	});

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
