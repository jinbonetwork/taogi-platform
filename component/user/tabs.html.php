<?php
if(is_numeric($user)||!isset($user['DISPLAY_NAME'])) {
	$user = User::getUserProfile($user);
}
if(empty($user)) {
	return;
}
importResource('taogi-ui-tabs');
if(empty($userTabs)) {
	$userTabs['dashboard'] = array(
		'href' => $user['dashboard_link'],
		'label' => '대시보드',
	);
	$userTabs['archives'] = array(
		'href' => $user['archives_link'],
		'label' => '타임라인 목록',
	);
	if($_SESSION['user']['uid']==$user['uid']) {
		$userTabs['profile'] = array(
			'href' => $user['profile_link'],
			'label' => '프로필 편집',
		);
	}
}
if(!is_array($userTabs)) {
	return;
}?>
<div class="ui-block user-tabs ui-tabs-container">
	<ul class="ui-tabs">
<?php
	foreach($userTabs as $userTab => $option) {
		$currentClass = $userTab==$current?' current':''; ?>
		<li class="user-tab ui-tab <?php print $userTab.$currentClass; ?>"><a href="<?php print $option['href']; ?>"><span><?php print $option['label']; ?></span></a></li>
<?php
	}?>
	</ul><!--/.ui-tabs-->
</div><!--/.user-tabs-->
