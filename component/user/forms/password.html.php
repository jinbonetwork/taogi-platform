<fieldset class="ui-form-items ui-user-form-items password">
	<div class="ui-form-item" data-field="password">
		<label class="ui-form-item-label" for="password">비밀번호</label>
		<div class="ui-form-item-control">
			<input id="password" type="password" class="ui-text-form animated <?php print $password_necessary; ?>" name="password" placeholder="********" />
			<ul class="ui-form-item-help">
				<li>특수문자, 숫자, 영문자를 포함해야 합니다.</li>
				<li>8글자를 넘어야 합니다.</li>
			</ul>
		</div>
	</div>
	<div class="ui-form-item" data-field="password_confirm">
		<label class="ui-form-item-label" for="password_confirm">비밀번호 재입력</label>
		<div class="ui-form-item-control">
			<input id="password_confirm" type="password" class="ui-text-form animated <?php print $password_necessary; ?>" name="password_confirm" placeholder="********" />
			<ul class="ui-form-item-help">
				<li>확인을 위해 한번 더 입력해주세요.</li>
			</ul>
		</div>
	</div>
</fieldset>
