<?php 

require_once( "Request.php" );

require_once( 'Model/ComicSeriesMaster.php' );

require_once( 'Model/UserComicSeriesData.php' );

require_once( 'Model/ModelBase.php ');

require_once( 'Model/UserData.php');

require_once( 'Model/ComicCategoryMaster.php');

require_once( 'MyTemplate.php' );

class ControllerBase {

	protected $view;

	protected $request;

    protected $userData;

    protected $app_pos;

	public function __construct($url, $app_pos)
    {
        $this->app_pos = $app_pos;
        //user data
        $userName = $_SESSION['username'] ? $_SESSION['username'] : "";
  
        $userDataModel = new UserData();
            
        $this->userData = $userDataModel->getByUserName($userName);

        // リクエスト

        $this->request = new Request();

        //ビューインスタンス化

        $this->view = new MyTemplate();

        $this->view->app_pos = $app_pos;
    }

    // 権限を満たしているか
    public function checkAuthority($authority){
        if($authority > $this->userData['authority']){
            // 権限をみたしていない場合はその旨を表示して終了
            require_once("/View/403Error.php");
            exit();
        }
    }
}
?>