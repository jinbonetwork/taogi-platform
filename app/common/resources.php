<?php
class common_resources extends Controller {
	public function index() {
		$this->contentType = 'json';

		if(is_array($this->params['resource'])) {
			foreach($this->params['resource'] as $r) {
				View_Resource::addResource($r);
			}
		} else {
			View_Resource::addResource($this->params['resource']);
		}
		$css = View_Resource::getCssList();
		$js = View_Resource::getJsList();

		$this->result = array('css'=>$css,'js'=>$js);
		header('Content-type:application/json');
		echo json_encode($this->result);
		exit;
	}
}
