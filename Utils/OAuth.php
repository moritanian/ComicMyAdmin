<?php
// アプリケーション設定
define('CONSUMER_KEY', '845080769-hhjhrr7et12pq0n5cur3euo2q08m3ujk.apps.googleusercontent.com');
define('CONSUMER_SECRET', 'IqyiNZeFPBpFBmscGk0lnGSI');
define('CALLBACK_URL', 'http://localhost/ComicMyAdmin/Login/OAuthExec');

// URL
define('AUTH_URL', 'https://accounts.google.com/o/oauth2/auth');
define('TOKEN_URL', 'https://accounts.google.com/o/oauth2/token');
define('INFO_URL', 'https://www.googleapis.com/oauth2/v1/userinfo');


function OAuthIndex(){
	$params = array(
		'client_id' => CONSUMER_KEY,
		'redirect_uri' => CALLBACK_URL,
		'scope' => 'https://www.googleapis.com/auth/userinfo.profile',
		'response_type' => 'code',
	);

	// 認証ページにリダイレクト
	header("Location: " . AUTH_URL . '?' . http_build_query($params));
}



function OAuthExec(){


	$params = array(
		'grant_type' => 'authorization_code',
		'code' => $_GET['code'],
		'redirect_uri' => CALLBACK_URL,
		'client_id' => CONSUMER_KEY,
		'client_secret' => CONSUMER_SECRET,
	);

	// HTTPヘッダの内容(※ここがかなり重要っぽい)
	$data = http_build_query($params);
	$encoded_data = urlencode($data);

	$headers = array(
	    'Content-Type: application/x-www-form-urlencoded',
	);

	// POST送信
	$options = array('http' => array(
		'method' => 'POST',
		'content' => http_build_query($params),
		'header' => implode("\r\n", $headers),
	));
	$res1 = file_get_contents(TOKEN_URL, false, stream_context_create($options));

	// レスポンス取得
	$token = json_decode($res1, true);
	if(isset($token['error'])){
		echo 'エラー発生';
		exit;
	}

	$access_token = $token['access_token'];

	$params = array('access_token' => $access_token);

	// ユーザー情報取得
	$res = file_get_contents(INFO_URL . '?' . http_build_query($params));

	//表示
	$result = json_decode($res, true);

	var_dump($result);

	return $result;
}

?>