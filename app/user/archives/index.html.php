<?php
	echo '<div id="user-archives_'.$user['uid'].'" class="user-archives">'.PHP_EOL;
		echo '<div id="user-archives-vcard" class="block vcard-container">'.PHP_EOL;
			$userProfile->printVcard();
			$userProfile->printUserTabs(array(),'archives');
		echo '</div><!--/#user-archives-vcard-->'.PHP_EOL;
		echo '<div id="user-archives-archives" class="block archives">'.PHP_EOL;
			echo '<h2>'.$userProfile->user['DISPLAY_NAME'].'님의 타임라인 목록</h2>'.PHP_EOL;
			echo '<form id="userEntries" action="'.$entryControls->getPermalink($user['uid']).'/archives/" method="post">'.PHP_EOL;
				echo '<input id="userActionKey" type="hidden" name="" value="">'.PHP_EOL;
				echo '<div class="timeline-tabs ui-tabs-container">'.PHP_EOL;
					echo '<ul class="ui-tabs">'.PHP_EOL;
						echo '<li class="gallery ui-tab"><a href="javascript://">갤러리 형식으로 보기</a></li>'.PHP_EOL;
						echo '<li class="table ui-tab"><a href="javascript://">표 형식으로 보기</a></li>'.PHP_EOL;
					echo '</ul>'.PHP_EOL;
					echo '<ul class="ui-tab-contents">'.PHP_EOL;
						echo '<li class="gallery ui-tab-content">'.$entryGallery->getGallery($entryList).'</li>'.PHP_EOL;
						echo '<li class="table ui-tab-content">'.$entryTable->getTable($entryList).'</li>'.PHP_EOL;
					echo '</ul>'.PHP_EOL;
				echo '</div><!--/.timeline-tabs-->'.PHP_EOL;
				$entryControls->printControls();
			echo '</form>'.PHP_EOL;
			//$entrySearchForm->printForm();
		echo '</div><!--/#user-archives-archives-->'.PHP_EOL;
	echo '</div><!--/#user-archives_uid-->'.PHP_EOL;
?>
