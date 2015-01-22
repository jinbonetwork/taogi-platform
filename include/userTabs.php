<?php
function getUserTabs($user=array(),$current='',$userTabs=array()) {
	if(is_numeric($user)||!isset($user['DISPLAY_NAME'])) {
		$user = User::getUserProfile($user);
	}
	if(empty($user)) {
		return;
	}
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
	}
	foreach($userTabs as $userTab => $option) {
		$currentClass = $userTab==$current?' current':'';
		$markup[] = '<li class="user-tab ui-tab '.$userTab.$currentClass.'"><a href="'.$option['href'].'"><span>'.$option['label'].'</span></a></li>'.PHP_EOL;
	}
	$markup_rows = implode('',$markup);
	$markup = <<<EOT
<div class="ui-block user-tabs ui-tabs-container">
<ul class="ui-tabs">
$markup_rows
</ul><!--/.ui-tabs-->
</div><!--/.user-tabs-->
EOT;
	return $markup;
}
?>
