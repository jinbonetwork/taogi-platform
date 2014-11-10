	<h2>회원가입<a href="<?php if($params['output'] != 'xml') print url("login",array('ssl'=>true,'attribute'=>array('requestURI'=>$params['requestURI']))); else print "javascript://\" onclick=\"poplogin()"; ?>">이미 소셜펀치회원이세요?</a></h2>
    <form id="join_form" name="join_form" method="post" onsubmit="return check_regist(this);">
    <input type="hidden" name="requestURI" value="<?php print $params['requestURI']; ?>" />
    <input type="hidden" name="join_type" value="<?php print ($params['output'] == 'xml' ? 'pop' : ''); ?>" />
    <table class="form_table">
		<tr>
			<td><label for="email_id">이메일 주소</label></td>
			<td><input id="email_id" type="email" name="email_id" /></td>
			<td>이메일 주소는 로그인시 아이디로 사용됩니다.</td>
		</tr>
		<tr>
			<th><label for="email_id_confirm">이메일 주소<br />재입력</label></th>
			<td><input id="email_id_confirm" type="email" name="email_id_confirm" /></td>
			<td></td>
		</tr>
		<tr>
			<th><label for="name">이름</label></th>
			<td><input id="name" type="text" name="name" /></td>
			<td></td>
		</tr>
		<tr>
			<th><label for="password">비밀번호</label></th>
			<td><input id="password" type="password" name="password" /></td>
			<td></td>
		</tr>
		<tr>
			<th><label for="password_confirm">비밀번호<br />재입력</label></th> 
			<td><input id="password_confirm" type="password" name="password_confirm" /></td>
			<td>암호는 한 개 이상의 특수문자, 한 개 이상의 숫자, 한 개 이상의 영문자를 포함하여 최소 8자 이상이어야 합니다.</td>
        </tr>
	</table>
	<div class="form_button">
		<input type="button" value="취  소"  class="round_7px" onclick="<?php if($params['output'] == 'xml') print "jfe_pop_close('joinus');"; else print "history.back();"; ?>" />
		<input type="submit" value="회원가입"  class="round_7px" />
    </div>
    </form>
