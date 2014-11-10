function check_login(TheForm) {
	var e = jQuery(TheForm).find('input[name="email_id"]');
	if(!e.val()) {
		display_error(e,'E-Mail 주소를 입력하세요');
		return false;
	} else {
		remove_error(e);
	}
	var p = jQuery(TheForm).find('input[name="password"]');
	if(!p.val()) {
		display_error(p,'비밀번호를 입력하세요');
		return false;
	} else {
		remove_error(p);
	}

	var url = base_uri+"login";
	var params = "email_id="+e.val()+"&password="+p.val()+"&requestURI="+jQuery(TheForm).find('input[name="requestURI"]').val()+"&output=xml";

	jQuery.ajax({
		type: 'POST',
		url: url,
		data: params,
		dataType: 'xml',
		beforeSend: function() {
			if(jQuery('.fancybox-inner').length > 0) {
				jfe_Block_onRequest('로그인 중입니다. 잠시만 기다려주세요.','.fancybox-inner');
			} else {
				jfe_Block_onRequest('로그인 중입니다. 잠시만 기다려주세요.');
			}
		},
		success: function(xml) {
			var error = $(xml).find('error').text();
			var message = $(xml).find('message').text();
			if(jQuery('.fancybox-inner').length > 0) {
				var element = '.fancybox-inner';
			} else {
				var element = '';
			}
			if(error == '-1') {
            	jfe_unBlock_afterRequest(element);
				var e = jQuery(TheForm).find('input[name="email_id"]');
				e.val('');
				display_error(e,message);
			} else if(error == '-2') {
            	jfe_unBlock_afterRequest(element);
				var p = jQuery(TheForm).find('input[name="password"]');
				p.val('');
				display_error(p,message);
			} else if(error == '1') {
            	jfe_unBlock_afterRequest(element);
				jQuery(TheForm).append('<div class="alert">'+message+'</div>');
			} else {
				window.location.href = message;
			}
		},
		error: function(xhr, status, errors) {
			if(jQuery('.fancybox-inner').length > 0) {
				var element = '.fancybox-inner';
			} else {
				var element = '';
			}
            jfe_unBlock_afterRequest(element);
			jQuery(TheForm).append('<div class="alert">'+errors+'</div>');
        }
	});
	return false;
}

function display_error(obj,message) {
	obj.addClass('focus');
	obj.parent().next().append('<span class="focus">'+message+'</span>');
	obj.focus();
}

function remove_error(obj) {
	obj.removeClass('focus');
	obj.parent().next().find('span.focus').remove();
}

$(function() {
	$(window).load(function() {
		$('#email_id').focus();
	});
	$('input#email_id').keyup(function(event) {
		if($(this).hasClass('focus')) {
			if($(this).val()) {
				$(this).removeClass('focus')
				$(this).parent().next().find('span.focus').remove();
			}
		}
	});

	$('input#password').keyup(function(event) {
		if($(this).hasClass('focus')) {
			if($(this).val()) {
				$(this).removeClass('focus')
				$(this).parent().next().find('span.focus').remove();
			}
		}
	});
})
