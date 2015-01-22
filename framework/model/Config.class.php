<?php
final class Model_Config extends Objects {
	public $database, $service, $session;

	public static function instance() {
		return self::_instance(__CLASS__);
	}

	protected function __construct() {
		$this->ConfigLoader();
	}

	private function ConfigLoader() {
		global $database, $service, $session;
		$this->settings = array();
		if(file_exists(JFE_PATH."/config/settings.php")) {
			@include(JFE_PATH."/config/settings.php");
			$this->userdatabase = $userdatabase;
			$this->database = $database;
			$this->service = $service;
			$this->timeline = $timeline;
			$this->session = $session;
		}
		$this->updateContext();
	}

	public function updateContext() {
		$context = Model_Context::instance();
		$configs = array('userdatabase','database','service','timeline','session');
		foreach($configs as $namespace) {
			if($namespace) {
				foreach($this->$namespace as $k => $v) {
					$context->setProperty($namespace.".".$k,$v);
				}
			}
		}
	}
}
?>
