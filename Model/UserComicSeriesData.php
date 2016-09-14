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

          public function getByUserIdAndSeriesId($userId, $seriesId)
        {
                $sql = sprintf('SELECT * FROM %s where user_id = :userId and series_id = :series_id', $this->tableName);
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':userId', $userId);
                $stmt->bindValue(':series_id', $seriesId);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                if(!isset($rows[0])){
                        return null;
                }
                return $rows[0];
        }

	public function insertData($data){

		$sql = sprintf('INSERT INTO %s  (user_id, series_id, user_comment, assessment, is_list, is_notify) values (:user_id, :series_id, :user_comment, :assessment, :is_list, :is_notify)', $this->tableName);
               
		$stmt = $this->db->prepare($sql);
                $stmt->bindValue(':user_id', $data['user_id']);
                $stmt->bindValue(':series_id', $data['series_id']);
                $stmt->bindValue(':user_comment', isset($data['user_comment']) ? $data['user_comment'] : "" );
                $stmt->bindValue(':assessment', isset($data['assessment']) ? $data['assessment'] : 0);
                $stmt->bindValue(':is_list', isset($data['is_list']) ? $data['is_list'] : 0);
                $stmt->bindValue(':is_notify', isset($data['is_notify']) ? $data['is_notify'] : 0 );
                $res = $stmt->execute();
		return $res;
	}

   public function getRecentlyUpdateIds($userId, $limit = 5){
        $sql = sprintf('select series_id, update_time from %s WHERE user_id = :user_id ORDER BY update_time DESC LIMIT :limit', $this->tableName);
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }

}

?>