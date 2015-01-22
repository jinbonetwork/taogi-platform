<?php
abstract class Objects {
	private static $instances = array();

	protected function __construct() {
	}

	final protected static function _instance($className) {
		if (!array_key_exists($className, self::$instances)) {
			self::$instances[$className] = new $className();
		}
		return self::$instances[$className];
	}

	abstract public static function instance();
}
