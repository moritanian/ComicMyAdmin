<?php
require_once( 'Controller/ControllerBase.php' );
class LoginController
{
	// 事前に生成したユーザごとのパスワードハッシュの配列
	private $hashes = [
    	'morita' => '$2y$10$0k4wTlhovbQCjDDj9ExSXO8.JyHBRsZWEaqJIbYH/t4ByKZnhKfri',
	];

	private $is_login = FALSE;
	
	public $username;

	private $app_pos;

	function __get($key){
		if($key=='app_pos'){
			return $app_pos;
		}
	}

	public function __construct($url, $app_pos)
	{
		$this->app_pos= $app_pos;
		 // セッション開始
    	@session_start();
    	// ログインしていれば / に遷移
    	if (isset($_SESSION['username'])) {
        	$is_login = True;
        }
	}

	public function isLogin(){
		return $this->is_login;
	}
	

	public function indexAction(){
		$this->execLogin();
		$v = $this;
		require_once("View/Header.php");
		require_once("View/Login/Login.php");
		
	}

	//新規登録
	public function RegistrationAction(){
		$this->execRegistration();
		$v = $this;
		if(isset($this->error)){
			header("HTTP/1.0 403");
		}
		require_once("View/Header.php");
		require_once("View/Login/Registration.php");
	}

	public function LogoutAction(){
		$this->execLogout();
		$v = $this;
		require_once("View/Header.php");
		require_once("View/Login/Logout.php");
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

	/**
	 * htmlspecialcharsのラッパー関数
	 *
	 * @param string $str
	 * @return string
	 */
	public function h($str)
	{
	    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
	}

	// ログイン処理
	private function execLogin(){
		// ユーザから受け取ったユーザ名とパスワード
		$this->username = filter_input(INPUT_POST, 'username');
		$password = filter_input(INPUT_POST, 'password');

		// POSTメソッドのときのみ実行
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			/*echo($this->validate_token(filter_input(INPUT_POST, 'token')));
			echo($password);
			echo ($this->hashes[$this->username]);
			echo ( isset($this->hashes[$this->username])
		                ? $this->hashes[$this->username]
		                : '$2y$10$abcdefghijklmnopqrstuv');
		    */
		    require_once('/Model/UserData.php');
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
			require_once('Model/UserData.php');
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


}
?>