<?php
function getAdminTabs($current='',$adminTabs=array()) {
	if(empty($adminTabs)) {
		$adminTabs['dashboard'] = array(
			'href' => '/admin/',
			'label' => '대시보드',
		);
		$adminTabs['entries'] = array(
			'href' => '/admin/entries/',
			'label' => '타임라인 관리',
		);
		$adminTabs['users'] = array(
			'href' => '/admin/users/',
			'label' => '사용자 관리',
		);
		$adminTabs['settings'] = array(
			'href' => '/admin/settings/',
			'label' => '사이트 관리',
		);
	}
	if(!is_array($adminTabs)) {
		return;
	}
	foreach($adminTabs as $adminTab => $option) {
		$currentClass = $adminTab==$current?' current':'';
		$markup[] = '<li class="admin-tab ui-tab '.$adminTab.$currentClass.'"><a href="'.$option['href'].'"><span>'.$option['label'].'</span></a></li>'.PHP_EOL;
	}
	$markup_rows = implode('',$markup);
	$markup = <<<EOT
<div class="ui-block admin-tabs ui-tabs-container">
<ul class="ui-tabs">
$markup_rows
</ul><!--/.ui-tabs-->
</div><!--/.admin-tabs-->
EOT;
	return $markup;
}
?>
