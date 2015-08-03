<?php
	echo '<div id="user-archives_'.$user['uid'].'" class="app-user app-user-archives">'.PHP_EOL;
		echo '<div id="user-archives-vcard" class="block vcard-container">'.PHP_EOL;
			echo $vcard;
			echo $tabs;
		echo '</div><!--/#user-archives-vcard-->'.PHP_EOL;
		echo '<div id="user-archives-archives" class="block archives">'.PHP_EOL;
			echo '<h2 class="ui-block">'.$user['DISPLAY_NAME'].'님의 타임라인 목록</h2>'.PHP_EOL;
			echo '<form id="userEntries" action="'.$user['archives_link'].'/" method="post">'.PHP_EOL;
				echo '<input id="userActionKey" type="hidden" name="" value="">'.PHP_EOL;
				echo '<div class="timeline-tabs ui-tabs-container">'.PHP_EOL;
					echo '<ul class="ui-tabs">'.PHP_EOL;
						echo '<li class="gallery ui-tab"><a href="javascript://">갤러리 형식으로 보기</a></li>'.PHP_EOL;
						echo '<li class="table ui-tab"><a href="javascript://">표 형식으로 보기</a></li>'.PHP_EOL;
					echo '</ul>'.PHP_EOL;
					echo '<ul class="ui-tab-contents">'.PHP_EOL;
						echo '<li class="gallery ui-tab-content">'.$entryGallery.'</li>'.PHP_EOL;
						echo '<li class="table ui-tab-content">'.$entryTable.'</li>'.PHP_EOL;
					echo '</ul>'.PHP_EOL;
				echo '</div><!--/.timeline-tabs-->'.PHP_EOL;
				echo $controls;
			echo '</form>'.PHP_EOL;
			//$entrySearchForm->printForm();
		echo '</div><!--/#user-archives-archives-->'.PHP_EOL;
	echo '</div><!--/#user-archives_uid-->'.PHP_EOL;
?>
