<fieldset class="ui-form-items ui-user-form-items name">
	<div class="ui-form-item" data-field="name">
		<label class="ui-form-item-label" for="name">이름</label>
		<div class="ui-form-item-control">
			<input id="name" type="text" class="ui-text-form animated necessary" name="name" value="<?php print $user['name']; ?>" placeholder="내 이름" />
			<ul class="ui-form-item-help">
				<li>반드시 입력해야 합니다.</li>
			</ul>
		</div>
	</div>
<?php if($user['uid']) {?>
	<div class="ui-form-item" data-field="display_name">
		<label class="ui-form-item-label" for="display_name">별명</label>
		<div class="ui-form-item-control">
			<input id="display_name" type="text" class="ui-text-form" name="display_name" value="<?php print $user['display_name']; ?>" placeholder="내 별명" />
			<ul class="ui-form-item-help">
				<li>입력하면 이름 대신 출력합니다.</li>
			</ul>
		</div>
	</div>
	<div class="ui-form-item ui-form-item-image float" data-field="portrait">
		<label class="ui-form-item-label">초상화</label>
		<div class="ui-form-item-control ui-form-item-control-image">
			<input id="portrait" class="ui-form-item-image-input fileupload-target" type="hidden" name="portrait" value="<?php print $user['portrait']; ?>" />
			<div id="portrait_preview" class="ui-form-item-image-preview" style="background-image:url(<?php print $user['PORTRAIT']; ?>)"></div>
			<ul id="portrait_select" class="ui-form-item-image-select">
				<li><button class="button upload" type="button"><span>업로드하기</span></button></li>
				<li><button class="button delete" type="button"><span>삭제하기</span></button></li>
			</ul>
			<div class="ui-form-hidden">
				<input type="file" id="upload_portrait" class="fileupload" for="portrait" attr-wrap="ui-form-item-control-image" name="upload_portrant" data-url="server/php/" />
			</div>
		</div>
	</div>
	<div class="ui-form-item ui-form-item-image float" data-field="background">
		<label class="ui-form-item-label">프로필 배경</label>
		<div class="ui-form-item-control ui-form-item-control-image">
			<input id="background" class="ui-form-item-image-input" type="hidden" name="background" value="<?php print $user['background']; ?>" />
			<div id="background_preview" class="ui-form-item-image-preview" src="" style="background-image:url(<?php print $user['BACKGROUND']; ?>)"></div>
			<ul id="background_select" class="ui-form-item-image-select">
				<li><button class="button upload" type="button"><span>업로드하기</span></button></li>
				<li><button class="button delete" type="button"><span>삭제하기</span></button></li>
			</ul>
		</div>
	</div>
	<div class="ui-form-item" data-field="homepage">
		<label class="ui-form-item-label" for="homepage">홈페이지</label>
		<div class="ui-form-item-control">
			<input id="homepage" type="text" class="ui-text-form" name="homepage" value="<?php print $user['homepage']; ?>" placeholder="http://homepage.com" />
		</div>
	</div>
	<div class="ui-form-item" data-field="twitter">
		<label class="ui-form-item-label" for="twitter">트위터</label>
		<div class="ui-form-item-control">
			<span class="ui-form-item-template">
				<span class="protocol">https://</span>
				<span class="domain">twitter.com/</span>
			</span>
			<input id="twitter" type="text" class="ui-text-form" name="twitter" value="<?php print $user['twitter']; ?>" placeholder="account" />
		</div>
	</div>
	<div class="ui-form-item" data-field="facebook">
		<label class="ui-form-item-label" for="facebook">페이스북</label>
		<div class="ui-form-item-control">
			<span class="ui-form-item-template">
				<span class="protocol">https://</span>
				<span class="domain">facebook.com/</span>
			</span>
			<input id="facebook" type="text" class="ui-text-form" name="facebook" value="<?php print $user['facebook']; ?>" placeholder="account" />
		</div>
	</div>
	<div class="ui-form-item" data-field="summary">
		<label class="ui-form-item-label" for="summary">자기소개</label>
		<div class="ui-form-item-control">
			<textarea id="summary" name="summary" placeholder="간단한 소갯말"><?php print $user['summary']; ?></textarea>
		</div>
	</div>
<?php }?>
</fieldset>
