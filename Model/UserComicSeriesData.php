<?php
require_once( 'Model/ModelBase.php ');

class UserComicSeriesData extends ModelBase
{
	protected $tableName = 'user_comic_series_data';

        public function getAllByUserId($userId)
        {
                $sql = sprintf('SELECT * FROM %s where user_id = :userId', $this->tableName);
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':userId', $userId);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                return $rows;
	}

	public function insertData($data){
		$sql = sprintf('INSERT INTO %s  (user_id, series_id, user_comment, assessment, is_list, is_notify) values (:user_id, :user_id, :series_Id, :user_comment, :assessment, :is_list, :is_notify)', $this->tableName);
		$stmt = $this->db->prepare($sql);
        $stmt->bindValue(':use_id', $data['user_id']);
        $stmt->bindValue(':series_id', $data['series_id']);
        $stmt->bindValue(':user_comment', $data['mail_address']);
        $stmt->bindValue(':assessment', $data['authority']);
        $stmt->bindValue(':is_list', $data['is_list']);
        $stmt->bindValue(':is_notify', $data['is_notify']);
        $res = $stmt->execute();
		return $res;
	}

}

?>