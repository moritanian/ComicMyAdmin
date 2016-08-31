<?php 

require_once( 'Controller/ControllerBase.php' );

class ComicAdminController  extends ControllerBase{

	public function SeriesAllListAction()
	{
		// マスタデータ
		$comic_series_model = new ComicSeriesMaster();
		$all_comic_data = $comic_series_model->getAll();
		// ユーザデータ
		$user_comic_series_model = new UserComicSeriesData();
		$mylist = $user_comic_series_model->getAllByUserId($this->userData['user_id']);
		
		// マイリストへの追加処理
		$is_add = $this->request->add ? 1 : 0;
		if($is_add){	//追加ボタン押された
			$series_ids = array();
			$request = $this->request->_all;
			foreach ($request as $key => $value) {
				preg_match('/id[0-9]+/', $key, $re);
				if(isset($re[0])){
					$series_id = (int)str_replace('id', '', $re[0]);
					array_push($series_ids, $series_id);
				}
			}
			$this->execAddMyList($series_ids, $user_comic_series_model);
		}
		$this->view->is_add = $is_add;

		// 検索条件
		$mylist_contain_cond = ($this->request->is_contain_my_list) ? $this->request->is_contain_my_list : 0;
		$search_text = $this->request->search_text;

		// initial でソート
		//$this->sortComicListByInitial($all_comic_data);

		foreach ($all_comic_data as $key => $comic_data) {
			$series_id = $all_comic_data[$key]['series_id'];
			$all_comic_data[$key]['is_contain_my_list'] = 0;
			$all_comic_data[$key]['initial'] = $this->get_initial($comic_data['kana']);
			foreach ($mylist as $key => $mylist_book) {
				if($mylist_book['series_id'] == $series_id){
					$all_comic_data[$key]['is_contain_my_list'] = 1;
					break;
				}
			}
		}

		if($mylist_contain_cond == 1){	// 含まれる
			foreach ($all_comic_data as $key => $value) {
				if($value['is_contain_my_list'] == 0){
					unset($all_comic_data[$key]);
				}
			}
		}elseif ($mylist_contain_cond == 2) { // 含まれない
			foreach ($all_comic_data as $key => $value) {
				if($value['is_contain_my_list'] == 1){
					unset($all_comic_data[$key]);
				}
			}
		}
		$this->view->all_comic_data = $all_comic_data;
		$this->view->cond = array(
			'mylist_contain_cond' => $mylist_contain_cond,
			'search_text'	=> $search_text
			);
		$this->view->show("ComicAdmin/AllComicList");
	}

	// シリーズマイリスト
	public function SeriesMyListAction()
	{
		$user_comic_series_model = new UserComicSeriesData();
		$mylist = $user_comic_series_model->getAllByUserId($this->userData['user_id']);
		// マスタデータ
		$comic_series_model = new ComicSeriesMaster();
		foreach($mylist as $key => $series){
			$comic_data = $comic_series_model->getBySeriesId($series['series_id']);	
			if($comic_data == null){
				unset($mylist[$key]);
				continue;
			}
			$mylist[$key]['title'] = $comic_data['title'];
			$mylist[$key]['kana'] = $comic_data['kana'];
 		}
 		// タイトル頭文字でソート
 		$this->sortComicListByInitial($mylist);

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
		$this->checkAuthority(2);
		$category_master = new ComicCategoryMaster();
		$category_list = $category_master->getAll();

		//$category_master->dump($_POST);
		$ret = $this->execAddComicSeries();
		
		$this->view->category_list = $category_list;
		$this->view->ret = $ret;
		$this->view->show("ComicAdmin/AddComicSeries");
	}

	// シリーズの一覧
	public function ComicVolumeListAction(){
		$series_id = (int)$this->request->series_id;
		//$comic_volume_master = new ComicVolumeMaster();
		//$volume_list = $comic_volume_master->getAllBySeriesId($series_id);
		$asin = "B00TEY2MG8"; //ニコンのカメラ
		$item = getAmazonItem($asin);
		$this->view->items= array($item);
		$this->view->show("ComicAdmin/VolumeList");
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
		$ret = array(
			"success" => 0,
			"error" => ""
		);
		if($this->request->add_series_title == null){
			return $ret;
		}
		$comic_series_model = new ComicSeriesMaster();
	
		if($this->request->title == null || $this->request->title == ""){
			$ret['error'] = "タイトルがありません";
			return $ret;
		}
		if($this->request->kana == null || $this->request->kana == ""){
			$ret['error'] = "カナがありません";
			return $ret;
		}
		$insert_data= array(
			'title' => $this->request->title,
			'kana'	=> $this->request->kana,
			'is_end' => $this->request->is_end ? $this->request->is_end : 0,
			'author' => $this->request->author ? $this->request->author : "",
			'press' => $this->request->press ? $this->request->press : "",
			'explain_text' => $this->request->explain_text ? $this->request->explain_text : "",
			);
		for($i = 1; $i<=10; $i++){
			$key = "category$i";
			$insert_data[$key] = $this->request->$key ? $this->request->$key : 0;
		}
		$comic_series_model->insertData($insert_data);
		$ret['success'] = 1;
 		return $ret;
	}

	// mylist に追加
	private function execAddMyList($series_ids, $model){
		foreach ($series_ids as $key => $series_id) {
			$ins_data = array(
				'user_id' => $this->userData['user_id'],
				'series_id' => $series_id,
				'is_list' => "1"
				);
			$model->insertData($ins_data);
		}
		
	}

	private function get_initial($name){
		return mb_substr($name ,0 ,1);
	}

	// タイトル頭文字でソート
	private function sortComicListByInitial($comic_list){
		$initial_arr = array();
		foreach($comic_list as $key => $series){
			$initial_arr[$key] = $this->get_initial($series['kana']);
			$comic_list[$key]['initial'] = $initial_arr[$key];
 		}
 		// タイトル頭文字でソート
 		array_multisort ( $initial_arr , SORT_ASC , $comic_list);

	}
}


?>