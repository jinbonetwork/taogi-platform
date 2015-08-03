<?php
function Login($email_id, $password, $authtoken) {
	$context = Model_Context::instance();
	$result = Auth::authenticate($email_id,$password,$authtoken);
	if(!$result) {
		if(empty($_POST['save'])) {
			setcookie('JFESESSION_LOGINID', '', time() - 31536000, $context->getProperty('service.path') . '/', $context->getProperty('service.domain'));
		} else {
			setcookie('JFESESSION_LOGINID', $email_id, time() + 31536000, $context->getProperty('service.path') . '/', $context->getProperty('service.domain'));
		}
	}

	return $result;
}

function Logout() {
	Acl::clearAcl();
	session_destroy();
}

function requireLogin() {
	$context = Model_Context::instance();
	$service = $context->getProperty('service.*');
	$requestURI = ($_SERVER['HTTPS'] == 'on' ? "https://" : "http://").$service['domain'].$_SERVER['REQUEST_URI'];
	RedirectURL('login',array('ssl'=>true,'query'=>array('requestURI'=>$requestURI)));
}

function doesHaveMembership() {
	return Acl::getIdentity('taogi') !== null;
}

function requireMembership() {
	if(Acl::getIdentity('taogi') !== null) {
		return true;
	}
	requireLogin();
}
?>
