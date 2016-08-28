<?php
require_once( 'Model/ModelBase.php ');

class ComicSeriesMaster extends ModelBase
{
	protected $tableName = 'comic_series_master';

	public function getBySeriesId($seriesId)
	{
		$sql = sprintf('SELECT * FROM %s where series_id = :seriesId', $this->tableName);
        $stmt = $this->db->query($sql);
        $stmt->bindValue(':seriesId', $seriesId);
        $rows = $stmt->fetchAll();
        return $rows;
	}

	public function getAll(){
		$sql = sprintf('SELECT * FROM %s', $this->tableName);
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();
        return $rows;
	}

	public function getByLikeName($likeName){

	}

	public function insertData($seriesData){
		$sql = sprintf('INSERT INTO %s (title, kana category1, category2, category3, category4, caegory5, category6, category7, category8, category9, category10, is_end, author, press, explain_text) values (:title, :kana,  :category1, :category2, :category3, :category4 :category5, category6, :category7, :category8, :category9, :category10, :is_end, :author, :press, :explain_text)', $this->tableName);
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':kana', $seriesData['kana']);
        $stmt->bindValue(':title', $seriesData['title']);
        $stmt->bindValue(':is_end', $seriesData['is_end']);
        $stmt->bindValue(':author', $seriesData['author']);
        $stmt->bindValue(':press', $seriesData['press']);
        $stmt->bindValue(':explain_text', $seriesData['explain_text']);
        for($i = 0; $i < 10; $i++){
        	$stmt->bindValue(":category$i", $seriesData["category$i"]);
        }
        $res = $stmt->execute();
		return $res;
	}

}

?>