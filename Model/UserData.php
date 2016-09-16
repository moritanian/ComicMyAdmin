<?php
require_once( 'Model/ModelBase.php');

class UserData extends ModelBase
{
	protected $tableName = 'comic_user_data';

        public function getByUserId($userId)
        {
                $sql = sprintf('SELECT * FROM %s where user_id = :userId ', $this->tableName);
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':userId', $userId);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                if(!isset($rows[0]))return null;
                return $rows;
	}

	public function getByUserName($userName){
                $sql = sprintf('SELECT * FROM %s where user_name = :userName', $this->tableName);
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':userName', $userName);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                if(!isset($rows[0]))return null;
                return $rows[0];
        }

        public function getByHash($hash){
                $sql = sprintf('SELECT * FROM %s where hash = :hash', $this->tableName);
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':hash', $hash);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                if(!isset($rows[0]))return null;
                return $rows[0];
        }

	public function insertData($userData){
		$sql = sprintf('INSERT INTO %s  (user_name, hash, mail_address, authority, notification_id, line_id) values ( :user_name, :hash, :mail_address, :authority, :notification_id, :line_id)', $this->tableName);
		$stmt = $this->db->prepare($sql);
        $stmt->bindValue(':use_name', $userData['user_name']);
        $stmt->bindValue(':hash', $userData['hash']);
        $stmt->bindValue(':mail_address', $userData['mail_address']);
        $stmt->bindValue(':authority', $userData['authority']);
        $stmt->bindValue(':notification_id', $userData['notification_id']);
        $stmt->bindValue(':line_id', $userData['line_id']);
        $res = $stmt->execute();
		return $res;
	}

}

?>