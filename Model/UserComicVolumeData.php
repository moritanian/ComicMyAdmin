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

          public function getByUserIdAndBookId($userId, $bookId)
        {
                $sql = sprintf('SELECT * FROM %s where user_id = :userId and book_id = :bookId', $this->tableName);
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':userId', $userId);
                $stmt->bindValue(':bookId', $bookId);
                $stmt->execute();
                $row = $stmt->fetch();
                return $row;
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
error_log(print_r($data, true));
		$sql = sprintf('INSERT INTO %s  (user_id, book_id, series_id, is_possess, is_read, user_comment, assessment) values (:user_id, :book_id, :series_id, :is_possess, :is_read, :user_comment, :assessment)', $this->tableName);
		$stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $data['user_id']);
        $stmt->bindValue(':book_id', $data['book_id']);
        $stmt->bindValue(':series_id', $data['series_id']);
        $stmt->bindValue(':is_possess', $data['is_possess']);
        $stmt->bindValue(':is_read', $data['is_read']);
        $stmt->bindValue(':user_comment', $data['user_comment']);
        $stmt->bindValue(':assessment', $data['assessment']);
        $res = $stmt->execute();
		return $res;
	}

    public function updateData($data){
error_log("update" . print_r($data, true));
        if(!isset($data['book_id']) || !isset($data['user_id']))return ;
        $sql = sprintf('UPDATE %s SET is_possess = :is_possess, is_read = :is_read, user_comment = :user_comment, assessment = :assessment where user_id = :user_id and book_id = :book_id', $this->tableName);
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':book_id', $data['book_id'], PDO::PARAM_INT);
        $stmt->bindValue(':is_possess', $data['is_possess'], PDO::PARAM_INT);
        $stmt->bindValue(':is_read', $data['is_read'], PDO::PARAM_INT);
        $stmt->bindValue(':user_comment', $data['user_comment'], PDO::PARAM_STR);
        $stmt->bindValue(':assessment', $data['assessment'], PDO::PARAM_INT);
        $res = $stmt->execute();
        return $res;
    }

    public function updateInsertData($data){
        if($this->getByUserIdAndBookId($data['user_id'], $data['book_id'])){
            $this->updateData($data);
        }else{
            $this->insertData($data);
        }
    }

}

?>