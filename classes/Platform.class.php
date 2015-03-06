<?php
class Platform extends Objects {
	
	public static function instance(){
		return self::_instance(__CLASS__);
	}

	public static function getProperty($key,$default=null){
		$context = Model_Context::instance();
		$value = $context->getProperty($key,$default);
		return $value;
	}
}
?>
