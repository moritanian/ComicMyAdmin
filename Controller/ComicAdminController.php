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

		$ret = $this->getUserVolumeData($series_id);
		$this->setCategoryData($ret->series_data);
		$this->view->series_data = $ret->series_data;
		$this->view->volume_list = $ret->volume_list;
		$this->view->show("ComicAdmin/VolumeMyList");
		
	} 

	// top
	public function indexAction(){
		$topSlideImgs = $this->getTopSlideImages();
		$this->view->topSlideImgs = $topSlideImgs;
		$recentlyActivityData = $this->getRecentlyActivitySeriesData();
		$this->view->recentlyActivities = $recentlyActivityData;
		//var_dump($recentlyActivityData);
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

	// マスタデータ編集画面
	public function EditComicVolumeAction(){
		$this->checkAuthority(2);
		$seriesId = $this->request->seriesId;
		$ret = $this->execEditComicMaster();
		$this->view->ret = $ret;
		if(!$seriesId){
			$this->view->is_incorrect_id = 1;
		}else{
			$ret = $this->getVolumeMaster($seriesId);
			if($ret){
				$this->appendCategory($ret->series_data);
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

	// 新刊ページ
	public function NewPublicationAction(){
		$page = $this->request->page ?  $this->request->page : 1;
		$search_text = $this->request->search_text ?  $this->request->search_text : "";
		$this->view->info = $this->getMonthlyPublication($page, $search_text, true);
		$this->view->search_text = $search_text;
		$this->view->show("ComicAdmin/NewPublication");

	}

	public function AutoAddComicAction(){
		$search_title = $this->request->search_title;
		$this->view->itemInfo = array();
		if($search_title){
			$this->view->search_title = $search_title;
			$this->view->itemInfo = getRakutenItemBySeriesName($search_title);
		}
		$this->view->show("ComicAdmin/AutoAddComic");
	}

	// testページ
	public function testAction(){
		$this->checkAuthority(2);
		$this->view->itemInfo = getRakutenItemByItemName("のんのんびより(10)");
		//$this->view->itemInfo = getRakutenItemByISBN("9784785939885");
		$this->view->show("ComicAdmin/test");
	}

	public function Libre3DAction(){
		$this->view->show("ComicAdmin/Libre3D");
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

	private function execEditComicMaster(){
		$ret = (object)array('edit' => "", 'result' => false);

		if($this->request->series_submit || $this->request->all_submit){ // シリーズデータ
			$ret->edit = "series_data";
			$series_data = $this->request->series_data;
			$updateData = array(
				'series_id' => $this->request->seriesId,
				'title'		=> $series_data['title'],
				'kana' 		=> $series_data['kana'],
				'is_end'	=> $series_data['is_end'],
				'author'	=> $series_data['author'],
				'press'		=> $series_data['press'],
				'explain_text' => $series_data['explain_text']
				);
			$updateData += $this->convertUserDataInCategoryData($series_data['category']);
			$comic_series_model = new ComicSeriesMaster();
			$ret->result = $comic_series_model->updateData($updateData);
		}
		if($this->request->volume_submit_list || $this->request->all_submit){ // volume
			
			$edit_volume_list = array();
			if($this->request->volume_submit_list){
				$ret->edit = "volume_data";
				$volume_submit_list = $this->request->volume_submit_list;
				$edit_book_ids = array_keys($volume_submit_list);
				$volume_list = $this->request->volume_list;
				foreach ($edit_book_ids as $key => $book_id) {
					$edit_volume_list["$book_id"] = $volume_list[$book_id]; 
				}
			}else{
				$ret->edit= 'all';
				$edit_volume_list = $this->request->volume_list;
			}
			
			if(count($edit_volume_list)){
				$ret->result = true;
			}
			
			$comic_volume_master = new ComicVolumeMaster();
			foreach ($edit_volume_list as $book_id => $volume_data) {
				$volume_data['book_id'] = $book_id;
				if(!$comic_volume_master->updateDataByBookId($volume_data)){
					$ret->result = false; // 更新失敗した場合、falseに
				}
			}
			
		}
		
		return $ret;
	}

	// カテゴリーチェックリストをモデルデータ形式に変更
	private function convertUserDataInCategoryData($category_list){
		$category_data = array();
		$i = 1;
		foreach ($category_list as $key => $category_id) {
			$category_data["category$i"] = $category_id;
			$i ++;
		}
		for($j = $i; $j <= 10; $j++){
			$category_data["category$j"] = 0;
		}
		return $category_data;
	}

	public function getRecentlyActivitySeriesData(){
		$userId = $this->userData['user_id'];
		$user_comic_series_model = new UserComicSeriesData();
		$user_comic_volume_model = new UserComicVolumeData();
		$user_recenly_series = $user_comic_series_model->getRecentlyUpdateIds($userId);
		$user_recenly_volume = $user_comic_volume_model->getRecentlyUpdateIds($userId);
		$user_data = array_merge($user_recenly_series,$user_recenly_volume);
		/* 　series と volume をガチャンコ
			それぞれのuser_data は update_date で　ソート済みの前提
		*/
		function sortByUpdate($user_data1, $user_data2){
			$date1 = $user_data1['update_time'];
			$date2 = $user_data2['update_time'];
			if($date1 == $date2){
				return 0;
			}
			return $date1 < $date2 ? -1 : 1;
		}
		usort($user_data, "sortByUpdate");
		$seriesIds = array();
		foreach ($user_data as $key => $data) {
			$seriesId = $data['series_id'];
			if(in_array($seriesId, $seriesIds)){ // すでにuser_data に 同じseries_id が存在する場合、削除
				unset($user_data[$key]);
			}else{ 								// マスタデータ付加 
				if(!$this->appendSeriesMaster($user_data[$key])){
					unset($user_data[$key]);
				}
				array_push($seriesIds, $seriesId);
				
			}
		}
		return $user_data;
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
			//$this->setCategoryData($series_data);
			//$this->appendCategory($series_data);
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
			$volumeMasterData->series_data['series_img'] = "http://ecx.images-amazon.com/images/I/51nJbKvEHfL._SX250_.jpg";
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
						$volumeMasterData->volume_list[$master_key] = array_merge($volume, $user_volume); // 配列を結合
						break;
					}
				}
			}
		}
		return $volumeMasterData;
	}

	// マスタデータ付加
	private function appendSeriesMaster(&$usrSeriesData){
		$comic_series_master = new ComicSeriesMaster();
		$series_master_data = $comic_series_master->getBySeriesId($usrSeriesData['series_id']);
		$ret = false;
		if($series_master_data){
			$usrSeriesData["title"] = $series_master_data['title'];
			$usrSeriesData["kana"] = $series_master_data['kana'];
			$usrSeriesData['is_end'] = $series_master_data['is_end'];
			$usrSeriesData['author'] = $series_master_data['author'];
			$usrSeriesData['press'] = $series_master_data['press'];
			$usrSeriesData['explain_text'] = $series_master_data['explain_text'];
			for($i=1; $i <= 10; $i++){
				$usrSeriesData["category$i"] = $series_master_data["category$i"];
			}
			$ret = true;
		}
		return $ret;
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

	// ユーザデータにカテゴリーをカテゴリーマスタ順で取得し追加
	private function appendCategory(&$series_data){
		$category_master = new ComicCategoryMaster();
		$all_category_list = $category_master->getAll();
		$user_category_list = array();
		foreach ($all_category_list as $master_key => $category) {
			$category["selected"] = 0;
			for ($i=1; $i<=10; $i++) {
				$category_id = $series_data["category$i"];
				if($category_id != $category["category_id"])continue;
				$category["selected"] = 1;
			}
			array_push($user_category_list, $category);
		}
		$series_data['category'] = $user_category_list;
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
		$bookId_right = str_pad($seriesId, 10 - $length, 0, STR_PAD_RIGHT);
		$bookId = str_pad($bookId_right, 5, 0, STR_PAD_RIGHT);
		$bookIds = array();
		for($i = $from; $i<=$to; $i++){
			$id = $bookId +$i;
			$bookIds[$i] = $id;
		}
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

	private function getMonthlyPublication($page, $search_text, $new = false){
		$month = date("m");
		$year = date("Y");
		$info = $new ? getRakutenNewlyPublication(): getRakutenMonthlyPublication($year, $month);
		$itemPerPage = 20;
		$index = ($page - 1) * $itemPerPage;

		$app_info = array();
		if($search_text){
			echo("se = ".$search_text);
			foreach ($info as $key => $item) {
				similar_text($item['title'], $search_text, $percent);
				if($percent> 40){
					echo((int)$percent. " ");
					array_push($app_info, $item);
				}
			}
		}else{
			$app_info = $info;
		}


		$slice_info = array_slice($app_info, $index, $itemPerPage);
		$item_all = count($app_info);
		$pages = (int)(($item_all + $itemPerPage - 1)/$itemPerPage);
		$data = array(
			'year'	=> $year,
			'month'	=> $month,
			'page'	=> $page,
			'itemPerPage' => $itemPerPage,
			'pages'	=> $pages,
			'item_all'	=> $item_all
			);
		$ret = array(
			"data"		=> $data,
			"item_list" => $slice_info
			); 
		return $ret;
	}
}


?>