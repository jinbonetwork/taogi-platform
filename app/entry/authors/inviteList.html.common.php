	<h2 class="ui-block">&lsquo;<?php print $entry['subject']; ?>&rsquo; 초대자 목록</h2>
	<form id="entryAuthors" action="<?php print $entry['permalink']; ?>'/authors/invite" method="post">
		<input id="userActionKey" type="hidden" name="" value="">
		<div class="author-tabs ui-tabs-container">
			<ul class="ui-tabs">
				<li class="table ui-tab"><a href="javascript://">표 형식으로 보기</a></li>
				<li class="gallery ui-tab"><a href="javascript://">갤러리 형식으로 보기</a></li>
			</ul>
			<ul class="ui-tab-contents">
				<li class="table ui-tab-content"><?php print getEntryAuthorTable($entry,$authors); ?></li>
				<li class="gallery ui-tab-content"><?php print getEntryAuthorGallery($entry,$authors); ?></li>
			</ul>
		</div><!--/.author-tabs-->
		<?php print getEntryAuthorsControls($entry); ?>
	</form>
