<h2 class="ui-block">&lsquo;<?php print $entry['subject']; ?>&rsquo; 편집 그룹</h2>
<div id="authors-private">
	<p>이 타임라인의 편집자 그룹 정보는 비공개 설정이 되어 있습니다.</p>
</div>
<div class="authors-go-user">
	<a href="<?php print $entry['permalink']; ?>"><span class="profile"><?php if($entry['COVERFRONT']['small_versioned']) {?><img src="<?php print $entry['COVERFRONT']['small_versioned']; ?>"><?php }?></span>타임라인 보기</a>
	<a href="<?php print $entry['owner_dashboard_link']; ?>"><span class="profile"><?php if($entry['owner_PORTRAIT']['small_versioned']) {?><img src="<?php print $entry['owner_PORTRAIT']['small_versioned']; ?>"><?php }?></span><?php print $entry['owner_DISPLAY_NAME']; ?>님 대쉬보드</a>
</div>
