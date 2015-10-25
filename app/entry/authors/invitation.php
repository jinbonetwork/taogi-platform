<?php
importLibrary('auth');

class authors_invitation extends Interface_Entry {
	public function index() {
		$context = Model_Context::instance();
		$this->entry = Entry::getEntryProfile($this->params['taogiid']);
		$this->owner = User::getUserProfile($this->params['userid']);

		if(!$this->params['email_id']) {
			Error("본인의 E-Mail 주소를 입력하세요",400);
		}
		if(!$this->params['authtoken']) {
			Error("인증토큰을 입력하세요",400);
		}

		$invite = Entry_Invite::getbyEmail($this->params['taogiid'],$this->params['email_id']);
		if( !$invite ) {
			Error("초대된 이메일 주소가 아닙니다.",400);
		}
		if( $invite['authtoken'] != $this->params['authtoken']) {
			Error("인증토큰이 일치하지 않습니다.",400);
		}

		$this->base_uri = base_uri();
		$this->baselink = Entry::getEntryLink($this->entry);

		/*
		 * check if email has previousely membership of socialfunch or taogi
		 */
		$author = User::getUserByEmail($this->params['email_id']);
		if($author) {
			$author = User::getUserProfile($author['uid']);
			$priv = User_DBM::getPrivilege($author['uid'],$this->params['taogiid']);
			/*
			 * check if email has editor privilege at this entry
			 * then rediret to author page of this entry. if not loggined, login at first.
			 */
			if($priv['uid']) {
				if(doesHaveMembership()) {
					RedirectURL($this->baselink."/authors/");
				} else {
					requireMembership( array( 'requestURI' => $this->baselink."/authors/" ) );
				}
			} else if($author['taoginame']) {
				/*
				 * if email has not editor privilege at this entry. check this email has membership of taogi
				 * then upgrade my privilege to editor of this entry. and redirect to author page of this entry.
				 * if no loggined, login at first.
				 */
				if(doesHaveMembership()) {
					User_DBM::addPrivilege($author['uid'],$this->params['taogiid'],BITWISE_EDITOR);
					Entry_Invite::deletebyUid( $this->params['taogiid'], $author['uid'] );
					RedirectURL($this->baselink."/authors/");
				} else {
					requireMembership( array( 'requestURI' => $this->baselink."/authors/", 'email_id' => $this->params['email_id'], 'authtoken' => $this->params['authtoken'] ) );
				}
			}
		}

		$this->regist = 1;
		if( !empty($this->params['name']) &&
			!empty($this->params['taoginame']) &&
			!empty($this->params['password']) &&
			!empty($this->params['password_confirm']) ) {
			if($this->params['password'] != $this->params['password_confirm']) {
				RespondJson::ResultPage(array(-2, "비밀번호가 서로 일치하지 않습니다."));
				$this->regist = 0;
			}
			if(!$this->params['taoginame'])
				RespondJson::ResultPage(array(-3, "따오기 고유주소를 입력해주세요."));
			$_user = User::getUserByNickname($this->params['taoginame']);
			if($_user['uid']) {
				RespondJson::ResultPage(array(-3, "이미 다른 분이 사용 중인 따오기 고유주소입니다. 다른 주소를 입력해주세요."));
				$this->regist = 0;
			}
			if($this->regist) {
				/* regist user and localuser table and regist previlege to this entry */

				if(!$author) {
					$regist_params = $this->params;
					$regist_params['authtoken'] = '';
					if(!($reg_uid = User::Regist($regist_params)))
						RespondJson::ResultPage(array(1,"회원 가입 도중 장애가 발생했습니다."));
				} else {
					if(!$this->params['authwith_socialfunch']) {
						RespondJson::ResultPage(array(-4,'<p>회원님은 이미 소셜펀치에 가입되어 있으십니다. 위 소셜펀치 로그인 버튼으로 소셜펀치 아이디로 로그인해주세요.</p><div class="btn-buttons"><a class="login button" href="'.url("login",array('ssl'=>true,'query'=>array('requestURI'=>$_SERVER['REQUEST_URI'],'email_id'=>$this->params['email_id'],'authtoken'=>$this->params['authtoken']))).'">로그인</a></div>'));
					}
					$reg_uid = $author['uid'];
				}

				$this->params['uid'] = $reg_uid;
				$this->params['degree'] = BITWISE_USER;
				if(!User::RegistLocalUser($this->params))
					RespondJson::ResultPage(array(1,"회원 가입 도중 장애가 발생했습니다."));

				/* add privilege table */
				User_DBM::addPrivilege($reg_uid,$this->params['taogiid'],BITWISE_EDITOR);
				/* delete from invite table */
				Entry_Invite::deletebyEmail( $this->params['taogiid'], $this->params['email_id'] );

				/* auto login */
				$isLogin = Login($this->params['email_id'],$this->params['password'],'');
				switch($isLogin) {
					case 1:
						RespondJson::ResultPage(array($isLogin,"아직 첫 로그인을 위한 링크 연결이 완료되지 않았습니다."));
						break;
					case 0:
						RespondJson::ResultPage(array(0,$this->baselink."/authors/"));
						break;
					case -1:
						RespondJson::ResultPage(array($isLogin,"존재하지 않는 E-Mail 아이디입니다."));
						break;
					case -2:
						RespondJson::ResultPage(array($isLogin,"비밀번호가 일치하지 않습니다."));
						break;
				}
			}
		}

		$this->title = JFE_NAME." 편집인 초대 승인하기";

		importResource('taogi-app-invitation');
		$this->password_necessary = "necessary";

		$this->password_form = Component::get('user/forms/password',array('password_necessary'=>'necessary'));
		$this->name_form = Component::get('user/forms/name',array('user'=>null));
		$this->profile_form = Component::get('user/forms/profile',array('user'=>null));
	}
}
