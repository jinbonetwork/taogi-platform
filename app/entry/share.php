<?php
class entry_share extends Interface_Entry {
	public function index() {
		$context = Model_Context::instance();

		$uid = Acl::getIdentity('taogi');
	}
}
?>
