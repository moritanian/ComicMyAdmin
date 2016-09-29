<?php
require_once( 'Controller/ControllerBase.php' );
require_once('Model/UserData.php');
class LoginController extends ControllerBase
{
	private $is_login = FALSE;
	
	public $username;

	function __get($key){
		if($key=='app_pos'){
			return $app_pos;
		}
	}

	public function __construct($url, $app_pos)
	{
		 // セッション開始
    	@session_start();
    	// ログインしていれば / に遷移
    	if (isset($_SESSION['username'])) {
        	$is_login = True;
        }

         parent::__construct($url, $app_pos);
	}

	public function isLogin(){
		return $this->is_login;
	}
	

	public function indexAction(){
		$this->execLogin();
		
		$this->view->username = $this->username;
		$this->view->token = $this->generate_token();
		$this->view->show("Login/Login", true);
	}

	//新規登録
	public function RegistrationAction(){
		$this->execRegistration();
		if(isset($this->error)){
			header("HTTP/1.0 403");
		}
		$this->view->username = $this->username;
		$this->view->error = $this->error;
		$this->view->show("Login/Registration", true);
	}

	public function LogoutAction(){
		$this->execLogout();
		$v = $this;
		$this->view->show("Login/Logout", 1);
	}

	public function SignInWithGoogleAction(){
		require_once("Utils/OAuth.php");
		OAuthIndex();
	}

	public function OAuthExecAction(){
		require_once("Utils/OAuth.php");
		$result = OAuthExec();
		if(isset($result['id']) && $result['id']){
			$userDataModel = new UserData();
			$g_id = $result['id'];
			$g_user_name = $result['name'];
			$user = $userDataModel->getByGoogleId($result['id']);
			// 新規登録の時
			if($user == null){
				// ユーザデータを登録してからアプリ用ユーザネームを登録する画面へ
				session_regenerate_id(true);
		        $_SESSION['g_id'] = $g_id;
		        $user_data = array(
		        		'user_name' => '',
		        		'g_user_id' => $g_id,
		        		'authority' => 0);

		        $userDataModel->insert($user_data);
 				$this->redirectURL("Login/SetUp", array("g_name" => $g_user_name));
			}else{
				if($user['user_name'] == ""){ // ユーザ名登録されていないときは登録画面へ
					session_regenerate_id(true);
		        	$_SESSION['g_id'] = $g_id;
					$this->redirectURL("Login/SetUp", array("g_name" => $g_user_name));
				}
				// すでに登録されているときはトップへ
				 session_regenerate_id(true);
		        // ユーザ名をセット
		        $_SESSION['username'] = $user['user_name'];
		        // ログイン完了後に / に遷移
		        $this->redirectURL("ComicAdmin", array(), true);
			}
		}
	}

	// google アカウントで新規登録(oAuth　を通った後に呼ばれる)
	public function SetUpAction(){
		// g_id が登録されてない場合は不正なアクセス
		if(!isset($_SESSION['g_id'])){
			$this->authorityErrorAction();
			exit;
		}
		$g_id =	$_SESSION['g_id'];
		$ret = $this->execSetUp($g_id);
		$this->view->gName = $this->request->g_name;
		$this->view->err = isset($ret['error']) ? $ret['error'] : "";
		$this->view->show("Login/SetUp", true);
	}	

	/**
	 * CSRFトークンの生成
	 *
	 * @return string トークン
	 */
	public function generate_token()
	{
	    // セッションIDからハッシュを生成
	    return hash('sha256', session_id());
	}

	public function genarate_hash($password){
		return password_hash($password,  PASSWORD_BCRYPT);
	}

	/**
	 * CSRFトークンの検証
	 *
	 * @param string $token
	 * @return bool 検証結果
	 */
	public function validate_token($token)
	{
	    // 送信されてきた$tokenがこちらで生成したハッシュと一致するか検証
	    return $token === $this->generate_token();
	}

	// ログイン処理
	private function execLogin(){
		// ユーザから受け取ったユーザ名とパスワード
		$this->username = filter_input(INPUT_POST, 'username');
		$password = filter_input(INPUT_POST, 'password');

		// POSTメソッドのときのみ実行
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$userDataModel = new UserData();
			$user = $userDataModel->getByUserName($this->username);
			
		    if (
		        $this->validate_token(filter_input(INPUT_POST, 'token')) &&
		        password_verify(
		            $password,
		            isset($user['hash'])
		                ? $user['hash']
		                : '$2y$10$abcdefghijklmnopqrstuv' // ユーザ名が存在しないときだけ極端に速くなるのを防ぐ
		        )
		    ) {
		        // 認証が成功したとき
		        // セッションIDの追跡を防ぐ
		        session_regenerate_id(true);
		        // ユーザ名をセット
		        $_SESSION['username'] = $this->username;
		        // ログイン完了後に / に遷移
		        header("Location: $this->app_pos/ComicAdmin/?time=" . time());
		        exit;
		    }
		    // 認証が失敗したとき
		    // 「403 Forbidden」
		    http_response_code(403);
		}
	}

	// 新規登録処理
	private function execRegistration(){
		if(!filter_input(INPUT_POST, 'submit'))return;
		$this->username = filter_input(INPUT_POST, 'username');
		$password = filter_input(INPUT_POST, 'password');
		if($this->username && mb_strlen($password) >= 6){
			$hash = $this->genarate_hash($password);
			$userDataModel = new UserData();
			$user = $userDataModel->getByUserName($this->username);
			if($user != null){
				$this->error = "同一のユーザ名が登録済みです";
				return ;
			}
			$user_data = array(
				'user_name' => $this->username,
				'hash'		=> $hash,
				'mail_address' => '',
				'authority'		=> 1,
				'notification_id'=> 0,
				'line_id' 	=> 0
			);

			$userDataModel->insert($user_data);
			header('Location:../Login/index');
			exit();
		}else{
			$this->error = "パスワードが短すぎます";
		}
	}

	public function execLogout(){
		// セッション用Cookieの破棄
		setcookie(session_name(), '', 1);
		// セッションファイルの破棄
		session_destroy();
	}

	public function ValidateUserName($name){
        $min_len = 4;
        $ret = array('success' => false,
                     'error' => ''
        );
        if(strlen($name) >= $min_len){
            $userDataModel = new UserData();
            $user = $userDataModel->getByUserName($name);
            if($user != null){
                $ret['error'] = "同一のユーザ名が登録済みです";
            }else{
                $ret['success'] = true;
            }
        }else{
            $ret['error'] = 'ユーザ名が短かすぎます。ユーザ名は' . $min_len . '以上必要です。';
        }
        return $ret;
    }

    function execSetUp($g_id){
		$ret = array();
		$user_name = $this->request->user_name;
		if($user_name) {
			$ret = $this->ValidateUserName($user_name);
			if($ret['success']){
				$userDataModel = new UserData();   
				$result = $userDataModel->updateUserNameByGoogleUserId($g_id, $user_name);
				if($result){
					$_SESSION['username'] = $user_name;
					header("Location: $this->app_pos/ComicAdmin/?time=" . time());
		        	exit;
		        }
		        $ret['error'] = "データ登録時にエラーが発生しました。";
			}else{

			}
		}
		return $ret;
	}
}
?>