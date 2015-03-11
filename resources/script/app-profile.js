function check_profile(TheForm) {

	var url = TheForm.action;
	var params = "name="+n.val()+"&taoginame="+tn.val()+"&display_name="+jQuery('#display_name').val()+"&portrait="+jQuery('#portrait').val()+"&summary="+jQuery('#summary').val();
	if(p1.val() && p2.val()) {
		params += "&password="+p1.val()+"&password_confirm="+p2.val();
	}
	jQuery.ajax({
		type: 'POST',
        url: url,
        data: params,
        dataType: 'json',
		before_send: function() {
			jfe_Block_onRequest('프로필 정보를 수정중입니다. 잠시만 기다려주세요.');
		}.
		success: function(json) {
			var error = parseInt(json.error);
			var message = json.message;
			jfe_unBlock_afterRequest();
		},
		error: function(xhr, status, errors) {
			jfe_unBlock_afterRequest();
			jQuery(TheForm).append('<div class="alert">'+errors+'</div>');
		}
	});

	return false;
}

jQuery(function() {
	jQuery('#email_id_form').profileForm({
		submit : function() { return check_email_id('#email_id_form'); }
	});
	jQuery('#userProfile').profileForm({
		submit : function() { return check_profile('#userProfile'); }
	});
});

jQuery(document).ready(function(e){

});
