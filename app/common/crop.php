<?php
require_once JFE_CONTRIBUTE_PATH.'/Image/autoload.php';
use Gregwar\Image\Image;

class common_crop extends Controller {
	public $uid;

	public function index(){
		$this->uid = Acl::getIdentity('taogi');
		$this->contentType = 'json';
		$result = array();

		if(
			$this->uid>0
			&& isset($this->params['mode'])
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
				'mode' => $this->params['mode'],
				'origin' => $this->param['origin'],
				'x1' => $this->params['x'],
				'y1' => $this->params['y'],
				'x2' => $this->params['x']+$this->params['width'],
				'y2' => $this->params['y']+$this->params['height'],
				'rotate' => $this->params['rotate'],
			);

			switch($crop->mode){
				case 'portrait':
					$path = explode('/',$crop->origin);
					$path[count($path)-1] = PORTRAIT_FILENAME;
					$crop->width = PORTRAIT_WIDTH;
					$crop->height = PORTRAIT_HEIGHT;
					$crop->destination = implode('/',$path);
				break;
				case 'item':
					$crop->destination = $crop->origin.THUMBNAIL_FILENAME;
				break;
			}

			try{
				
				$image = Image::open($crop->origin);

				//$image->exif(); // strip EXIF data

				if($crop->rotate){
					$image->rotate($crop->rotate);
				}

				$image->crop($crop->x1,$crop->y1,$crop->x2,$crop->y2);

				if($crop->width&&$crop->height){
					$image->resize($crop->width,$crop->height);
				}

				if($crop->destination){
					$image->save($crop->destination);
				}else{
					$image->save($crop->origin);
				}

				$crop->cropped = str_replace(JFE_PATH,'',$crop->destination);
				$result = (array) $crop;
				//print_r($crop);
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
