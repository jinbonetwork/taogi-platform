<?php
$fb_user = $facebook->getUser();
?>
<h2>로그인</h2>
<form id="login_form" name="login" action="" method="POST" onsubmit="return check_login(this);">
	<input type="hidden" name="requestURI" value="<?php print $params['requestURI']; ?>" />
	<fieldset class="block login social">
		<legend>SNS 계정으로 로그인</legend>
		<div class="block form-item facebook">
			<a class="button login" href="<?php print $fb_login_url; ?>"><img src="<?php print JFE_RESOURCE_URI; ?>/images/button_facebook_login.png" alt="페이스북으로 로그인 "/></a>
			<!--p class="block help">페이스북 계정이 있으신 분은 별도의 가입없이 페이스북 계정으로 로그인이 가능합니다.</p-->
		</div>
	</fieldset>
	<fieldset class="block login taogi">
		<legend>따오기/소셜펀치 계정으로 로그인</legend>
		<!--p class="block info">따오기는 소셜펀치와 통합 인증시스템을 사용합니다.</p-->
		<div class="block form-item email">
			<label for="email_id" class="block">이메일 주소</label>
			<input type="text" id="email_id" class="block input text" name="email_id" placeholder="you@example.com" />
		</div>
		<div class="block form-item password">
			<label for="password" class="block">비밀번호</label>
			<input type="password" id="password" class="block input text password" name="password" />
		</div>
		<div class="block form-item buttons">
			<input type="submit" class="button submit" value="로그인" />
			<a class="action recovery" href="#">계정찾기</a>
		</div>
	</fieldset>
	<fieldset class="block join">
		<legend>따오기 가입하기</legend>
		<div class="block form-item taogi">
			<a class="button join" href="javascript://" onclick="<?php if($params['output'] != 'nolayout') print "document.location.href='".url("regist",array('ssl'=>true,'attribute'=>array('requestURI'=>$params['requestURI'])))."'"; else print "pop_regist()" ?>;">가입하기</a>
		</div>
	</fieldset>
</form>
