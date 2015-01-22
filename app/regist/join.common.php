<div id="join">
<div class="document">
<div class="wrap">
	<h2>회원가입</h2>
	<div class="ui-form-info">
		<ul>
			<li>따오기 TimeLine 서비스는 <a href="http://www.socialfunch.org" target="_blank">소셜펀치(SocialFunch)</a> 와 회원정보를 공유합니다.</li>
			<li>이미 소셜펀치에 가입되어 있으신 분은 바로 <a href="<?php if($params['output'] != 'nolayout') print url("login",array('ssl'=>true,'attribute'=>array('requestURI'=>$params['requestURI']))); else print "javascript://\" onclick=\"pop_login()"; ?>">로그인</a>하시면 됩니다.</li>
		</ul>
	</div>
	<form id="join_form" name="join_form" class="ui-form ui-user-form" method="post" autocomplete="off">
		<input type="hidden" name="requestURI" value="<?php print $params['requestURI']; ?>" />
		<input type="hidden" name="join_type" value="<?php print ($params['output'] == 'nolayout' ? 'pop' : ''); ?>" />
<?php		include_once JFE_PATH."/include/user/forms/email_id.html.php"; ?>
<?php		include_once JFE_PATH."/include/user/forms/profile.html.php"; ?>
<?php		include_once JFE_PATH."/include/user/forms/name.html.php"; ?>
<?php		include_once JFE_PATH."/include/user/forms/password.html.php"; ?>
		<div class="buttons">
			<button class="button join" type="submit"><span>회원가입</span></button>
			<button class="button cancel" type="button" onclick="<?php if($params['output'] == 'nolayout') print "jfe_popup_close();"; else print "history.back();"; ?>"><span>취소</span></button>
		</div>
	</form>
</div><!--/.wrap-->
</div><!--/.document-->
</div><!--/#join-->
