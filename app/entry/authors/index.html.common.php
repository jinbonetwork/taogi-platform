<?php
	echo '<h2 class="ui-block">&lsquo;'.$entry['subject'].'&rsquo; 그룹 관리</h2>'.PHP_EOL;
	echo '<form id="entryAuthors" action="'.$entry['permalink'].'/authors/" method="post">'.PHP_EOL;
		echo '<input id="userActionKey" type="hidden" name="" value="">'.PHP_EOL;
		echo '<div class="author-tabs ui-tabs-container">'.PHP_EOL;
			echo '<ul class="ui-tabs">'.PHP_EOL;
				echo '<li class="table ui-tab"><a href="javascript://">표 형식으로 보기</a></li>'.PHP_EOL;
				echo '<li class="gallery ui-tab"><a href="javascript://">갤러리 형식으로 보기</a></li>'.PHP_EOL;
			echo '</ul>'.PHP_EOL;
			echo '<ul class="ui-tab-contents">'.PHP_EOL;
				echo '<li class="table ui-tab-content">'.getEntryAuthorTable($entry,$authors).'</li>'.PHP_EOL;
				echo '<li class="gallery ui-tab-content">'.getEntryAuthorGallery($entry,$authors).'</li>'.PHP_EOL;
			echo '</ul>'.PHP_EOL;
		echo '</div><!--/.author-tabs-->'.PHP_EOL;
		echo getEntryAuthorsControls($entry);
	echo '</form>'.PHP_EOL;
	//$authorSearchForm->printForm();
?>
