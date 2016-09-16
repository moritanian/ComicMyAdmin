<?php
require_once( 'Model/ModelBase.php');

class ComicVolumeMaster extends ModelBase
{
	protected $tableName = 'comic_volume_master';

	public function getAllBySeriesId($seriesId)
	{
		$sql = sprintf('SELECT * FROM %s where series_id = :seriesId', $this->tableName);
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':seriesId', $seriesId);
       	$stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
	}

	public function getAll(){
		$sql = sprintf('SELECT * FROM %s', $this->tableName);
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();
        return $rows;
	}

	public function insertData($seriesData){
		$sql = sprintf('INSERT INTO %s (book_id, series_id, book_name, release_date) values (:book_id, :series_id, :book_name, :release_date)', $this->tableName);
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':book_id', $seriesData['book_id']);
        $stmt->bindValue(':series_id', $seriesData['series_id']);
        $stmt->bindValue(':book_name', $seriesData['book_name']);
        $stmt->bindValue(':release_date', $seriesData['release_date']);
        $res = $stmt->execute();
		return $res;
	}

	public function countVolumeBySeriesId($series_id){
		$sql = sprintf('COUNT %s WHERE series_id = :series_id', $this->tableName);
		$stmt = $this->db->prepare($sql);
        $stmt->bindValue(':series_id', $seriesData['series_id']);
        $res = $stmt->execute();
		return $res;
	}

	public function updateDataByBookId($seriesData){
		$sql = sprintf('UPDATE %s SET book_name = :book_name, release_date = :release_date  where book_id = :book_id', $this->tableName);
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':book_id', $seriesData['book_id']);
        $stmt->bindValue(':book_name', $seriesData['book_name']);
        $stmt->bindValue(':release_date', $seriesData['release_date']);
        $res = $stmt->execute();
		return $res;
	}

}

?>