<?php

/**

 * PHPのinclude_pathを設定。

 * 本番環境、テスト環境を切り分ける。

 */

function IncludePathSetting($dispatcher){

    if ($_SERVER['SERVER_NAME']=='moritanian.s2.xrea.com' ){

        //本番環境

        $path = '/home/hoge/smarty/libs/';

        $path .= PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'].'/ComicMyAdmin' ;

        $dispatcher->setSystemRoot($_SERVER['DOCUMENT_ROOT'].'/ComicMyAdmin');

        $dispatcher->setAppPosition("/ComicMyAdmin");
    } else {

        //開発環境

        $path =  '/';
        $path = "C:\\Users\\moritania\\Desktop\\wavserver\\Apache24\\htdocs\\ComicMyAdmin";
       // $path .= PATH_SEPARATOR . 'D:/devlop/php/別にインクルードするディレクトリあれば指定/';

        //$dispatcher->setSystemRoot('C:/Users/moritania/Destop/wavserver/Apache24/ComicMyAdmin');
        $dispatcher->setSystemRoot($_SERVER['DOCUMENT_ROOT'].'/ComicMyAdmin');

        $dispatcher->setAppPosition("/ComicMyAdmin");
    }

    set_include_path(get_include_path() . PATH_SEPARATOR . $path); 

}

function getAmazonItem($asin){

    $asin = 'B013PUTPHK';

    //アクセスキー
    $access_key_id = 'AKIAITLMZ67Q23CSZN2A';
    //シークレットキー
    $secret_access_key = 'H7fm2KxLnk8lsavpEP4tr5bUwLRlQbxIVfMS8nMT';
    //アソシエイトタグ
    $associateTag = '';

    //APIエンドポイントURL
    $endpoint = 'http://ecs.amazonaws.jp/onca/xml';

    // パラメータ
    $params = array(
        //共通↓
        'Service' => 'AWSECommerceService',
        'AWSAccessKeyId' => $access_key_id,
        'AssociateTag' => $associateTag,
        //リクエストにより変更↓
        'Operation' => 'ItemLookup',
        'ItemId' => $asin,
        'ResponseGroup' => 'ItemAttributes,Images',
        //署名用タイムスタンプ
        'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
    );

    //パラメータと値のペアをバイト順？で並べかえ。
    ksort($params);

    //RFC 3986?でURLエンコード
    $string_request = str_replace(
        array('+', '%7E'),
        array('%20', '~'),
        http_build_query($params)
    );

    //URL分解
    $parse_url = parse_url($endpoint);

    //署名対象のリクエスト文字列を作成。
    $string_signature = "GET\n{$parse_url["host"]}\n{$parse_url["path"]}\n$string_request";

    //RFC2104準拠のHMAC-SHA256ハッシュ化しbase64エンコード（これがsignatureとなる）
    $signature = base64_encode(hash_hmac('sha256', $string_signature, $secret_access_key,true));

    //URL組み立て
    $url = $endpoint . '?' . $string_request . '&Signature=' . $signature;

echo($url);

    // xml取得
    $xml = simplexml_load_string(getHttpContent($url));

    $item = $xml->Items->Item;
   var_dump($xml->Items->Item);
   // echo "画像URL：".$item->LargeImage->URL."\n";
    return $item;
}

function getHttpContent($url)
{
    try {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $body = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if (CURLE_OK !== $errno) {
            throw new RuntimeException($error, $errno);

        }
        return $body;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

?>
