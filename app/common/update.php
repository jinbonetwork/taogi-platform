<?php
class common_update extends Controller {
	public $uid;

	public function index(){
		$this->uid = Acl::getIdentity('taogi');
		$this->contentType = 'json';

		if($this->uid<1){
			$result = false;
			$message = 'access denied';
		}else if(!isset($this->params['value'])){
			$result = false;
			$message = 'value field is required';
		}else{
			switch($this->params['context']){
				case 'user_portrait':
				break;
				case 'user_background':
				break;
				default:
				break;
			}
			$result = true;
			$message = 'saved';
		}
		
		$result = array(
			'result' => $result,
			'message' => $message,
		);
		echo json_encode($result);
		exit;
	}
}
?>
