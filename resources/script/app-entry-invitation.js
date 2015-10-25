function check_regist(TheForm) {

	var url = TheForm.action;
	var params = jQuery.param(jQuery(TheForm).serializeArray());
	jQuery.ajax({
		type: 'POST',
		url: url,
		data: params,
		dataType: 'json',
		beforeSend: function() {
			jfe_Block_onRequest('회원가입 처리중입니다. 잠시만 기다려주세요.');
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
			} else if(error == -4) {
				jQuery('body').statusBox({
					message: message,
					autoclose: false
				});
			} else if(error > 0) {
				jQuery('body').statusBox({
					type: 'error',
					message: message
				});
			} else {
				document.location.href = message;
			}
		},
		error: function(xhr, status, errors) {
			jfe_unBlock_afterRequest();
			jQuery('body').statusBox({
				type: 'error',
				message: errors
			});
		}
	});

	return false;
}

jQuery(function() {
	jQuery('.button.regist').click(function(e) {
		e.preventDefault();
		jQuery('#join_form #name').focus();
	});

	jQuery('#join_form').profileForm({
		submit: function() { return check_regist('#join_form'); }
	});
});
