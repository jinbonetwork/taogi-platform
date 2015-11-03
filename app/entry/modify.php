<?php
importLibrary('sortByDate');
$Acl = "editor";
class entry_modify extends Controller {
	public function index() {
		$this->pre_title = "편집";
		$this->title = "타임라인 수정하기";
		$context = Model_Context::instance();
		require JFE_PATH."/timeline/model/touchcarousel/config/config.php";

		$this->view_mode ='edit';
		$this->mediaconfig = $config;
					        
		$uid = Acl::getIdentity('taogi');
		if($uid < 1) Error("타임라인을 수정하시려면 먼저 회원 가입을 하셔야 합니다.",403);

		if(!$this->params['taogiid']) Error("수정할 타임라인을 지정하세요");
		$this->eid = $this->params['taogiid'];

		$this->entry = Entry::getEntryInfoByID($this->eid,0);
		if($this->params['vid']) $this->entry['vid'] = $this->params['vid'];
		$this->vid = $this->entry['vid'];
		$this->author = User::getUserProfile( $this->entry['owner'] );

		$data = Entry::getEntryData($this->eid,$this->vid);
		if($this->entry['locked'] && $this->entry['locked'] != $_COOKIE[Session::getName()]) {
			if( $this->entry['modified'] > ( time() - 3600 ) )
				Error("다른 분이 편집중입니다. 잠시후 다시 해주세요.",423);
		}
		$this->timeline = json_decode($data['timeline'],true);
		$this->timeline = $this->timeline['timeline'];
		$this->extra = Entry::getEntryExtra($this->eid);
		$datalist = $this->timeline['date'];
		if($this->extra['sort'] == 'desc') {
			usort($datalist,'_sortByDateDesc');
		} else {
			usort($datalist,'_sortByDateAsc');
		}
		$this->timeline['date'] = $datalist;
		if( $this->entry['locked'] != $_COOKIE[Session::getName()] ) {
			Entry_DBM::setLock($this->eid);
			$this->revision = true;
			$dbm = DBM::instance();
			$dbm->commit();
		}
		$_SESSION['current'] = array('mode'=>'entry_edit','eid'=>$this->eid);

		/* Local */
		View_Resource::addCssURI(JFE_URI."timeline/resources/css/media.css",-1);
		View_Resource::addJsURI(JFE_URI."timeline/model/touchcarousel/js/jquery.taogi.touchcarousel.js",-1);

		importResource("taogi-app-modify");

		$this->editor_title = '따오기 타임라인 수정하기';
		$this->presets = PresetManager::getList('touchcarousel',$this->timeline['extra']['preset']);
		$this->extraCss = htmlspecialchars(_file_get_contents(getEntryExtraCssPath($this->eid)));
		$this->editor_header = Component::get('entry/editor/head',get_object_vars($this));
		$this->editor_basic = Component::get('entry/editor/basic',array());
		$this->editor_footer = Component::get('entry/editor/foot',array());
	}

	public function make_attr($item) {
		$use_proxy = str_replace(".","\\.",implode("|",$this->mediaconfig['use_proxy']));
		$attr = "";
		$attr .= 'href="'.$item['media'].'"';
		if(preg_match("/(".$use_proxy.")/i",$item['media'])) {
			$attr .= ' use_proxy="1"';
		}
		$attr .= ' credit="'.htmlspecialchars($item['credit']).'"';
		$attr .= ' caption="'.htmlspecialchars($item['caption']).'"';
		if($item['thumbnail']) {
			$attr .= ' thumbnail="'.$item['thumbnail'].'"';
			if(preg_match("/(".$use_proxy.")/i",$item['thumbnail'])) {
				$attr .= ' use_thumb_proxy="1"';
			}
		}
		return $attr;
	}
}
?>
