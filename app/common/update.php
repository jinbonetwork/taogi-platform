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
					if(!User_DBM::updateUserField($this->uid,'portrait',$this->params['value'])){
						$result = false;
						$message = '초상화를 저장하지 못했습니다.';
					}else{
						$result = true;
						$message = 'updated';
					}
				break;
				case 'user_background':
					if(!User_DBM::updateUserExtra($this->uid,'background',$this->params['value'])){
						$result = false;
						$message = '배경그림을 저장하지 못했습니다.';
					}else{
						$result = true;
						$message = 'updated';
					}
				break;
				default:
				break;
			}
			$dbm = DBM::instance();
			$dbm->commit();
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
