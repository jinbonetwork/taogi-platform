<div id="join">
	<div class="wrap">
		<h2>회원가입</h2>
		<div class="info">
			<ul>
				<li>따오기 TimeLine 서비스는 <a href="http://www.socialfunch.org" target="_blank">소셜펀치(SocialFunch)</a> 와 회원정보를 공유합니다.</li>
				<li>이미 소셜펀치에 가입되어 있으신 분은 바로 <a href="<?php if($params['output'] != 'nolayout') print url("login",array('ssl'=>true,'attribute'=>array('requestURI'=>$params['requestURI']))); else print "javascript://\" onclick=\"pop_login()"; ?>">로그인</a>하시면 됩니다.</li>
			</ul>
		</div>
	    <form id="join_form" name="join_form" method="post" onsubmit="return check_regist(this);">
			<input type="hidden" name="requestURI" value="<?php print $params['requestURI']; ?>" />
			<input type="hidden" name="join_type" value="<?php print ($params['output'] == 'nolayout' ? 'pop' : ''); ?>" />
			<fieldset class="fields email">
				<label for="email_id">이메일 주소</label></th>
				<input id="email_id" type="email" class="text animated" name="email_id" />
				<p class="explain">이메일 주소는 로그인시 아이디로 사용됩니다.</p>
			</fieldset>
			<fieldset class="fields email">
				<label for="email_id_confirm">이메일주소 재입력</label>
				<input id="email_id_confirm" type="email" class="text animated" name="email_id_confirm" />
			</fieldset>
			<fieldset class="fields name">
				<label for="name">이름</label>
				<input id="name" type="text" class="text animated" name="name" />
			</fieldset>
			<fieldset class="fields taoginame">
				<label for="taoginame">따오기 고유주소</label>
				http://<?php print $context->getProperty('service.domain').base_uri(); ?><input id="taoginame" type="text" class="text small animated" name="taoginame" /><input type="button" class="button check_dup" value="중복검사">
				<p class="explain">영문숫자 조합입니다.</p>
				<div class="check_dup_taoginame_box">
					<div class="inner"></div>
					<span class="close">x</span>
				</div>
			</fieldset>
			<fieldset class="fields password">
				<label for="password">비밀번호</label>
				<input id="password" type="password" class="text animated" name="password" />
			</fieldset>
			<fieldset class="fields password">
				<label for="password_confirm">비밀번호 재입력</label>
				<input id="password_confirm" type="password" class="text animated" name="password_confirm" />
				<p class="explain">암호는 한 개 이상의 특수문자, 한 개 이상의 숫자, 한 개 이상의 영문자를 포함하여 최소 8자 이상이어야 합니다.</p>
			</fieldset>
			<fieldset class="buttons">
				<input type="submit" value="회원가입"  class="button" />
				<input type="button" value="취  소"  class="button" onclick="<?php if($params['output'] == 'nolayout') print "jfe_popup_close();"; else print "history.back();"; ?>" />
			</fieldset>
		</form>
	</div>
</div>
