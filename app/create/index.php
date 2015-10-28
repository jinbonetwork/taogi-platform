<?php
$Acl = "user";
class create_index extends Controller {
	public function index() {
		$this->title = "타임라인 만들기";
		$context = Model_Context::instance();
		require JFE_PATH."/timeline/model/touchcarousel/config/config.php";

		$this->mediaconfig = $config;
					        
		$uid = Acl::getIdentity('taogi');
		if($uid < 1) Error("타임라인을 만드시려면 먼저 회원 가입을 하셔야 합니다.");

		$this->author = $this->user;

		$_SESSION['current'] = array('mode'=>'entry_create');

		/* Local */
		View_Resource::addCssURI(JFE_URI."timeline/resources/css/media.css");
		View_Resource::addJsURI(JFE_URI."timeline/model/touchcarousel/js/jquery.taogi.touchcarousel.js");

		importResource('taogi-app-create');

		$this->editor_title = '따오기 타임라인 작성하기';
		$this->presets = PresetManager::getList('touchcarousel');
		$this->extraCss = '';
		$this->editor_header = Component::get('entry/editor/head',get_object_vars($this));
		$this->editor_basic = Component::get('entry/editor/basic',array());
		$this->editor_footer = Component::get('entry/editor/foot',array());
	}
}
?>
