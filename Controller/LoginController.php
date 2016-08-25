<?php
class LoginController 
{
	// 事前に生成したユーザごとのパスワードハッシュの配列
	private $hashes = [
    	'morita' => '$2y$10$0k4wTlhovbQCjDDj9ExSXO8.JyHBRsZWEaqJIbYH/t4ByKZnhKfri',
	];

	private $is_login = FALSE;
	public $username;
	/*
	public function __construct()
	{
		 // セッション開始
    	@session_start();
    	// ログインしていれば / に遷移
    	if (isset($_SESSION['username'])) {
        	$is_login = True;
        }
	}
	*/

	public function isLogin(){
		return $this->is_login;
	}
	

	public function indexAction(){
		$this->execLogin();
		try {
			$v = $this;
			require_once("/../View/Login.php");
		} catch (Exception $e) {
			echo("エラーが発生しました。");
		}
		
	}

	//新規登録
	public function RegistrationAction(){
		$this->execRegistration();
		$v = $this;
		require_once("/../View/Registration.php");
	}

	public function LogoutAction(){
		$this->execLogout();
		$v = $this;
		require_once("/../View/Logout.php");
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
		    if (
		        $this->validate_token(filter_input(INPUT_POST, 'token')) &&
		        password_verify(
		            $password,
		            isset($this->hashes[$this->username])
		                ? $this->hashes[$this->username]
		                : '$2y$10$abcdefghijklmnopqrstuv' // ユーザ名が存在しないときだけ極端に速くなるのを防ぐ
		        )
		    ) {
		    	echo ("success");
		        // 認証が成功したとき
		        // セッションIDの追跡を防ぐ
		        session_regenerate_id(true);
		        // ユーザ名をセット
		        $_SESSION['username'] = $this->username;
		        // ログイン完了後に / に遷移
		        header('Location: ./ComicAdmin/');
		        exit;
		    }
		    // 認証が失敗したとき
		    // 「403 Forbidden」
		    http_response_code(403);
		}
	}

	// 新規登録処理
	private function execRegistration(){
		$this->username = filter_input(INPUT_POST, 'username');
		$password = filter_input(INPUT_POST, 'password');
		if(mb_strlen($password) >= 6){
			$hash = $this->genarate_hash($password);
			echo $hash;
			header('Location:../Login/');
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