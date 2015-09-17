<?php
function _sortByDateAsc($a,$b) {
	if($a['startDate'] > $b['startDate']) return 1;
	else if($a['startDate'] < $b['startDate']) return -1;
	else return 0;
}

function _sortByDateDesc($a,$b) {
	if($a['startDate'] < $b['startDate']) return 1;
	else if($a['startDate'] > $b['startDate']) return -1;
	else return 0;
}
?>
