//
function addAlertMessage(obj,message) {
	var p = obj.parent();
	if(p.find('span.alert').length > 0) {
		p.find('span.alert').text(message);
	} else {
		p.addClass('alert').append('<span class="alert">'+message+'</span>');
	}
	obj.focus();
	return false;
}

function removeAlertMessage(obj) {
	p.parent().removeClass('alert').find('span.alert').remove();
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
	jQuery.ajax({
		type: 'POST',
		url: url,
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
			jQuery(TheForm).append('<div class="alert">'+errors+'</div>');
		}
	});
}

jQuery(document).ready(function(e){
	if(jQuery('#content').length > 0) {
		/*
		var plugins = [
			"link image lists charmap hr pagebreak",
			"searchreplace insertdatetime media",
			"table contextmenu paste textcolor filemanager"
		];
		Load_Wysiwyg_Editor('content',null,plugins);
		*/
		Load_Wysiwyg_Editor('content');
	}

	jQuery('form#invite-form input#email').keyup(function(e) {
		tinyMCE.activeEditor.dom.setHTML('invitation_email',jQuery(this).val());
		tinyMCE.activeEditor.dom.setHTML('invitation_email2',jQuery(this).val());
	});
});
