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

function jfe_load_css(css_url) {
	if(css_url) {
		$("head").append("<link>");
		css = $("head").children(":last");
		css.attr({
			rel: "stylesheet",
			type: "text/css",
			href: css_url
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

function jfe_popup_close() {
	jQuery.fancybox.close();
}

function jfe_fancybox_open(url,callback) {
	if(typeof(fancybox) === 'undefined') {
		$("head").append("<link>");
		css = $("head").children(":last");
        css.attr({
			rel: "stylesheet",
			type: "text/css",
			href: base_uri+"resources/js/fancyBox/source/jquery.fancybox.css"
		});
		jQuery.getScript(base_uri+"resources/js/fancyBox/source/jquery.fancybox.pack.js",function() {
			jfe_fancybox_load(url,callback);
		});
	} else {
		jfe_fancybox_load(url,callback);
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
	'transition':      'transitionEnd',
	'-Moz-Transition':  'transitionend',
	'-O-Transition':      'oTransitionEnd',
	'-Webkit-Transition': 'webkitTransitionEnd',
	'-ms-Transition':    'msTransitionEnd'
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

jQuery(document).ready(function(e){
// BEGIN CODE
	TaogiContainerStyle = jQuery('#taogi-net-site-main-container')[0].style;
	useWebkit = false;
	if(SUPPORTS_TOUCH || _getVendorPropertyName('transition')) {
		useWebkit = true;
		transition = _getVendorPropertyName('transition');
		transitionEnd = TransitionEndeventNames[transition] || null;
		if(transitionEnd) transitionEnd = transitionEnd+'.taogi';
	}


	jQuery.fn.taogiSlideDown = function(duration,easing) {
		return this.each(function() {
			var zIndex = parseInt(jQuery(this).css('z-index'));
			jQuery(this).css('z-index',(zIndex+1));
			var h = jQuery(document).height() - jQuery(this).offset().top;
			if(useWebkit) {
				if(jQuery(this).css('display') == 'none') jQuery(this).css('display','block');
				jQuery(this).css({transition: 'max-height '+duration+'ms '+easing, 'max-height':h+'px'});
				jQuery(this).bind(transitionEnd,function() {
					jQuery(this).css({ 'overflow':'auto', 'z-index':zIndex });
					jQuery(this).unbind(transitionEnd);
				});
			} else {
				jQuery(this).css({ 'max-height': h+'px' });
				jQuery(this).slideDown(duration,function() {
					jQuery(this).css({ 'overflow':'auto', 'z-index':zIndex });
				});
			}
		});
	};

	jQuery.fn.taogiSlideUp = function(duration,easing) {
		return this.each(function() {
			if(useWebkit) {
				if(parseInt(jQuery(this).css('max-height')) != 0) {
					jQuery(this).css({transition: 'max-height '+duration+'ms '+easing, 'max-height':'0px', 'overflow':'hidden'});
					jQuery(this).bind(transitionEnd,function() {
						jQuery(this).unbind(transitionEnd);
					});
				}
			} else {
				if(!jQuery(this).is(':hidden')) {
					jQuery(this).css('overflow','hidden');
					jQuery(this).slideUp(duration);
				}
			}
		});
	};

// END CODE
});
