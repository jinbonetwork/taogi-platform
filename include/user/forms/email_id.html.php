<fieldset class="ui-form-items ui-user-form-items email">
	<div class="ui-form-item" data-field="email_id">
		<label class="ui-form-item-label" for="email_id">이메일 주소</label>
		<div class="ui-form-item-control">
			<input id="email_id" type="email" class="ui-text-form animated necessary" name="email_id" value="<?php print $user['email_id']; ?>" placeholder="you@example.com" />
			<button class="button check" type="button"><span>중복검사</span></button>
			<span class="ui-form-item-trail"><span class="success">사용가능</span><span class="failed">사용불가</span></span>
			<ul class="ui-form-item-help">
				<li>이메일 주소는 로그인시 아이디로 사용됩니다.</li>
				<li>입력한 주소로 인증메일이 발송됩니다.</li>
				<li>메일에 포함된 인증링크를 클릭해야 정상적으로 저장됩니다.</li>
			</ul>
		</div>
	</div>
	<div class="ui-form-item" data-field="email_id_confirm">
		<label class="ui-form-item-label" for="email_id_confirm">이메일주소 재입력</label>
		<div class="ui-form-item-control">
			<input id="email_id_confirm" type="email" class="ui-text-form animated necessary" name="email_id_confirm" value="<?php print $user['email_id']; ?>" placeholder="you@example.com" />
			<ul class="ui-form-item-help">
				<li>확인을 위해 한번 더 입력해주세요.</li>
			</ul>
		</div>
	</div>
</fieldset>
