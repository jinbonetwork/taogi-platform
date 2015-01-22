<?php
ob_start();
include_once JFE_PATH."/include/gnb/index.html.php";
$content = ob_get_contents();
ob_end_clean();

RespondJson::PrintResult(array('error'=>0,'message'=>$content));
?>
