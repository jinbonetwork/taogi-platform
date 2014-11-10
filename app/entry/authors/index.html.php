<?php
	echo '<div id="entry-authors_'.$entry['eid'].'" class="entry-authors">'.PHP_EOL;
		echo '<div id="entry-authors-ecard" class="block ecard">'.PHP_EOL;
			echo "<h2>{$entry['subject']}</h2>".PHP_EOL;
			echo '<div>'.$entry['summary'].'</div>'.PHP_EOL;
		echo '</div><!--/#entry-authors-ecard-->'.PHP_EOL;
		echo '<div id="entry-authors-list" class="block list">'.PHP_EOL;
			echo '<h2>'.$entry['subject'].' 그룹 관리</h2>'.PHP_EOL;
			echo '<form id="entryAuthors" action="'.$authorControls->getPermalink($entry['owner'],$entry['eid']).'/authors/" method="post">'.PHP_EOL;
				echo '<input id="userActionKey" type="hidden" name="" value="">'.PHP_EOL;
				echo '<div class="author-tabs ui-tabs-container">'.PHP_EOL;
					echo '<ul class="ui-tabs">'.PHP_EOL;
						echo '<li class="table ui-tab"><a href="javascript://">표 형식으로 보기</a></li>'.PHP_EOL;
						echo '<li class="gallery ui-tab"><a href="javascript://">갤러리 형식으로 보기</a></li>'.PHP_EOL;
					echo '</ul>'.PHP_EOL;
					echo '<ul class="ui-tab-contents">'.PHP_EOL;
						echo '<li class="table ui-tab-content">'.$authorTable->getTable($authorList,$authorTable->userHeaders,$authorTable->userOptions).'</li>'.PHP_EOL;
						echo '<li class="gallery ui-tab-content">'.$authorGallery->getGallery($authorList,$authorGallery->userHeaders,$authorGallery->userOptions).'</li>'.PHP_EOL;
					echo '</ul>'.PHP_EOL;
				echo '</div><!--/.author-tabs-->'.PHP_EOL;
				$authorControls->printControls();
				//$authorSearchForm->printForm();
			echo '</form>'.PHP_EOL;
		echo '</div><!--/#entry-authors-list-->'.PHP_EOL;
	echo '</div><!--/#entry-authors_eid-->'.PHP_EOL;
?>
