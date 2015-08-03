//
function saveAuthorSettings() {
	var f = jQuery('#authors-settings');
	var url = f.attr('action');
	var params = '';
	params = 'privateAuthorInfo='+f.find('input[name="privateAuthorInfo"]:checked').val();
	console.log(url);
	console.log(params);
	jQuery.ajax({
		type: 'POST',
		url: url,
		data: params,
		dataType: 'json',
		beforeSend: function() {
			jfe_Block_onRequest('설정을 적용하고 있습니다. 잠시만 기다려주세요.');
		},
		success: function(json) {
			var error = parseInt(json.error);
			var message = json.message;
			jfe_unBlock_afterRequest();
			if(error != 0) {
				alert(message);
			}
		},
		error: function(xhr, status, errors) {
			jfe_unBlock_afterRequest();
			alert(errors);
			return false;
		}
	});
}

function addAlertMessage(obj,message) {
	obj.displayError({
		message: message,
		shake: true
	});
	return false;
}

function removeAlertMessage(obj) {
	obj.removeError({});
}

function check_invite(TheForm) {
	if(TheForm.email.value == '') {
		return addAlertMessage(jQuery(TheForm.email),'E-Mail 주소를 입력하세요');
	} else {
		removeAlertMessage(jQuery(TheForm.email));
	}

	if(TheForm.subject.value == '') {
		return addAlertMessage(jQuery(TheForm.subject),'초대장 제목을 입력하세요');
	} else {
		removeAlertMessage(jQuery(TheForm.subject));
	}

	if(TheForm.content.value == '') {
		return addAlertMessage(jQuery(TheForm.content),'초대장 내용을 입력하세요');
	} else {
		removeAlertMessage(jQuery(TheForm.content));
	}

	var url = TheForm.action;
	var params = jQuery.param(jQuery(TheForm).serializeArray());
	jQuery.ajax({
		type: 'POST',
		url: url,
		data: params,
		dataType: 'json',
		beforeSend: function() {
			jfe_Block_onRequest('초대장을 발송 중입니다. 잠시만 기다려주세요.');
		},
		success: function(json) {
			var error = parseInt(json.error);
			var message = json.message;
			jfe_unBlock_afterRequest();
			switch(error) {
				case 0:
					jQuery('#entryInviteTable').replaceWith(message);
					jQuery('#invite-form-wrap #email').val('');
					jQuery('#invite-form-wrap #subject').val(jQuery('#invite-form-wrap #subject').attr('origin-data'));
					tinyMCE.activeEditor.dom.setHTML('invitation_email','');
					tinyMCE.activeEditor.dom.setHTML('invitation_email2','');
					jQuery('body').statusBox({
						message: '초대장 발송이 완료되었습니다.',
						type: 'success'
					});
					break;
				case 1:
					return addAlertMessage(jQuery('input#email'),message);
					break;
				case 2:
					return addAlertMessage(jQuery('input#subject'),message);
					break;
				case 3:
					return addAlertMessage(jQuery('text#content'),message);
					break;
				default:
					break;
			}
		},
		error: function(xhr, status, errors) {
			jfe_unBlock_afterRequest();
			jQuery('body').statusBox({
				message : errors,
				type: 'error'
			});
			return false;
		}
	});

	return false;
}

jQuery(document).ready(function(e){
	if(jQuery('#authors-settings').length > 0) {
		jQuery('#authors-settings button').click(function(e) {
			saveAuthorSettings();
		});
	}

	if(jQuery('#content').length > 0) {
		jQuery('#invite-form-wrap #subject').attr('origin-data',jQuery('#invite-form-wrap #subject').val());
		Load_Wysiwyg_Editor('content');
		jQuery('#invite-form-wrap .subtitle').click(function(e) {
			if(jQuery('#invite-form-wrap').hasClass('collapsed')) {
				var m_h = jQuery('#invite-form').actual('outerHeight');
				jQuery('#invite-form-wrap .invite-form-content').css('max-height',m_h+'px');
				jQuery('#invite-form-wrap').removeClass('collapsed');
			} else {
				jQuery('#invite-form-wrap .invite-form-content').css('max-height','0px');
				jQuery('#invite-form-wrap').addClass('collapsed');
			}
		});
	}

	jQuery('form#invite-form input#email').keyup(function(e) {
		tinyMCE.activeEditor.dom.setHTML('invitation_email',jQuery(this).val());
		tinyMCE.activeEditor.dom.setHTML('invitation_email2',jQuery(this).val());
	});
});
