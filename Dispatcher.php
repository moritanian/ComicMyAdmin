<?php

require_once 'Controller/LoginController.php';

class Dispatcher

{

    private $sysRoot;

    //URLの階層設定。どの階層からをパラメータとして解釈するか。

    // /直下に置くなら0, /hoge/以下に置くなら1, /hoge/piyo/以下に置くなら2

    private $paramLevel=1; 

    private $app_pos;

    public function setSystemRoot($path)

    {

        $this->sysRoot = rtrim($path, '/');

    }



    /**

     * URLの階層設定。どの階層からをパラメータとして解釈するか。

     * @param int $iLevel

     */

    public function setPramLevel($iLevel) {

        $this->paramLevel=$iLevel;

    }

    public function setAppPosition($app_pos){
        $this->app_pos = $app_pos;
    }

    public function dispatch()

    {

        $params_tmp = array();


        //?で分割。GETパラータを無視するため

        $params_tmp = explode('?', $_SERVER['REQUEST_URI']);

//var_dump($params_tmp);
        // パラメーター取得（末尾,先頭の / は削除）

        $params_tmp[0] = preg_replace('/\/?$/', '', $params_tmp[0]);

        $params_tmp[0] = preg_replace('/^\/*/', '', $params_tmp[0]);

        $params = array();

        if ('' != $params_tmp[0]) {

            // パラメーターを / で分割

            $params = explode('/', $params_tmp[0]);

        }

        

        // １番目のパラメーターをコントローラーとして取得

        $controller = "index";

        if ($this->paramLevel < count($params)) {

            $controller = $params[$this->paramLevel];

        }

        // パラメータより取得したコントローラー名によりクラス振分け

        //$className = ucfirst(strtolower($controller)) . 'Controller';
       
        @session_start();
$className = ucfirst($controller) . 'Controller';
        if($className != "LoginController"){  
            if (!isset($_SESSION['username'])) {
                header('Location:' .$this->sysRoot .'/Login');
                exit();
            }   
        }

        //echo("sysroot = " . $this->sysRoot);

        // クラスファイル読込
        $file_name =   $this->sysRoot . '/Controller/' . $className . '.php';
        // ファイル名不正か
        if(!file_exists($file_name)){
           // header('Location:' . $this->sysRoot. 'ComicAdmin/ErrorPage.php')
            $this->notFoundError();
        }
        require_once $file_name;

        $url ="/";

        for ($i = 0; $i < $this->paramLevel; $i++) {

            $url = $url . $params[$i] . "/";

        }

        // クラスインスタンス生成

        $controllerInstance = new $className($url, $this->app_pos);

         // 2番目のパラメーターをコントローラーとして取得

         $action= 'index';        

         if ( ($this->paramLevel + 1) < count($params)) {

         $action= $params[($this->paramLevel + 1)];

         } 

        // アクションメソッドを実行

        $actionMethod = $action . 'Action';

        if(!method_exists($controllerInstance, $actionMethod)){
            $this->notFoundError();
        }

        $controllerInstance->$actionMethod();

        

    }

    private function notFoundError(){
        $redirectUrl = "/View/404Error.php";
        header("HTTP/1.0 404 Not Found");
        $v = (object) array('app_pos' => $this->app_pos);
        //$v->sysRoot = $this->sysRoot;
        require_once($redirectUrl);
       // print(file_get_contents($redirectUrl));
        exit();
    }

}



?>
