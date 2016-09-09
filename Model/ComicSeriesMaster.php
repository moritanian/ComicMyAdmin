<?php
require_once( 'Model/ModelBase.php ');

class ComicSeriesMaster extends ModelBase
{
	protected $tableName = 'comic_series_master';

	public function getBySeriesId($seriesId)
	{
		$sql = sprintf('SELECT * FROM %s WHERE series_id = :seriesId', $this->tableName);
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':seriesId', $seriesId);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                if(!isset($rows[0])){
                        return null;
                }
                return $rows[0];
	}

	public function getAll(){
		$sql = sprintf('SELECT * FROM %s order by kana', $this->tableName);
                $stmt = $this->db->query($sql);
                $rows = $stmt->fetchAll();
                return $rows;
	}

	public function getByLikeName($likeName){
                $likeName = $this->escape_str($likeName);
                $sql = sprintf('SELECT * FROM %s  where title like :like_name  order by kana', $this->tableName);
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':like_name', $likeName);
                $stmt->execute();       
                $rows = $stmt->fetchAll();
                return $rows;
	}

	public function insertData($seriesData){
		$sql = sprintf('INSERT INTO %s (title, kana, category1, category2, category3, category4, category5, category6, category7, category8, category9, category10, is_end, author, press, explain_text) values (:title, :kana,  :category1, :category2, :category3, :category4, :category5, :category6, :category7, :category8, :category9, :category10, :is_end, :author, :press, :explain_text)', $this->tableName);
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':kana', $seriesData['kana']);
        $stmt->bindValue(':title', $seriesData['title']);
        $stmt->bindValue(':is_end', $seriesData['is_end'], PDO::PARAM_INT);
        $stmt->bindValue(':author', $seriesData['author']);
        $stmt->bindValue(':press', $seriesData['press']);
        $stmt->bindValue(':explain_text', $seriesData['explain_text']);
        for($i = 1; $i <= 10; $i++){
        	$key = "category".$i;
        	$stmt->bindValue(":".$key, $seriesData[$key], PDO::PARAM_INT);
        }
        $res = $stmt->execute();
		return $res;
	}

}

?>