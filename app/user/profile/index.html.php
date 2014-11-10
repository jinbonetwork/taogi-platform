<?php
	echo '<div id="user-profile_'.$user['uid'].'" class="user-profile">'.PHP_EOL;
		echo '<div id="user-profile-vcard" class="block vcard-container">'.PHP_EOL;
			$userProfile->printVcard();
			$userProfile->printUserTabs(array(),'profile');
		echo '</div><!--/#user-profile-vcard-->'.PHP_EOL;
		echo '<div id="user-profile-userinfo" class="block userinfo">'.PHP_EOL;
			$userProfile->printUserinfo();
			$userProfile->userForm->printForm();
			echo '<div class="buttons">'.PHP_EOL;
				echo '<a class="button edit" href="javascript://"><span>편집</span></a>'.PHP_EOL;
				echo '<a class="button save" href="javascript://"><span>저장</span></a>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
		echo '</div><!--/#user-profile-userinfo-->'.PHP_EOL;
		if($user['uid']) {
			echo '<form id="userProfile" action="'.$userProfile->getPermalink($user['uid']).'/profile/save" method="post">'.PHP_EOL;
			foreach($user as $key => $value) {
				echo '<input type="hidden" name="'.$key.'" value="'.$value.'">'.PHP_EOL;
			}
			echo '</form>'.PHP_EOL;
		}
	echo '</div><!--/#user-profile_uid-->'.PHP_EOL;
?>
