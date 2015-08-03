<?php
	echo '<h2 class="ui-block">&lsquo;'.$entry['subject'].'&rsquo; 편집 그룹</h2>'.PHP_EOL;
	echo '<form id="entryAuthors" action="'.$entry['permalink'].'/authors/" method="post">'.PHP_EOL;
		echo '<input id="userActionKey" type="hidden" name="" value="">'.PHP_EOL;
		echo '<div class="author-tabs ui-tabs-container"'.(!$isOwner ? ' data-tab-default="li.gallery"' : '').'>'.PHP_EOL;
			echo '<ul class="ui-tabs">'.PHP_EOL;
				echo '<li class="table ui-tab"><a href="javascript://">표 형식으로 보기</a></li>'.PHP_EOL;
				echo '<li class="gallery ui-tab"><a href="javascript://">갤러리 형식으로 보기</a></li>'.PHP_EOL;
				if($isOwner) {
					echo '<li class="settings ui-tab"><a href="javascript://">그룹 설정</a></li>'.PHP_EOL;
				}
			echo '</ul>'.PHP_EOL;
			echo '<ul class="ui-tab-contents">'.PHP_EOL;
				echo '<li class="table ui-tab-content">'.$authorTable.'</li>'.PHP_EOL;
				echo '<li class="gallery ui-tab-content">'.$authorGallery.'</li>'.PHP_EOL;
				if($isOwner) {
					echo '<li class="settings ui-tab-content">'.$authorSettings.'</li>'.PHP_EOL;
				}
			echo '</ul>'.PHP_EOL;
		echo '</div><!--/.author-tabs-->'.PHP_EOL;
		echo $authorsControls;
	echo '</form>'.PHP_EOL;
	//$authorSearchForm->printForm();
?>
