<?php 

require_once( "Request.php" );

require_once( 'Model/ComicSeriesMaster.php' );

require_once( 'Model/ModelBase.php ');

require_once( 'MyTemplate.php' );



class ComicAdminController {

	private $view;

	private $request;

	public function __construct($url)
    {

        // リクエスト

        $this->request = new Request();

        //ビューインスタンス化

        $this->view = new MyTemplate();

    }



	public function SeriesAllListAction()
	{

		$comic_series_model = new ComicSeriesMaster();
		$all_comic_data = $comic_series_model->getAll();
		$this->view->all_comic_data = $all_comic_data;
		$this->view->show("AllComicList");
		var_dump($all_comic_data); 
	}

	public function SeriesMyList($userData)
	{

	}

	// top
	public function indexAction(){
		$this->view->show("Top");
	}
}


?>