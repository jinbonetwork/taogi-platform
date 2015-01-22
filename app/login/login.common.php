<?php
$fb_user = $facebook->getUser();
?>
<div id="login">
<div class="document">
<div class="wrap">
<h2>로그인</h2>
<form id="login_form" class="ui-form" name="login" action="" method="POST" onsubmit="return check_login(this);" autocomplete="off">
	<input type="hidden" name="requestURI" value="<?php print $params['requestURI']; ?>" />
	<fieldset class="ui-form-items login social">
		<legend>SNS 계정으로 로그인</legend>
		<div class="ui-form-item facebook">
			<label class="ui-form-item-label">페이스북 계정으로 로그인</label>
			<div class="ui-form-item-control">
				<a class="button login" href="<?php print $fb_login_url; ?>"><img src="<?php print JFE_RESOURCE_URI; ?>/images/button_facebook_login.png" alt="페이스북으로 로그인 "/></a>
				<!--p class="ui-form-item-help">페이스북 계정이 있으신 분은 별도의 가입없이 페이스북 계정으로 로그인이 가능합니다.</p-->
			</div>
		</div>
	</fieldset>
	<fieldset class="ui-form-items login taogi">
		<legend>따오기/소셜펀치 계정으로 로그인</legend>
		<!--p class="block info">따오기는 소셜펀치와 통합 인증시스템을 사용합니다.</p-->
		<div class="ui-form-item email">
			<label class="ui-form-item-label" for="email_id" class="block">이메일 주소</label>
			<div class="ui-form-item-control">
				<input type="text" id="email_id" class="block input text" name="email_id" placeholder="you@example.com" />
			</div>
		</div>
		<div class="ui-form-item password">
			<label class="ui-form-item-label" for="password" class="block">비밀번호</label>
			<div class="ui-form-item-control">
				<input type="password" id="password" class="block input text password" name="password" placeholder="********" />
			</div>
		</div>
		<div class="buttons">
			<button type="submit" class="button submit"><span>로그인</span></button>
			<a class="button link action recovery" href="javascript://"><span>계정찾기</span></a>
		</div>
	</fieldset>
	<fieldset class="ui-form-items join">
		<legend>따오기 가입하기</legend>
		<div class="buttons">
			<button class="button join" href="javascript://" onclick="<?php if($params['output'] != 'nolayout') print "document.location.href='".url("regist",array('ssl'=>true,'attribute'=>array('requestURI'=>$params['requestURI'])))."'"; else print "pop_regist()" ?>;"><span>가입하기</span></button>
		</div>
	</fieldset>
</form>
</div><!--/.wrap-->
</div><!--/.document-->
</div><!--/#login-->
