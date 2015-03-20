<?php
import('library.files');
require_once JFE_CONTRIBUTE_PATH.'/SimpleImage/src/abeautifulsite/SimpleImage.php';

class common_crop extends Controller {
	public $uid;

	public function index(){
		$this->uid = Acl::getIdentity('taogi');
		$this->contentType = 'json';
		$result = array();

		if(
			$this->uid>0
			&& isset($this->params['context'])
			&& isset($this->params['origin'])
			&& isset($this->params['x'])
			&& isset($this->params['y'])
			&& isset($this->params['width'])
			&& isset($this->params['height'])
			&& isset($this->params['rotate'])
		){
			if(strpos($this->params['origin'],'http')===0){
				$domain = 'http'.(Platform::getProperty('service.ssl')==true?'s':'').'://'.Platform::getProperty('service.domain');
				$this->param['origin'] = str_replace($domain,'',$this->params['origin']);
			}
			if(!strpos($this->param['origin'],JFE_PATH)){
				$this->param['origin'] = JFE_PATH.$this->param['origin'];
			}

			$crop = (object) array(
				'context' => $this->params['context'],
				'origin' => $this->param['origin'],
				'x1' => $this->params['x'],
				'y1' => $this->params['y'],
				'x2' => $this->params['x']+$this->params['width'],
				'y2' => $this->params['y']+$this->params['height'],
				'rotate' => $this->params['rotate'],
			);

			switch($crop->context){
				case 'portrait':
					/*
					$path = explode('/',$crop->origin);
					$path[count($path)-1] = PORTRAIT_FILENAME;
					$crop->destination = implode('/',$path);
					*/
					//$path = explode('/',getUserAttachedPath($uid));
					$path = getUserAttachedPath($this->uid);

					$crop->width = PORTRAIT_WIDTH;
					$crop->height = PORTRAIT_HEIGHT;
					$crop->quality = PORTRAIT_QUALITY;
					$crop->format = PORTRAIT_FORMAT;
					$crop->destination = $path.'/files/'.PORTRAIT_FILENAME;
					$crop->thumbnail_destination = $path.'/thumbs/'.PORTRAIT_FILENAME;
				break;
				default:
					$crop->quality = THUMBNAIL_QUALITY;
					$crop->format = THUMBNAIL_FORMAT;
					$crop->destination = $crop->origin.THUMBNAIL_FILENAME;
				break;
			}

			try{
				$simpleimage = new abeautifulsite\SimpleImage($crop->origin);

				//$image->save(); // strip EXIF data

				if($crop->rotate){
					$simpleimage->rotate($crop->rotate);
				}

				$simpleimage->crop($crop->x1,$crop->y1,$crop->x2,$crop->y2);

				if($crop->width&&$crop->height){
					$simpleimage->resize($crop->width,$crop->height);
				}

				if($crop->destination!=$crop->origin){
					$simpleimage->save((string)($crop->destination),$crop->quality,$crop->format);
				}else{
					$simpleimage->save((string)($crop->origin),$crop->quality,$crop->format);
				}

				if($crop->thumbnail_destination){
					/*
					$image->resize(THUMBNAIL_WIDTH,THUMBNAIL_HEIGHT);
					$image->save($crop->thumbnail_destination);
					*/
					unlink($crop->thumbnail_destination);
				}

				$crop->cropped = str_replace(JFE_PATH,'',$crop->destination);
				$result = (array) $crop;
			}catch(Exception $e){
				$result['error'] = $e->getMessage();
			}
		}else{
			$result['error'] = 'invalid command.';
		}
		
		echo json_encode($result);
		exit;
	}
}
?>