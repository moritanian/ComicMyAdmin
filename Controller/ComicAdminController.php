<?php 

require_once( 'Controller/ControllerBase.php' );

class ComicAdminController  extends ControllerBase{

	public function SeriesAllListAction()
	{
		// マスタデータ
		$comic_series_model = new ComicSeriesMaster();
		// ユーザデータ
		$user_comic_series_model = new UserComicSeriesData();
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
		$search_text = "%" . $this->request->search_text . "%";

		
		$all_comic_data = ($search_text == "") ? $comic_series_model->getAll() : $comic_series_model->getByLikeName($search_text) ;
		
		$mylist = $user_comic_series_model->getAllByUserId($this->userData['user_id']);
		

		// initial でソート
		//$this->sortComicListByInitial($all_comic_data);

		foreach ($all_comic_data as $master_key => $comic_data) {
			$series_id = $all_comic_data[$master_key]['series_id'];
			$all_comic_data[$master_key]['is_contain_my_list'] = 0;
			$all_comic_data[$master_key]['initial'] = $this->get_initial($comic_data['kana']);
			foreach ($mylist as $model_key => $mylist_book) {
				if($mylist_book['series_id'] == $series_id){
					$all_comic_data[$master_key]['is_contain_my_list'] = 1;
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
			'search_text'	=> $this->request->search_text
			);
		$this->view->show("ComicAdmin/AllComicList");
	}

	// シリーズマイリスト
	public function SeriesMyListAction()
	{
		
		$this->view->mylist = $this->getUserSeriesData();
		$this->view->show("ComicAdmin/Mylist");
	}

	// volume マイリスト
	public function VolumeMyListAction()
	{
		$series_id = (int)$this->request->series_id;
		$user_id = $this->userData['user_id'];
/*
		// シリーズマスタ
		$comic_series_model = new ComicSeriesMaster();
		$series_data = $comic_series_model->getBySeriesId($series_id);
		// ユーザシリーズデータ
		$user_comic_series_model = new UserComicSeriesData();
		$series_user_data = $user_comic_series_model->getByUserIdAndSeriesId($user_id, $series_id);
		// ユーザvolume データ
		$user_comic_volume_model = new UserComicVolumeData();
		$volume_list = $user_comic_volume_model->getAllByUserIdAndSeriesId($user_id, $series_id);
		// volume model
		$comic_volume_master = new ComicVolumeMaster();
		$volume_list = $comic_volume_master->getAllBySeriesId($series_id);
*/
		$ret = $this->getUserVolumeData($series_id);
		$this->view->series_data = $ret->series_data;
		$this->view->volume_list = $ret->volume_list;
		$this->view->show("ComicAdmin/VolumeMyList");
		
	} 

	// top
	public function indexAction(){
		$topSlideImgs = $this->getTopSlideImages();
		$this->view->topSlideImgs = $topSlideImgs;
		$this->view->show("Top");
	}

	public function AddComicSeriesAction(){
		$this->checkAuthority(2);

		$ret = $this->execAddComicSeries();
		
		$category_master = new ComicCategoryMaster();
		$category_list = $category_master->getAll();
		$this->view->category_list = $category_list;
		$this->view->ret = $ret;
		$this->view->show("ComicAdmin/AddComicSeries");
	}

	public function EditComicVolumeAction(){
		$this->checkAuthority(2);
		$seriesId = $this->request->seriesId;
		if(!$seriesId){
			$this->view->is_incorrect_id = 1;
		}else{
			$ret = $this->getVolumeMaster($seriesId);
			if($ret){
				$this->view->series_data = $ret->series_data;
				$this->view->volume_list = $ret->volume_list;
			}else{
				$this->view->is_incorrect_id = 1;
			}
		}
		$this->view->show("ComicAdmin/EditComicVolume");
	}

	// シリーズの一覧
	public function ComicVolumeListAction(){
		$series_id = (int)$this->request->series_id;
		$user_id = $this->userData['user_id'];

		// シリーズマスタ
		$comic_series_model = new ComicSeriesMaster();
		$series_data = $comic_series_model->getBySeriesId($series_id);
		$series_data['series_img'] = "http://ecx.images-amazon.com/images/I/51nJbKvEHfL._SX250_.jpg";
		// volume model
		$comic_volume_master = new ComicVolumeMaster();
		$volume_list = $comic_volume_master->getAllBySeriesId($series_id);

		$this->setCategoryData($series_data);		
		
		$this->view->series_data = $series_data;
		$this->view->volume_list = $volume_list;


	//	$asin = "B00TEY2MG8"; //ニコンのカメラ
	//	$item = getAmazonItem($asin);
	//	$this->view->items= array($item);
		$this->view->show("ComicAdmin/VolumeList");
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
		$comic_volume_master = new ComicVolumeMaster();

		$title = $this->request->title;

		if($title == null || $title == ""){
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
		$seriesId = $comic_series_model->getLastInsertId();

		$all_volume_number = (int)$this->request->all_volume_number;
		$bookIds = $this->getNewBookIds($seriesId, 1, $all_volume_number);
		foreach ($bookIds as $volume_num => $id) {
			$book_name = $title . $volume_num;
			$volume_data = array(
				'book_id' => $id,
				'series_id' => $seriesId,
				'book_name' => $book_name,
 				'release_date' => ''
			);
			$comic_volume_master->insertData($volume_data);
		}
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

	private function getSeriesMaster($seriesId){

	}

	// ユーザ登録されているすべてのシリーズデータ
	private function getUserSeriesData(){
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
		return $mylist;
	}

	private function getVolumeMaster($seriesId){
		$comic_series_master = new ComicSeriesMaster();
		$comic_volume_master = new ComicVolumeMaster();
		$series_data = $comic_series_master->getBySeriesId($seriesId);
		$ret = (object)array();
		if($series_data == null){
			$ret = null;
		}else{
			$this->setCategoryData($series_data);
			$volume_list = $comic_volume_master->getAllBySeriesId($seriesId);
			$ret->series_data = $series_data;
			$ret->volume_list = $volume_list;
		}
		return $ret;
	}

	// ユーザ登録されている
	private function getUserVolumeData($seriesId){
		$user_comic_volume_model = new UserComicVolumeData();
		$volumeMasterData = $this->getVolumeMaster($seriesId);

		if($volumeMasterData){
			$user_volume_list = $user_comic_volume_model->getAllByUserIdAndSeriesId($this->userData['user_id'] ,$seriesId);
			foreach ($volumeMasterData->volume_list as $master_key => $volume) {
				// user data 初期値
				$volumeMasterData->volume_list[$master_key] += array(
					'is_possess' => 0,
					'is_read'	=> 0,
					'user_comment' => "",
					'assessment'	=> 0
					);
				foreach ($user_volume_list as $list_key => $user_volume) {
					if($volume['book_id'] == $user_volume['book_id']){
						$volume += $user_volume; // 配列を結合
						break;
					}
				}
			}
		}
		return $volumeMasterData;
	}

	// series data にカテゴリーを追加
	private function setCategoryData(&$series_data){
		// categiry master
		$category_master = new ComicCategoryMaster();
		for($i=1; $i<=10; $i++){
			$category_id = $series_data["category$i"];
			if($category_id){
				$category = $category_master->getByCategoryId($category_id);
				$series_data["category${i}_name"] = (isset($category['category_name'])) ? $category['category_name'] : "";
			}
		}
	}

	// seriesId から新しくbookIdを生成する
	private function getNewBookIds($seriesId, $from, $to){
		
		if(!$to){
			$to = $from;
		}

		$length = strlen($seriesId);
		if($length > 10){
			return null;
		}
	echo($to);
		$bookId_right = str_pad($seriesId, 10 - $length, 0, STR_PAD_RIGHT);
		$bookId = str_pad($bookId_right, 5, 0, STR_PAD_RIGHT);
		$bookIds = array();
		for($i = $from; $i<=$to; $i++){
			$id = $bookId +$i;
			$bookIds[$i] = $id;
		}
		var_dump($bookIds);
		return $bookIds;
	}

	private function get_initial($name){
		return mb_substr($name ,0 ,1);
	}

	// タイトル頭文字でソート
	private function sortComicListByInitial(&$comic_list){
		$initial_arr = array();
		foreach($comic_list as $key => $series){
			$initial_arr[$key] = $this->get_initial($series['kana']);
			$comic_list[$key]['initial'] = $initial_arr[$key];
 		}
 		// タイトル頭文字でソート
 		array_multisort ( $initial_arr , SORT_ASC , $comic_list);

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
}


?>