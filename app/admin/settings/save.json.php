<?php
RespondJson::PrintResult(array(
	'error'=>0,
	'taoginame' => $user['taoginame'],
	'display_name' => $user['display_name'],
	'portrait' => $user['portrait'],
	'summary' => $user['summary'],
	'message'=>'변경이 완료되었습니다.'
));
?>
