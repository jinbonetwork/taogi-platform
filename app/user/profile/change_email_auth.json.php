<?php
ob_start();
?>
	<div class="wrap">
		<p><?php print $this->user['name']; ?>님 로그인 이메일 아이디를 <?php print $this->params['email_id']; ?>로 변경신청하셨습니다.</p>
		<p>변경될 <?php print $this->params['email_id']; ?>로 이메일 아이디 변경 승인 요청 메일을 발송했습니다.</p>
		<p>메일을 열어 '로그인 E-Mail 변경하기' 링크를 클릭하여 이메일 주소 변경을 승인해주세요.</p>
		<p>변경 승인이 없으면, 기존 이메일 주소(<?php print $this->user['email_id']; ?>)와 비밀번호가 유지됩니다.</p>
	</div>
<?php
$content = ob_get_contents();
ob_end_clean();
RespondJson::ResultPage(array(2,$content));
?>
