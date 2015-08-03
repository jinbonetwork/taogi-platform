<div id="authors-settings" action="<?php print $entry['permalink']; ?>/authors/settings" method="post">
	<fieldset class="fields">
		<label class="field-label">편집자 정보 공개</label>
		<input type="radio" id="visible1" name="privateAuthorInfo" value="0"<?php if(!$extra['privateAuthorInfo']) print ' checked'; ?> /><label for="visible1">모두에게 공개</label>
		<input type="radio" id="visible2" name="privateAuthorInfo" value="1"<?php if($extra['privateAuthorInfo'] == 1) print ' checked'; ?> /><label for="visible2">따오기 회원에게만 공개</label>
		<input type="radio" id="visible3" name="privateAuthorInfo" value="2"<?php if($extra['privateAuthorInfo'] == 2) print ' checked'; ?> /><label for="visible3">비공개</label>
	</fieldset>
	<fieldset class="buttons">
		<button type="button">적용하기</button>
	</fieldset>
</div>
