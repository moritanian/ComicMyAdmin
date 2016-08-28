<?php 

require_once( 'Controller/ControllerBase.php' );

class ComicAdminController  extends ControllerBase{

	public function SeriesAllListAction()
	{

		$comic_series_model = new ComicSeriesMaster();
		$all_comic_data = $comic_series_model->getAll();
		foreach ($all_comic_data as $key => $comic_data) {
			$all_comic_data[$key]['initial'] = mb_substr($comic_data['kana'],0 ,1);
		}
	var_dump($all_comic_data);
		$this->view->all_comic_data = $all_comic_data;
		$this->view->show("ComicAdmin/AllComicList");
	}

	public function SeriesMyListAction()
	{
		$user_comic_series_model = new UserComicSeriesData();
		$mylist = $user_comic_series_model->getAllByUserId($this->userData['user_id']);
		$this->view->mylist = $mylist;
		$this->view->show("ComicAdmin/Mylist");
	}

	// top
	public function indexAction(){
		$topSlideImgs = $this->getTopSlideImages();
		$this->view->topSlideImgs = $topSlideImgs;
		$this->view->show("Top");
	}

	public function AddComicSeriesAction(){
		$this->execAddComicSeries();
		$this->view->show("ComicAdmin/AddComicSeries");
	}

	// top ページスライド画像
	private function getTopSlideImages(){
		$images = array(
			"https://i.ytimg.com/vi/zm2rVIiddFE/maxresdefault.jpg",
			"http://blog-imgs-18.fc2.com/g/l/o/globaltour/eiga2.jpg",
			"http://degucchan.noor.jp/wp-content/uploads/2014/11/300.jpg"
			);
		return $images;
	}

	// タイトルをマスタに追加
	private function execAddComicSeries(){

	}
}


?>