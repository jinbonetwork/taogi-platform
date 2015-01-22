<fieldset class="ui-form-items ui-user-form-items url">
	<div class="ui-form-item" data-fields="taoginame">
		<label class="ui-form-item-label" for="taoginame">프로필 주소</label>
		<div class="ui-form-item-control">
			<span id="taoginame_template" class="ui-form-item-template">
				<span class="protocol">http://</span>
				<span class="domain"><?php print $context->getProperty('service.domain'); ?></span>
				<span class="app"><?php print base_uri(); ?></span>
			</span>
			<input id="taoginame" type="text" class="ui-text-form ui-char-form animated necessary" name="taoginame" value="<?php print $user['taoginame']; ?>" placeholder="myid" />
			<button	type="button" class="button check"><span>중복검사</span></button>
			<span class="ui-form-item-trail"><span class="success">사용가능</span><span class="failed">사용불가</span></span>
			<ul class="ui-form-item-help">
				<li>영문자, 숫자만 사용할 수 있습니다.</li>
				<li>프로필 주소를 바꾸면 기존에 작성한 모든 타임라인의 주소가 바뀌어 기존 링크로는 접속이 불가능해집니다! 신중히 결정해 주세요!</li>
			</ul>
		</div>
	</div>
</fieldset>
