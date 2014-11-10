<?php
$permalink = $_SERVER['REQUEST_URI'];
global $lang;
$lang = $taogi_language;
require_once TAOGI_SOURCE_PATH."/model/".$model."/skin/".$skinname."/timeline.php";
if($model == 'touchcarousel')
	require_once JFE_PATH."/include/gnb/index.html.php";
?>
