<?php
ob_start();
include_once(JFE_RESOURCE_PATH."/html/invitation.html.php");
$fixed = ob_get_contents();
ob_end_clean();

ob_start();
include_once(JFE_RESOURCE_PATH."/html/invitation.example.php");
$content = ob_get_contents();
ob_end_clean();
?>
<div id="invite-form-wrap" class="collapsed">
	<h3 class="ui-block subtitle">공동 편집인 그룹에 초대하기</h3>
	<div class="invite-form-content">
		<form id="invite-form" class="invite-form" action="<?php print $entry['permalink']; ?>/authors/invite/" onsubmit="return check_invite(this);">
			<input type="hidden" name="act" value="invite" />
			<fieldset id="invitation-address" class="fields ui-form">
				<label for="email" class="email">이메일</label>
				<input type="email" id="email" name="email" class="ui-text" placeholder="your@email.com" />
			</fieldset>
			<fieldset id="invitation-name" class="fields ui-form">
				<label for="name" class="name">닉네임</label>
				<input type="text" id="name" name="name" class="ui-text" placeholder="닉네임" />
			</fieldset>
			<fieldset id="invitation-title" class="fields ui-form">
				<label for="subject" class="subject">제 목</label>
				<input type="text" id="subject" name="subject" class="ui-text" placeholder="초대장 제목" value="<?php print $user['display_name']; ?>님이 따오기 '<?php print $entry['subject']; ?>' 타임라인에 귀하를 공동편집인으로 초대합니다." />
			</fieldset>
			<fieldset id="invitation-content" class="fields ui-form">
				<label for="content" class="content">초대장</label>
				<div class="wrap">
					<textarea id="content" name="content"><?php print $content; ?></textarea>
					<div class="main-fixed-footer">
						<?php print $fixed; ?>
					</div><!--/.main-fixed-footer-->
				</div><!--/.wrap-->
			</fieldset>
			<fieldset class="fields ui-form buttons">
				<button type="submit" class="ui-button submit">초대하기</button>
			</fieldset>
		</form>
	</div>
</div>
