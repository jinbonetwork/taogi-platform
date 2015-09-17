<?php
importLibrary('mail');

$Acl = "owner";
$IV = array(
	'POST' => array(
		'email' => array('email', 'default' => null),
		'name' => array('string', 'default' => null),
		'subject' => array('string', 'default' => null),
		'content' => array('string', 'default' => null)
	)
);
class authors_invite extends Interface_Entry {
	public function index() {
		$this->contentType = 'json';

		$context = Model_Context::instance();
		$this->user = User::getUserProfile(Acl::getIdentity('taogi'));
		$this->entry = Entry::getEntryProfile($this->params['taogiid']);

		switch($this->params['act']) {
			case 'invite':
				$this->regist();
				$this->lists();
				break;
			case 'delete':
				$this->delete();
				$this->lists();
				break;
			case 'list':
			default:
				$this->lists();
				break;
		}
		$this->result = array('error'=>0,'message'=>$this->inviteTable);
		header('Content-type:application/json; charset=utf-8');
		echo json_encode($this->result);
		exit;
	}

	public function lists() {
		$this->inviters = Entry_Invite::getList($this->entry['eid']);
		$this->inviteTable = Component::get("entry/invite/table",array('entry'=>$this->entry,'inviters'=>$this->inviters));
	}

	public function regist() {
		$context = Model_Context::instance();
		$this->user = User::getUserProfile(Acl::getIdentity('taogi'));
		$this->entry = Entry::getEntryProfile($this->params['taogiid']);
		if(!$this->params['email']) {
			RespondJson::ResultPage(array(1,"초대할 E-Mail 주소를 입력하세요."));
		}
		$author = User::search('email_id',$this->params['email']);
		if($author) {
			$priv = User_DBM::getPrivilege($author['uid'],$this->params['taogiid']);
			if($priv['uid']) {
				RespondJson::ResultPage(array(1,"이미 공동 편집인으로 등록된 이용자의 E-Mail 주소입니다."));
			}
		} else {
			$invite = Entry_Invite::getbyEmail($this->params['taogiid'],$this->params['email']);
			if($invite['id']) {
				RespondJson::ResultPage(array(1,"이미 초대된 E-Mail 주소입니다."));
			}
			if(!$this->params['name']) {
				RespondJson::ResultPage(array(2,"초대할 편집인 이름을 입력하세요."));
			}
		}

		if(!$this->params['subject']) {
			RespondJson::ResultPage(array(3,"초대장 제목을 입력하세요."));
		}

		if(!$this->params['content']) {
			RespondJson::ResultPage(array(4,"초대장 내용을 입력하세요."));
		}

		$email = $this->params['email'];
		$authtoken = Auth::makeAuthtoken($this->params['email']);
		$content = $this->params['content'];
		$base_uri = "http://".$context->getProperty('service.domain').base_uri();
		$entry = $this->entry;
		$user = $this->user;
		ob_start();
		include_once(JFE_RESOURCE_PATH."/html/invitation.html.php");
		$content = ob_get_contents();
		ob_end_clean();

		$ret = sendAuthorInvite( ($author ? $author['email_id'] : $this->params['email']), ($author ? $author['taoginame'] : $this->params['name']), $this->params['subject'], $content );
		if($ret[0] != true) {
			RespondJson::ResultPage(array(5,"초대장을 발송하던 도중 장애가 발생했습니다. ".$ret[1]));
		}

		if($author) {
			$invite = array(
				'eid' => $this->params['taogiid'],
				'uid' => $author['uid'],
				'email_id' => $author['email_id'],
				'name' => $author['taoginame'],
				'taoginame' => $author['taoginame'],
				'display_name' => $author['display_name'],
				'degree' => $author['degree'],
				'portrait' => $author['portrait'],
				'authtoken' => $authtoken
			);
		} else {
			$invite = array(
				'eid' => $this->params['taogiid'],
				'email_id' => $this->params['email'],
				'name' => $this->params['name'],
				'authtoken' => $authtoken
			);
		}
		Entry_Invite::add($invite);

		$dbm = DBM::instance();
		$dbm->commit();
	}

	public function delete() {
		if(!$this->params['id']) {
			RespondJson::ResultPage(array(-1,"삭제할 ID를 지정하세요."));
		}
		if(!is_array($this->params['id']))
			$ids = array($this->params['id']);
		else
			$ids = $this->params['id'];
		Entry_Invite::deletes($this->params['taogiid'],$ids);

		$dbm = DBM::instance();
		$dbm->commit();
	}
}
