<?php
require_once( 'Model/ModelBase.php ');

class UserComicVolumeData extends ModelBase
{
	protected $tableName = 'user_comic_volume_data';

        public function getAllByUserId($userId)
        {
                $sql = sprintf('SELECT * FROM %s where user_id = :userId', $this->tableName);
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':userId', $userId);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                return $rows;
	}

         public function getAllByUserIdAndSeriesId($userId, $seriesId)
        {
                $sql = sprintf('SELECT * FROM %s where user_id = :userId and series_id = :seriesId', $this->tableName);
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':userId', $userId);
                $stmt->bindValue(':seriesId', $seriesId);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                return $rows;
        }

	public function insertData($data){
		$sql = sprintf('INSERT INTO %s  (user_id, book_id, is_possess, is_read, user_comment, assessment) values (:user_id, :book_id, :is_possess, :is_read, :user_comment, :assessment)', $this->tableName);
		$stmt = $this->db->prepare($sql);
        $stmt->bindValue(':use_id', $data['user_id']);
        $stmt->bindValue(':book_id', $data['book_id']);
        $stmt->bindValue(':is_possess', $data['is_possess']);
        $stmt->bindValue(':is_read', $data['is_read']);
        $stmt->bindValue(':user_comment', $data['user_comment']);
        $stmt->bindValue(':assessment', $data['assessment']);
        $res = $stmt->execute();
		return $res;
	}

}

?>