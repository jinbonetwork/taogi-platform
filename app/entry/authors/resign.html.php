<div id="resign_timeline_wrap">
	<h1>"<?php print $entry['subject']; ?>" 타임라인 편집그룹 탈퇴</h1>
	<div class="inner">
<?php if($params['act'] == 'proc') {?>
		<div class="user_info">
			<strong><?php print $myinfo['username']; ?></strong>님 이 타임라인 편즙그룹에서 탈퇴가 되었습니다.
		</div>
		<div class="button">
			<a href="<?php print $permalink; ?>">확인</a>
		</div>
<?php } else {?>
		<div class="user_info">
			<strong><?php print $myinfo['username']; ?></strong>님 이 타임라인 편즙그룹에서 탈퇴하시겠습니까?
		</div>
		<div class="button">
			<a href="<?php print $permalink; ?>/editor/resign?act=proc">탈퇴하기</a>
			<a href="javascript://" onclick="history.back();">취소</a>
		</div>
<?php } ?>
	</div>
</div>
