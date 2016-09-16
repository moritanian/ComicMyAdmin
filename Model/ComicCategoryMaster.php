<?php
require_once( 'Model/ModelBase.php');

class ComicCategoryMaster extends ModelBase
{
	protected $tableName = 'comic_category_master';

	public function getByCategoryId($categoryId)
	{
		$sql = sprintf('SELECT * FROM %s where category_id = :categoryId', $this->tableName);
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':categoryId', $categoryId);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if(!isset($rows[0])){
        	return null;
        }
        return $rows[0];
	}

	public function getAll(){
		$sql = sprintf('SELECT * FROM %s', $this->tableName);
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();
        return $rows;
	}

	public function insertData($seriesData){
		$sql = sprintf('INSERT INTO %s (category_id, category_name) values (:category_id, :category_name)', $this->tableName);
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':category_id', $seriesData['category_id']);
        $stmt->bindValue(':category_name', $seriesData['category_name']);
        $res = $stmt->execute();
		return $res;
	}

}

?>