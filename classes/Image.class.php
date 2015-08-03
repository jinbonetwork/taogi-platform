<?php
require_once dirname(__FILE__).'/../config/options.php';
require_once dirname(__FILE__).'/../contribute/SimpleImage/src/abeautifulsite/SimpleImage.php';
class Image extends Objects {
	private static $init = false;
	private static $root;
	private static $console;
	private static $sp_pattern;
	private static $ps_pattern;
	private static $sizes;
	private static $pathes;
	private static $index_pattern;
	
	public static function instance() {
		self::init();
		return self::_instance(__CLASS__);
	}

	public static function init($root=null,$console=true) {
		if(self::$init == true){
			return;
		}else{
			self::$init == true;
		}

		if($root){
			self::$root = $root;
		}else{
			self::$root = JFE_PATH;
		}

		if(!is_object(self::$console)&&$console){
			self::$console = Logger::instance();
			self::$console->setLogType(JFE_LOG_TYPE_FILE);
		}

		global $imageIndexes;
		self::$index_pattern = $imageIndexes;
		foreach(self::$index_pattern as $size => $value){
			self::$sp_pattern[$size] = $value['dirname'];
		}
		self::$ps_pattern = array_flip(self::$sp_pattern);
		self::$sizes = array_keys(self::$sp_pattern);
		self::$pathes = array_values(self::$sp_pattern);
	}

	public static function log($message=null){
		if(is_object(self::$console)&&$message){
			self::$console->Error($message);
		}
	}

	public static function getImagePath($given){
		self::init();
		$result = array();

		$uri = $given;
		self::log("Image::getImagePath: processing {$uri}");
		$result['given'] = $given;

		if(strpos($uri,'://')){
			list($protocol,$uri) = explode('://',$uri);
			$pathto = explode('/',$uri);
			unset($pathto[0]);
			$uri = '/'.implode('/',$pathto);
			self::log("Image::getImagePath: domain stripped. ({$uri})");
		}

		if(strpos($uri,'?')){
			list($uri,$query) = explode('?',$uri);
			self::log("Image::getImagePath: query string stripped. ({$uri})");
		}

		if(strpos($uri,self::$root)===0){
			$uri = str_replace(self::$root,'',$uri);
			self::log("Image::getImagePath: root path stripped. ({$uri})");
		}

		$uri_pattern = array(
			'//' => '/',
			'../' => '',
		);
		$uri = str_replace(array_keys($uri_pattern),array_values($uri_pattern),$uri);
		self::log("Image::getImagePath: relative uri extracted. ({$uri})");
		$result['fileuri'] = $uri;

		$pathto = explode('/',$uri);
		$result['filename'] = $pathto[count($pathto)-1];
		unset($pathto[count($pathto)-1]);
		self::log("Image::getImagePath: filename extracted. ({$result['filename']})");

		$result['size'] = self::$ps_pattern[$pathto[count($pathto)-1]];
		unset($pathto[count($pathto)-1]);
		self::log("Image::getImagePath: filesize extracted. ({$result['size']})");

		$result['baseuri'] = implode('/',$pathto);
		self::log("Image::getImagePath: baseuri extracted. ({$result['baseuri']})");

		return $result;
	}

	public static function getImageIndexes($image){
		self::init();
		self::log("Image::getImageIndexes: processing {$image}");

		$pathinfo = self::getImagePath($image);

		$indexes = self::$index_pattern;
		foreach($indexes as $index => $value){
			if($index=='portrait'){
				$filteredPathinfo = array_merge($pathinfo,$value);
			}else{
				$filteredPathinfo = $pathinfo;
			}
			$indexes[$index] = array_merge($indexes[$index],array(
				'filename' => $filteredPathinfo['filename'],
				'diruri' => "{$filteredPathinfo['baseuri']}/{$value['dirname']}",
				'fileuri' => "{$filteredPathinfo['baseuri']}/{$value['dirname']}/{$filteredPathinfo['filename']}",
				'dirpath' => str_replace('//','/',self::$root."/{$filteredPathinfo['baseuri']}/{$value['dirname']}"),
				'filepath' => str_replace('//','/',self::$root."/{$filteredPathinfo['baseuri']}/{$value['dirname']}/{$filteredPathinfo['filename']}"),
			));
			ksort($indexes[$index]);
		}
		self::log("Image::getImageIndexes: index pattern -- ".implode(', ',array_keys($indexes)));

		$result = $indexes;
		return $result;
	}

	public static function getOriginalImage($image,$indexes=null){
		self::init();
		self::log("Image::getOriginalImage: processing {$image}");

		if(!$indexes){
			$indexes = self::getImageIndexes($image);
		}

		$original = $indexes['original']['filepath'];
		self::log("Image::getOriginalImage: found path -- {$original}");

		$result = $original;
		return $result;
	}

	public static function buildImage($original,$output,$width,$height,$crop,$size=null,$indexes=null){
		self::init();
		self::log("Image::buildImage: processing {$original} => {$output} ({$width}x{$height}, {$crop})".($size?" ({$size})":''));

		if(file_exists($original)){
			if(!$size){
				$pathinfo = self::getImagePath($output);
				$size = $pathinfo['size'];
			}
			
			if(!$indexes){
				$indexes = self::getImageIndexes($output);
			}

			if(isset($indexes[$size])){
				if(!file_exists($indexes[$size]['dirpath'])){
					mkdir($indexes[$size]['dirpath'],0707,true);
					self::log("Image::buildImage: creating output directory -- {$indexes[$size]['dirpath']}");
				}

				try{
					$SimpleImage = new abeautifulsite\SimpleImage($original);
					$action = $crop?'thumbnail':'best_fit';
					$SimpleImage->$action($width,$height)->save($output);
					self::log("Image::buildImage: generating {$output} success. ({$width}x{$height})");
					$result = true;
				}catch(Exception $e){
					$error = $e->getMessage();
					self::log("Image::buildImage: generating {$output} failed. (".$error.")");
					$result = false;
				}
			}else{
				$result = false;
			}
		}else{
			self::log("Image::buildImage: original image not found -- {$original}");
			$result = false;
		}
		return $result;
	}

	public static function checkImage($image,$size=null,$original=null,$indexes=null){
		self::init();
		self::log("Image::checkImage: processing {$image}");

		if(!$size){
			$pathinfo = self::getImagePath($image);
			$size = $pathinfo['size'];
		}

		if(!$indexes){
			$indexes = self::getImageIndexes($image);
		}

		if(!$original){
			$original = self::getOriginalImage($image,$indexes);
		}

		$output = $indexes[$size]['filepath'];
		$width = $indexes[$size]['width'];
		$height = $indexes[$size]['height'];
		$crop = $indexes[$size]['crop'];

		self::log("Image::checkImage: request buildImage -- {$original}, {$output}, {$width}, {$height}, {$crop}");
		$result = self::buildImage($original,$output,$width,$height,$crop,$size,$indexes);
		return $result;
	}

	public static function buildImageset($image,$force=false){
		self::init();
		self::log("Image::buildImageset: processing {$image}, {$force}");

		$indexes = self::getImageIndexes($image);
		$initialImage = self::getOriginalImage($image,$indexes);

		if($initialImage){
			foreach($indexes as $size => $options){
				if($size=='portrait'){
					// general skip;
					continue;
				}
				if($initialImage==$options['filepath']&&!$force){
					self::log("Image::buildImageset: skipping {$options['filepath']}. (initialImage)");
					continue;
				}
				if($size=='original'&&!$force){
					self::log("Image::buildImageset: skipping {$options['filepath']}. (original)");
					continue;
				}
				if(!file_exists($options['filepath'])||$force){
					self::log("Image::buildImageset: request buildImage -- {$size}, {$options['filepath']}");
					$original = $initialImage;
					$output = $options['filepath'];
					$width = $options['width'];
					$height = $options['height'];
					$crop = $options['crop'];
					self::buildImage($original,$output,$width,$height,$crop,$size,$indexes);
				}else{
					self::log("Image::buildImageset: do not request buildImage -- {$options['filepath']}. (file exists)");
				}
			}
			self::log("Image::buildImageset: processing {$sizes['original']['filepath']} done.");
			$result = true;
		}else{
			self::log("Image::buildImageset: processing {$sizes['original']['filepath']} failed. (initialImage not found)");
			$result = false;
		}
		return $result;
	}

	public static function getImageset($image,$default=null,$tagged=true){
		self::init();
		global $dev;
		$imageset = array();
		$keys = array(
			'small',
			'medium',
			'large',
			'original',
			'small_versioned',
			'medium_versioned',
			'large_versioned',
			'original_versioned',
		);
		if($image!=$default){
			$indexes = self::getImageIndexes($image);
			foreach($keys as $key){
				list($size,$tag) = explode('_',$key);
				$imageset[$key] = $indexes[$size]['fileuri'];
				if($tagged){
					$attributes = array();
					switch($tag){
						case 'versioned':
							$attributes['v'] = $dev['timestamp'];
						break;
					}
					if(!empty($attributes)){
						$imageset[$key] .= '?'.http_build_query($attributes);
					}
				}
			}
		}else{
			foreach($keys as $key){
				$imageset[$key] = $image;
			}
		}
		return $imageset;
	}

	public static function removeImage($image,$indexes=null){
		self::init();
		self::log("Image::removeImage: processing {$image}");

		if(!file_exists($image)){
			$pathinfo = self::getImagePath($image);
			$size = $pathinfo['size'];
			$image = self::$root."{$pathinfo['fileuri']}";

			if(!file_exists($image)){
				if(!$indexes){
					$indexes = self::getImageIndexes($image);
				}
				$image = $indexes[$size]['filepath'];
			}
		}

		if(file_exists($image)){
			try{
				unlink($image);
				self::log("Image::removeImage: deleting {$image} success.");
				$result = true;
			}catch(Exception $e){
				$error = $e->getMessage();
				self::log("Image::removeImage: deleting {$image} failed. ({$error})");
				$result = false;
			}
		}else{
			self::log("Image::removeImageset: file not found -- {$image}");	
		}

		return $result;
	}

	public static function removeImageset($image,$indexes=null){
		self::init();
		self::log("Image::removeImageset: processing {$image}");
		$result = array(
			'given' => $image,
		);

		if(!$indexes){
			$indexes = self::getImageIndexes($image);
		}
		$result['indexes'] = $indexes;

		foreach($indexes as $size => $value){
			self::removeImage($indexes[$size]['filepath'],$indexes);
			$result['indexes'][$size]['status'] = self::$console->last->message;
		}

		return $result;
	}
}
?>
