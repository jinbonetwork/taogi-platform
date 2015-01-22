<?php
function getJsonPath($eid) {
	if(!$eid) return null;
	return JFE_DATA_PATH."/json/".(int)($eid % 16)."/".$eid.".json";
}
?>
