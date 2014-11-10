<?php
/// Copyright (c) 2004-2010, Needlworks  / Tatter Network Foundation
/// All rights reserved. Licensed under the GPL.
/// See the GNU General Public License for more details. (/documents/LICENSE, /documents/COPYRIGHT)
class RespondJson {
	function ResultPage($errorResult) {
		if (is_array($errorResult) && count($errorResult) < 2) {
			$errorResult = array_shift($errorResult);
		}
		if (is_array($errorResult)) {
			$error = $errorResult[0];
			$errorMsg = $errorResult[1];
		} else {
			$error = $errorResult;
			$errorMsg = '';
		}
		if ($error === true)
			$error = 0;
		else if ($error === false)
			$error = 1;
		header('Content-Type: text/json; charset=utf-8');
		$repond = array('error'=>$error, 'message'=>$errorMsg);
		print json_encode($repond);
		exit;
	}
	
	function PrintResult($result, $useCDATA=true) {
		header('Content-Type: text/json; charset=utf-8');
		print json_encode($result);
		exit;
	}
	
	function NotFoundPage($isAjaxCall = false) {
		RespondJson::ResultPage(-1);
		exit;
	}
}
?>
