function check_email_id_duplicate(id) {
	var obj = jQuery('#'+id);
	if(!obj.val()) {
		alert('중복 검사할 E-Mail 아이디를 입력하세요.');
		obj.focus();
		return false;
	}
	var url = base_uri+"regist/duplicate";
	var params = "email_id="+obj.val();

	jQuery.ajax({
		type: 'POST',
		url: url,
		data: params,
		dataType: 'xml',
		success: function(xml) {
			if($(xml).find('error').text() == 0) {
				var content = $(xml).find('response').find('message').text();
				var _offset = obj.offset();
				jQuery('#regist_duplicate').remove();
				obj.parent().append(content);
				jQuery('#regist_duplicate')
					.css({'position':'absolute', 'z-index': '2'})
					.offset({top: (_offset.top)+25, left:_offset.left});
			} else {
				var errors = $(xml).find('response').find('message').text();
				alert(errors);
			}
		},
		error: function(xhr, status, error) {
			alert(error);
		}
	});

	return false;
}

function check_email_id_confirm(id,exist) {
	jQuery('#'+id).remove();
	if(exist) {
		jQuery('#email_id').val('')
			.addClass('focus')
			.focus();
	} else {
		jQuery('#email_id').removeClass('focus');
	}
}

function check_regist(TheForm) {

	var f1 = jQuery(TheForm).find('input[name="email_id"]');
	if(!f1.val()) {
		display_error(f1,'이메일주소를 입력하세요',1);
		return false;
	} else {
		remove_error(f1);
	}
	var f2 = jQuery(TheForm).find('input[name="email_id_confirm"]');
	if(!f2.val()) {
		display_error(f2,'이메일주소 확인을 입력하세요',1);
		return false;
	} else {
		remove_error(f2);
	}
	if(f1.val() != f2.val()) {
		f1.addClass('focus');
		display_error(f2,'이메일주소가 서로 일치하지 않습니다',1);
		return false;
	} else {
		remove_error(f1);
		remove_error(f2);
	}

	var n = jQuery(TheForm).find('input[name="name"]');
	if(!n.val()) {
		display_error(n,'이름을 입력하세요',1);
		return false;
	} else {
		remove_error(n);
	}

	jQuery('.check_dup_taoginame_box').hide();
	var tn = jQuery(TheForm).find('input[name="taoginame"]');
	if(!tn.val()) {
		display_error(tn,'따오기 고유주소를 입력하세요',1);
		return false;
	} else {
		remove_error(tn);
	}

	var p1 = jQuery(TheForm).find('input[name="password"]');
	if(!p1.val()) {
		display_error(p1,'비밀번호를 입력하세요',1);
		return false;
	} else {
		if(p1.val().length < 8) {
			display_error(p1,'비밀번호는 영문 숫자 조합 8자 이상입니다.',1);
			return false;
		}
		remove_error(p1);
	}

	var p2 = jQuery(TheForm).find('input[name="password_confirm"]');
	if(!p2.val()) {
		display_error(p2,'비밀번호 확인을 입력하세요',1);
		return false;
	} else {
		remove_error(p2);
	}
	if(p1.val() != p2.val()) {
		p1.addClass('focus');
		display_error(p2,'비밀번호가 서로 일치하지 않습니다',1);
		return false;
	} else {
		remove_error(p1);
		remove_error(p2);
	}

/*
	var t = jQuery(TheForm).find('input[name="terms"]');
	if(!t.attr('checked')) {
		alert("사용자 약관에 동의하셔야 가입하실 수 있습니다.");
		t.focus();
		return false;
	}

	var pr = jQuery(TheForm).find('input[name="privacy"]');
	if(!pr.attr('checked')) {
		alert("개인정보보호정책에 동의하셔야 가입하실 수 있습니다.");
		pr.focus();
		return false;
	}

*/

	var url = base_uri+"regist";
	var params = "email_id="+f1.val()+"&email_id_confirm="+f2.val()+"&name="+n.val()+"&password="+p1.val()+"&password_confirm="+p2.val()+"&requestURI="+jQuery(TheForm).find('input[name="requestURI"]').val()+"&join_type="+jQuery(TheForm).find('input[name="join_type"]').val()+"&output=xml";
	jQuery.ajax({
		type: 'POST',
		url: url,
		data: params,
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded',
		beforeSend: function() {
			jfe_Block_onRequest('회원가입 처리중입니다. 잠시만 기다려주세요.');
		},
		success: function(json) {
			var error = json.error;
			var message = json.message;
			if(error == '-1') {
				jfe_unBlock_afterRequest();
				var e = jQuery(TheForm).find('input[name="email_id"]');
				e.val('');
				display_error(e,message,1);
			} else if(error == '-2') {
				jfe_unBlock_afterRequest();
				var p = jQuery(TheForm).find('input[name="password"]');
				p.val('');
				display_error(p,message,1);
			} else if(error == '-3') {
				jfe_unBlock_afterRequest();
				check_dup_box(message);
			} else if(error == '1') {
				jfe_unBlock_afterRequest();
				jQuery(TheForm).append('<div class="alert">'+message+'</div>');
			} else {
				jfe_unBlock_afterRequest();
				jQuery('#joinus').replaceWith(message);
			}
		},
		error: function(xhr, status, error) {
			jfe_unBlock_afterRequest();
			jQuery(TheForm).append('<div class="alert">'+error+'</div>');
		}
	});

	return false;
}

function check_dup_taoginame() {
	var taoginame = jQuery('#taoginame').val();
	var url = base_uri+'common/duplicate';
	var params = 'taoginame='+taoginame;
	jfe_Block_onRequest('중복검사 요청중입니다. 잠시만 기다려주세요.');
	jQuery.ajax({
		type: 'POST',
		url: url,
		data: params,
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded',
		beforeSend: function() {
			jfe_Block_onRequest('중복검사 요청중입니다. 잠시만 기다려주세요.');
		},
		success: function(json) {
			var error = parseInt(json.error);
			var message = json.message;
			if(error == -2 || error == -3) {
				jfe_unBlock_afterRequest();
				check_dup_box(message);
			} else if(error == 0) {
				jfe_unBlock_afterRequest();
				check_dup_box(message);
			}
		},
		error: function(xhr, status, errors) {
			jfe_unBlock_afterRequest();
			jQuery('#join_form').append('<div class="alert">'+errors+'</div>');
		}
	});
}

function display_error(obj,message,shake) {
	obj.addClass('focus');
	var fs = obj.parent();
	fs.addClass('focus');
	var e = fs.find('span.focus');
	if(e.length > 0) {
		e.html(message);
	} else {
		fs.append('<span class="focus">'+message+'</span>');
	}
	obj.focus();
	if(shake) {
		obj.addClass('shake');
		setTimeout(function() { obj.removeClass('shake'); }, 1000);
	}
}

function remove_error(obj) {
	obj.removeClass('focus');
	var fs = obj.parent();
	fs.removeClass('focus');
	fs.find('span.focus').remove();
}

function check_dup_box(message) {
	remove_error(jQuery('#taoginame'));
	jQuery('.check_dup_taoginame_box .inner').html(message);
	jQuery('.check_dup_taoginame_box').slideDown();
	var c = jQuery('.check_dup_taoginame_box .close');
	if(c.data('click.event') !== true) {
		c.bind('click.taogi', function(e) {
			jQuery('.check_dup_taoginame_box').slideUp();
		});
		c.data('click.event',true);
	}
}

jQuery(function() {
	jQuery('input#email_id').keyup(function(event) {
		if(jQuery(this).hasClass('focus')) {
			if(jQuery(this).val()) remove_error(jQuery(this));
		}
	});

	$('input#email_id_confirm').keyup(function(event) {
		var p = $('input#email_id');
		if(jQuery(this).val()) {
			if(p.val() != jQuery(this).val()) {
				display_error(jQuery(this),'이메일 주소가 일치하지 않습니다.',0);
			} else {
				remove_error(jQuery(this));
			}
		}
	});

	jQuery('input#name').keyup(function(event) {
		if(jQuery(this).hasClass('focus')) {
			if(jQuery(this).val()) remove_error(jQuery(this));
		}
	});

	jQuery('.button.check_dup').click(function(e) {
		if(!jQuery('#taoginame').val()) {
			display_error(jQuery(this),'사용하실 따오기 주소를 입력하세요');
			return false;
		}
		var tname = jQuery('#taoginame').val();
		if(!tname.match(/^[0-9a-zA-Z\.\-_]+$/g)) {
			check_dup_box('따오기 주소는 영문, 숫자 그리고 .-_ 조합으로만 사용하실 수 있습니다.');
			return false;
		}
		check_dup_taoginame();
		
		return false;
	});

	$('input#password_confirm').keyup(function(event) {
		var p = $('input#password');
		if(p.val()) {
			if(p.val() != $(this).val()) {
				display_error(jQuery(this),'비밀번호가 일치하지 않습니다.',0);
			} else {
				remove_error(jQuery(this));
			}
		}
	});
});
