function check_regist(TheForm) {

	var url = base_uri+"regist";
	var params = jQuery.param(jQuery(TheForm).serializeArray());
	jQuery.ajax({
		type: 'POST',
		url: url,
		data: params,
		dataType: 'json',
		beforeSend: function() {
			jfe_Block_onRequest('회원가입 처리중입니다. 첫로그인 승인메일 발송으로 시간이 걸릴 수 있습니다. 잠시만 기다려주세요.');
		},
		success: function(json) {
			var error = parseInt(json.error);
			var message = json.message;
			jfe_unBlock_afterRequest();
			if(error == -1) {
				var e = jQuery(TheForm).find('input[name="email_id"]');
				e.val('');
				e.displayError({
					message : message,
					shake: 1
				});
			} else if(error == -2) {
				var p = jQuery(TheForm).find('input[name="password"]');
				p.val('');
				p.displayError({
					message : message,
					shake : 1
				});
			} else if(error == -3) {
				var t = jQuery(TheForm).find('input[name="taoginame"]');
				t.displayError({
					message : message,
					shake : 1
				});
			} else if(error == 1) {
				jQuery(TheForm).append('<div class="alert">'+message+'</div>');
			} else {
				jQuery('#join').replaceWith(message);
			}
		},
		complete: function() {
			var c = jQuery('.button.close');
			var h = jQuery('.button.go-home');
			if(c.length > 0) {
				c.bind('click',function(e) {
					e.preventDefault();
					jfe_popup_close();
				});
			} else if(h.length > 0) {
				c.bind('click',function(e) {
					e.preventDefault();
					document.location.href = base_uri;
				});
			}
		},
		error: function(xhr, status, errors) {
			jfe_unBlock_afterRequest();
			jQuery(TheForm).append('<div class="alert">'+errors+'</div>');
		}
	});

	return false;
}

jQuery(function() {
	jQuery('#join_form').profileForm({
		submit: function() { return check_regist('#join_form'); }
	});
});
