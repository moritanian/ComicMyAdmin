<?php
require_once( 'Model/ModelBase.php ');

class ComicVolumeMaster extends ModelBase
{
	protected $tableName = 'comic_volume_master';

	public function getAllBySeriesId($seriesId)
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

	public function insertData($seriesData){
		$sql = sprintf('INSERT INTO %s (book_id, series_id, release_date) values (:book_id, :series_id, :release_date)', $this->tableName);
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':book_id', $seriesData['book_id']);
        $stmt->bindValue(':series_id', $seriesData['series_id']);
        $stmt->bindValue(':release_date', $seriesData['release_date']);
        $res = $stmt->execute();
		return $res;
	}

}

?>