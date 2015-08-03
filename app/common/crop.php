<?php
import('library.files');
import('contribute.SimpleImage.src.abeautifulsite.SimpleImage');

class common_crop extends Controller {
	public $uid;

	public function index(){
		$this->uid = Acl::getIdentity('taogi');
		$this->contentType = 'json';
		$crop = (object) array('log'=>array());

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
			$indexes = Image::getImageIndexes($this->params['origin']);

			$crop = (object) array_merge((array)$crop,array(
				'context' => (string) $this->params['context'],
				'origin' => (string) $indexes['original']['filepath'],
				'destination' => (string) $indexes['original']['filepath'],
				'x1' => $this->params['x'],
				'y1' => $this->params['y'],
				'x2' => $this->params['x']+$this->params['width'],
				'y2' => $this->params['y']+$this->params['height'],
				'rotate' => $this->params['rotate'],
				'width' => $indexes['small']['width'],
				'height' => $indexes['small']['height'],
				'quality' => $indexes['small']['quality'],
				'format' => $indexes['small']['format'],
				'cleanup' => true,
			));

			switch($crop->context){
				case 'portrait':
					$crop = (object) array_merge((array)$crop,$indexes['portrait'],array(
						'destination' => $indexes['original']['dirpath'].'/'.$indexes['portrait']['filename'],
					));
				break;
			}

			try{
				$simpleimage = new abeautifulsite\SimpleImage($crop->origin);
				$crop->log[] = "loaded: {$crop->origin}";

				if($crop->rotate){
					$simpleimage->rotate($crop->rotate);
					$crop->log[] = "rotated: {$crop->rotate} degree";
				}

				$simpleimage->crop($crop->x1,$crop->y1,$crop->x2,$crop->y2);
				$crop->log[] = "cropped: {$crop->x1}, {$crop->y1}, {$crop->x2}, {$crop->y2}";

				if($crop->width&&$crop->height){
					$simpleimage->resize($crop->width,$crop->height);
					$crop->log[] = "resized: {$crop->width}x{$crop->height}";
				}

				$simpleimage->save($crop->destination,$crop->quality,$crop->format);
				$crop->log[] = "saved: {$crop->destination}, {$crop->quality}, {$crop->format}";

				if($crop->cleanup){
					$t_indexes = Image::getImageIndexes($crop->destination);
					foreach($t_indexes as $t_size => $t_value){
						if(file_exists($t_indexes[$t_size]['filepath'])){
							if($t_size=='original'||$t_size=='portrait'){
								continue;
							}else{
								try{
									unlink($t_indexes[$t_size]['filepath']);
									$crop->log[] = "thumbnail deleted: {$t_indexes[$t_size]['filepath']}";
								}catch(Exception $unlink_result){
									$crop->log[] = "thumbnail deletion failed: ".$unlink_result->getMessage();
								}
							}
						}
					}

				}

				$crop->cropped = str_replace(JFE_PATH,'',$crop->destination);
				$crop->log[] = "done: {$crop->origin} => {$crop->cropped}";
			}catch(Exception $e){
				$crop->log[] = "error: ".$e->getMessage();
			}
		}else{
			$crop->log[] = "error: invalid command.";
		}
		
		$result = (array) $crop;
		header('Content-type:application/json');
		echo json_encode($result);
		exit;
	}
}
?>
