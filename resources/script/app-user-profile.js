/*var $email_new_container;
var $email_new_box;
var $email_new_box_height;

function user_profile_resize() {
	$email_new_box_height = $email_new_box.actual('height');
}

function toggle_edit_email() {
	if($email_new_container.hasClass('collapsed')) {
		$email_new_container.removeClass('collapsed');
		$email_new_box.css({'height':$email_new_box_height+'px'});
		$email_new_box.find('#email_id').focus();
	} else {
		$email_new_container.addClass('collapsed');
		$email_new_box.css({'height':'0px'});
	}
} */

function check_email_id(TheForm) {
	var url = jQuery(TheForm).attr('action');
	var params = jQuery.param(jQuery(TheForm).serializeArray());

	jQuery.ajax({
		type: 'POST',
		url: url,
		data: params,
		dataType: 'json',
		beforeSend: function() {
			if( jQuery('#current-email-id').text() != jQuery('#email_id').val() ) {
				jfe_Block_onRequest('로그인 이메일 아이디 수정 요청중입니다. 이메일 수정 수정메일 발송으로 시간이 걸릴 수 있습니다. 잠시만 기다려주세요.');
			} else {
				jfe_Block_onRequest('비밀번호를 수정 중입니다. 잠시만 기다려주세요.');
			}
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
				p.displayError({
					message : message,
					shake: 1
				});
			} else if(error == 1) {
				jQuery(TheForm).append('<div class="alert">'+message+'</div>');
			} else if(error == 2) {
				jQuery('#email_id_form #email_new').html(message);
			} else if(error == 0) {
			}
		},
		error: function(xhr, status, errors) {
			jfe_unBlock_afterRequest();
			jQuery(TheForm).append('<div class="alert">'+errors+'</div>');
		}
	});

	return false;
}
function check_profile(TheForm) {
	var url = jQuery(TheForm).attr('action');
	var params = jQuery.param(jQuery(TheForm).serializeArray());

	jQuery.ajax({
		type: 'POST',
		url: url,
		data: params,
		dataType: 'json',
		beforeSend: function() {
			jfe_Block_onRequest('프로필 정보를 수정중입니다. 잠시만 기다려주세요.');
		},
		success: function(json) {
			var error = parseInt(json.error);
			var message = json.message;
			jfe_unBlock_afterRequest();
			if(error == -3) {
				var t = jQuery(TheForm).find('input[name="taoginame"]');
				t.displayError({
					message : message,
					shake: 1
				});
			} else if(error == -4) {
				var n = jQuery(TheForm).find('input[name="name"]');
				n.displayError({
					message : message,
					shake: 1
				});
			} else if(error == 1) {
				jQuery(TheForm).append('<div class="alert">'+message+'</div>');
			} else if(error == 0) {
				var old_taoginame = json.old_taoginame;
				if(old_taoginame) {
					var old_url = jQuery('#taoginame_template .protocol').text() + jQuery('#taoginame_template .domain').text() + jQuery('#taoginame_template .app').text() + old_taoginame;
					var new_url = jQuery('#taoginame_template .protocol').text() + jQuery('#taoginame_template .domain').text() + jQuery('#taoginame_template .app').text() + json.taoginame;
					if (window.history.replaceState) {
						window.history.pushState(null,'따오기 타임라인',new_url+'/profile');
					}
					jQuery('a').each(function() {
						var href = jQuery(this).attr('href');
						jQuery(this).attr('href',href.replace(old_url,new_url));
						if(href == (base_uri + old_taoginame))
							jQuery(this).attr('href',base_uri + json.taoginame);
					});
					jQuery('form').each(function() {
						var href = jQuery(this).attr('action');
						jQuery(this).attr('action',href.replace(old_url,new_url));
					});
				}
				jQuery('#taogi-gnb-footer .user .profile a span').text(json.display_name);
				jQuery('#user-profile-vcard h1.DISPLAY_NAME div.wrap').text(json.display_name);
				jQuery('#user-profile-userinfo h2.ui-block span.profile-display-name').text(json.display_name);
				jQuery('#user-profile-vcard div.summary div.wrap').html(json.summary);
			}
		},
		error: function(xhr, status, errors) {
			jfe_unBlock_afterRequest();
			jQuery(TheForm).append('<div class="alert">'+errors+'</div>');
		}
	});
    
	return false;
}

jQuery(document).ready(function(e){
/*	$email_new_container = jQuery('#email_id_form');
	$email_new_box = jQuery('#email_new');
	$email_new_box_height = 0;

	user_profile_resize();
	$email_new_box.css({'height':'0'});

	jQuery('#email_current .button.edit').on('click',function(e) {
		e.preventDefault();
		toggle_edit_email();
	});
	jQuery('#email_new .button.cancel').on('click',function(e) {
		e.preventDefault();
		toggle_edit_email();
	}); */

	Load_Wysiwyg_Editor('summary');

	jQuery('#email_id_form').profileForm({
        submit : function() { return check_email_id('#email_id_form'); }
    });
    jQuery('#userProfile').profileForm({
        submit : function() { return check_profile('#userProfile'); }
    });
});
