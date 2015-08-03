<?php
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
importResource('taogi-ui-tabs');
?>
<div class="ui-block admin-tabs ui-tabs-container">
	<ul class="ui-tabs">
<?php
	foreach($adminTabs as $adminTab => $option) {
		$currentClass = $adminTab==$current?' current':''; ?>
		<li class="admin-tab ui-tab <?php print $adminTab.$currentClass; ?>"><a href="<?php print $option['href']; ?>"><span><?php print $option['label']; ?></span></a></li>
<?php }?>
	</ul><!--/.ui-tabs-->
</div><!--/.admin-tabs-->
