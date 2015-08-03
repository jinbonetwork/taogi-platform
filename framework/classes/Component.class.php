<?php
class Component extends Objects {
	public static $instances;

	public static function instance() {
		return self::_instance(__CLASS__);
	}

	public static function get($path,$args) {
		$context = Model_Context::instance();

		if(!(self::$instances[$path])) self::$instances[$path] = 0;
		$instance = self::$instances[$path];
		$real_path = JFE_PATH."/component/".$path.".html.php";
		if(is_array($args)) {
			extract($args);
		}
		if(file_exists($real_path)) {
			ob_start();
			include $real_path;
			$markup = ob_get_contents();
			ob_end_clean();
		}

		self::$instances[$path]++;

		return $markup;
	}
}
?>
