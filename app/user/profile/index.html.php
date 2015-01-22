	<div id="user-profile_'<?php print $user['uid']; ?>" class="app-user app-user-profile">
		<div id="user-profile-vcard" class="block vcard-container">
<?php		print getUserVcard($user);
			print getUserTabs($user,'profile');
?>
		</div><!--/#user-profile-vcard-->
		<div id="user-profile-userinfo" class="block userinfo">
			<h2 class="ui-block"><span class='profile-display-name'><?php print $user['DISPLAY_NAME']; ?></span>님의 프로필 편집</h2>
		</div><!--/#user-profile-userinfo-->
<?php	if($user['uid']) {?>
		<div class="profile_form_wrap">
			<form id="email_id_form" name="email_id_form" class="ui-form ui-user-form collapsed" action="<?php print User::getUserLink($user['uid']); ?>/profile/save" method="post" autocomplete="off">
				<input type="hidden" name="mode" value="email_id" />
				<div id="email_current" class="ui-form-block">
					<fieldset class="ui-form-items ui-user-form-items email">
						<div class="ui-form-item" data-field="email_id">
							<label class="ui-form-item-label">이메일</label>
							<div class="ui-form-item-control">
								<span id="current-email-id" class="ui-form-item-placeholder"><?php print $user['email_id']; ?></span>
								<button class="button edit toggle" type="button"><span>이메일/비밀번호 고치기</span></button>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="email_new" class="ui-form-block collapse_container">
					<div class="wrap">
<?php					include_once JFE_PATH."/include/user/forms/email_id.html.php"; ?>
<?php					include_once JFE_PATH."/include/user/forms/password.html.php"; ?>
						<div class="buttons">
							<button class="button apply" type="submit"><span>인증메일발송</span></button>
							<button class="button cancel toggle" type="button"><span>취소</span></button>
						</div>
					</div>
				</div>
			</form>
			<form id="userProfile" name="userProfile" class="ui-form ui-user-form" action="<?php print User::getUserLink($user['uid']); ?>/profile/save" method="post" autocomplete="off">
				<input type="hidden" id="c_taoginame" name="c_taoginame" value="<?php print $user['taoginame']; ?>" />
<?php			include_once JFE_PATH."/include/user/forms/name.html.php"; ?>
<?php			include_once JFE_PATH."/include/user/forms/profile.html.php"; ?>
				<div class="buttons">
					<button class="button save" type="submit"><span>저장하기</span></button>
				</div>
			</form>
		</div>
<?php	} ?>
	</div><!--/#user-profile_uid-->
