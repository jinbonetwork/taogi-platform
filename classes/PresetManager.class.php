<?php
@importLibrary('files');
class PresetManager extends Objects {
	public static $presets = array();
	public static function instance(){
		return self::_instance(__CLASS__);
	}

	public static function getList($model='touchcarousel',$current='') {
		$presets = _readdir(JFE_PRESET_PATH."/".$model);
		if(!empty($presets)) {
			foreach($presets as $preset) {
				if($preset['type']=='dir'){
					$directory = JFE_PRESET_URI."/".$model."/".$preset['name']."/";
					self::$presets[] = array (
						'name' => $preset['name'],
						'active' => ( ($current && $current == $preset['name']) ? ' active' : '' ),
						'checked' => ( ($current && $current == $preset['name']) ? ' checked="checked"' : '' ),
						'directory' => $directory,
						'screenshot' => $directory.'screenshot.png',
						'settings' => $directory.'settings.json',
						'stylesheet' => $directory.'style.css'
					);
				}
			}
		}
		return self::$presets;
	}
}
?>
