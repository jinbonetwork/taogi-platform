<?php
ob_start();
?>
<div id="join">
	<div class="wrap">
		<h2>회원가입 신청 완료</h2>
		<div class="ui-form-info">
			<ul>
				<li>정상적으로 회원가입 신청이 완료되었습니다.</li>
				<li>가인신청시 입력하신 이메일 주소로 로그인할 수 있는 링크를 보냈습니다.</li>
				<li>이메일을 확인하시고 이메일 본문에서 첫 로그인을 위한 링크를 클릭하세요.</li>
				<li>첫 로그인을 위한 링크를 클릭하지 않으면 로그인할 수 없습니다.</li>
			</ul>
		</div>
		<form class="ui-form ui-user-form">
			<div class="buttons">
<?php		if($this->params['join_type'] == 'pop') {?>
				<button class="button cancel close">닫기</button>
<?php		} else {?>
				<button class="button join go-home">홈으로</button>
<?php		}?>
			</div>
		</form>
	</div>
</div>
<?php
$content = ob_get_contents();
ob_end_clean();
RespondJson::ResultPage(array(0,$content));
?>
