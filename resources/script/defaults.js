var $window;
var $document;
var $body;
var $catchResize = {}; // callback array for resize event
var tinymceOptions = {
	language: 'ko_KR',
	theme: "modern",
	/*
	toolbar: [
		"undo redo",
		"bold italic underline",
		"styleselect forecolor backcolor",
		"alignleft aligncenter alignright alignjustify",
		"bullist numlist outdent indent",
		"link unlink",
		"image media filemanager",
		"preview code fullscreen"
	].join(' | '),
	*/
	toolbar: [
		"bold italic underline",
		"forecolor backcolor",
		"bullist numlist outdent indent",
		"link unlink image media filemanager"
	].join(' | '),
	/*
	plugins: [ 
		"advlist autolink autoresize link image lists charmap print preview hr anchor pagebreak spellchecker",
		"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
		"save table contextmenu directionality emoticons template paste textcolor filemanager"
	]
	*/
	plugins: [ 
		"autoresize link image lists charmap hr pagebreak",
		"searchreplace insertdatetime media",
		"table contextmenu paste textcolor filemanager"
	],
	menubar: false,
	image_advtab: true
};
var fancyboxOptions = {
	width       : 900,
	height      : 600,
	type        : 'iframe',
	fitToView   : true,
	autoSize    : false,
	autoResize  : true
};
var cropperOptions = {
	minCropBoxWidth: 64,
	minCropBoxHeight: 64,
	strict: true,
	checkImageOrigin: false,
	autoCrop: true,
	autoCropArea: 1,
	dragCrop: true,
	movable: true,
	resizable: true,
	zoomable: false,
	rotatable: false,
	mouseWheelZoom: false,
	touchDragZoom: false,
	responsive: true,
	modal: true,
	guides: true,
	highlight: true,
	background: false,
};

/***************************************************************************************
    Load one or multiple JavaScript files from the server using a GET HTTP request,
	then execute them..
    https://github.com/hudsonfoo/jquery-getscripts
	MIT Licensed.
****************************************************************************************/
var jsResource = [];
(function ($) {
	"use strict";

	if ($.getScripts) { return; }

	$.getScripts = function (options) {
		var _options, _sync, _async, _response;

		_options = $.extend({
			'async': false,
			'cache': true
		}, options);

		if (typeof _options.urls === 'string') {
			_options.urls = [_options.urls];
		}

		_response = [];

		_sync = function () {
			var _url = _options.urls.shift();
			$.ajax({
				url: _url,
				dataType: 'script',
				cache: _options.cache,
				success: function () {
					_response.push(arguments);
					jsResource.push(_url);
					if (_options.urls.length > 0) {
						_sync();
					} else if (typeof options.success === 'function') {
						options.success($.merge([], _response));
					}
				}
			});
		};

		_async = function (url) {
			_response.push(arguments);
			jsResource.push(url);
			if (_response.length === _options.urls.length &&
				typeof options.success === 'function') {
					options.success($.merge([], _response));
				}
		};

		if (_options.async === true) {
			for (var i = 0; i < _options.urls.length; i++) {
				$.ajax({
					url: _options.urls[i],
					dataType: 'script',
					cache: _options.cache,
					success: _async(_options.urls[i])
				});
			}
		} else {
			_sync();
		}
	};
}(jQuery));

jQuery(document).ready(function(e){
	$window = jQuery(window);
	$document = jQuery(document);
	$body = jQuery('body');
	$container = jQuery('.taogi-model-wrap article');


});

//-----------------------------------------------------------------------------
//	Core
//-----------------------------------------------------------------------------
function jfe_Block_onRequest(message,element) {
	// message -- label
	// element -- selector
	if(element) {
		jQuery(element).isLoading({
			text: message,
			position: 'overlay'
		});
		var ph = jQuery(element).height();
		var h = jQuery('.isloading-overlay .isloading-wrapper').height();
		jQuery('.isloading-overlay .isloading-wrapper').css('top',Math.round((ph-h)/2)+'px');
	} else {
		jQuery.isLoading({
			text: message,
			position: 'overlay'
		});
		var ph = jQuery(window).height();
		var h = jQuery('.isloading-overlay .isloading-wrapper').height();
		jQuery('.isloading-overlay .isloading-wrapper').css('top',Math.round((ph-h)/2)+'px');
	}
}

function jfe_unBlock_afterRequest(element) {
	if(element) {
		jQuery(element).isLoading('hide');
	} else {
		jQuery.isLoading('hide');
	}
}

function jfe_load_css(css_url,classes) {
	if(css_url) {
		$("head").append("<link>");
		css = $("head").children(":last");
		css.attr({
			rel: "stylesheet",
			type: "text/css",
			href: css_url
		});
		if(classes) {
			css.addClass(classes);
		}
	}
}

function jfe_load_css_obj(cssObj) {
	if(cssObj.css) {
		$("head").append("<link>");
		css = $("head").children(":last");
		css.attr({
			rel: "stylesheet",
			type: "text/css",
			id: cssObj.id,
			href: cssObj.css
		});
	}
}

function jfe_popup(url,params,css_url,script_url,callback) {
	if(css_url) {
		jfe_load_css(css_url);
	}
	if(params) params += "&output=nolayout";
    else params = "?output=nolayout";
	if(script_url) {
		$.getScript(script_url,function() {
			jfe_fancybox_open(url+params,callback);
		});
    } else {
		jfe_fancybox_open(url+params,callback);
	}
}

jfeResourceMap = {};
function _jfe_load_fancy_app(app,url,callback) {
	if(typeof jfeResourceMap[app] != 'undefined') {
		json = jfeResourceMap[app];
		var n_js = [];
		jQuery.each(json.css,function(i,css) {
			var id = css.id;
			var n = true;
			if(id) {
				if(jQuery('link#'+id).length > 0) n = false;
			}
			if(n === true) {
				if(css.type == 'src') jfe_load_css_obj(css);
			}
		});
		jQuery.each(json.js,function(i,js) {
			var id = js.id;
			var n = true;
			if(id) {
				if(jQuery('script#'+id).length > 0) n = false;
			}
			if(js.type == 'src' && jsResource.indexOf(js.script) != -1) n = false;
			if(n === true) {
				if(js.type == 'src') n_js.push(js.script);
			}
		});
		if(n_js.length > 0) {
			$.getScripts({
				urls: n_js,
				cache: true,
				async: true,
				success: function(response) {
					jfe_fancybox_open(url,callback);
				}
			});
		} else {
			jfe_fancybox_open(url,callback);
		}
	}
}

function jfe_app_popup(url,params,app,callback) {
	if(params) params += "&output=nolayout";
	else params = "?output=nolayout";
	if(typeof jfeResourceMap[app] != 'undefined') {
		_jfe_load_fancy_app(app,url+params,callback);
	} else {
		var map_url = base_uri+"common/resources";
		var map_params = "resource[]="+app+"&resource[]=fancybox";
		jQuery.ajax({
			type: 'GET',
			url : map_url,
			data: map_params,
			dataType: 'json',
			success: function(json) {
				jfeResourceMap[app] = json;
				_jfe_load_fancy_app(app,url+params,callback);
			},
			error: function(xhr, status, error) {
				alert(error);
			}
		});
	}
}

function jfe_popup_close() {
	jQuery.fancybox.close();
}

function jfe_fancybox_open(url,callback) {
	if(typeof(fancybox) !== 'undefined') {
		jfe_fancybox_load(url,callback);
	} else {
		if(jQuery('link.fancybox').length < 1) {
			jfe_load_css(base_uri+"resources/js/fancyBox/source/jquery.fancybox.css",'fancybox');
		}
		if(jQuery('script.fancybox').length < 1) {
			jQuery.getScript(base_uri+"resources/js/fancyBox/source/jquery.fancybox.pack.js",function() {
				jfe_fancybox_load(url,callback);
			});
		} else {
			jfe_fancybox_load(url,callback);
		}
	}
}

function jfe_fancybox_load(url,callback) {
	jQuery.fancybox({
		href		: url,
		width       : 900,
		height      : 600,
		type        : 'ajax',
		fitToView   : true,
		autoSize    : false,
		autoResize  : true,
		onUpdate	: function() {
			if(callback) {
				if(typeof callback === 'function') {
					callback();
				} else {
					eval(callback);
				}
			}
		}
	});
}

var TaogiContainerStyle;
function _getVendorPropertyName(prop) {
	var prefixes = ['Moz', 'Webkit', 'O', 'ms'];
	var prop_ = prop.charAt(0).toUpperCase() + prop.substr(1);

	for (var i=0; i<prefixes.length; ++i) {
		var vendorProp = prefixes[i] + prop_;
		if (vendorProp in TaogiContainerStyle) { return '-'+prefixes[i]+'-'+prop_; }
	}
}

var TransitionEndeventNames = {
	'transition':      'transitionend',
	'-Moz-Transition':  'transitionend',
	'-Webkit-Transition': 'webkitTransitionEnd'
};
var transEndEventNames = {
	'WebkitTransition'	: 'webkitTransitionEnd', 
	'MozTransition'		: 'transitionend',       
	'transition'		: 'transitionend'        
};

var SUPPORTS_TOUCH = 'ontouchstart' in window;
var transition;
var transitionEnd;

// Get outerHTML
(function($){
	$.fn.outerHTML = function(options){
//		this.each(function(index){
			return jQuery('<div></div>').append(jQuery(this).clone()).html();
//		});
	};
})(jQuery);

// disableTextSelect
(function($){
	$.fn.disableTextSelect = function(options) {
		return this.each(function() {
			$(this).css({
				'MozUserSelect':'none',
				'webkitUserSelect':'none'
			}).attr('unselectable','on').bind('selectstart', function() {
				return false;
			});
		});
	};
})(jQuery);

// enableTextSelect
(function($){
	$.fn.enableTextSelect = function() {
		return this.each(function() {
			$(this).css({
				'MozUserSelect':'',
				'webkitUserSelect':''
			}).attr('unselectable','off').unbind('selectstart');
		});
	};
})(jQuery);

useWebkit = false;
jQuery(document).ready(function(e){
// BEGIN CODE
	TaogiContainerStyle = jQuery('#taogi-net-site-main-container')[0].style;
	if( typeof( Modernizr ) !== 'undefined' ) {
		useWebkit = true;
		transition = Modernizr.prefixedCSS('transition');
		transitionEnd = transEndEventNames[Modernizr.prefixed('transition')];
	} else {
		if(SUPPORTS_TOUCH || _getVendorPropertyName('transition')) {
			useWebkit = true;
			transition = _getVendorPropertyName('transition');
			console.log(transition);
			transitionEnd = TransitionEndeventNames[transition] || null;
			if(transitionEnd) transitionEnd = transitionEnd+'.taogi';
		}
	}

	jQuery.fn.taogiSlideDown = function(duration,easing,callback) {
		return this.each(function() {
			var $el = jQuery(this);

			var zIndex = parseInt($el.css('z-index'));
			$el.css('z-index',(zIndex+1));

			$el.css('max-height', "none");

			var height = $el.outerHeight();
			var h = jQuery(document).height() - jQuery(this).offset().top;
			if(h < height) height = h;
			$el.css({'max-height': 0, 'overflow': 'hidden'});

			if(useWebkit) {
				if($el.css('display') == 'none') $el.css('display','block');
				setTimeout(function() {
					$el.css({transition: 'max-height '+duration+'ms '+easing, 'max-height':height+'px'});
					$el.bind(transitionEnd,function() {
						jQuery(this).css({ 'overflow':'auto', 'z-index':zIndex });
						if(typeof callback === 'function') {
							callback();
						}
						jQuery(this).unbind(transitionEnd);
					});
				},1);
			} else {
				$el.css({ 'max-height': height+'px' });
				$el.slideDown(duration,function() {
					jQuery(this).css({ 'overflow':'auto', 'z-index':zIndex });
					if(typeof callback === 'function') {
						callback();
					}
				});
			}
		});
	};

	jQuery.fn.taogiSlideUp = function(duration,easing,callback) {
		return this.each(function() {
			var $el = jQuery(this);
			if(useWebkit) {
				if(parseInt($el.css('max-height')) != 0) {
					$el.css({transition: 'max-height '+duration+'ms '+easing, 'max-height':'0px', 'overflow':'hidden'});
					$el.bind(transitionEnd,function() {
						if(typeof callback === 'function') {
							callback();
						}
						jQuery(this).unbind(transitionEnd);
					});
				}
			} else {
				if(!$el.is(':hidden')) {
					$el.css('overflow','hidden');
					$el.slideUp(duration,function() {
						if(typeof callback === 'function') {
							callback();
						}
					});
				}
			}
		});
	};

// END CODE
});
