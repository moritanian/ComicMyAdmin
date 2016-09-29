<?php
require_once( 'Model/ModelBase.php');

class UserData extends ModelBase
{
	protected $tableName = 'comic_user_data';

        public function getAll(){
                $sql = sprintf('SELECT * FROM %s', $this->tableName);
                $stmt = $this->db->query($sql);
                $rows = $stmt->fetchAll();
                return $rows;
        }

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
		$sql = sprintf('INSERT INTO %s  (g_user_id, user_name, hash, mail_address, authority, notification_id, line_id) values (:g_user_id, :user_name, :hash, :mail_address, :authority, :notification_id, :line_id)', $this->tableName);
error_log(isset($userData['g_user_id']) ? $userData['g_user_id'] : "");
		$stmt = $this->db->prepare($sql);
                $stmt->bindValue(':g_user_id', isset($userData['g_user_id']) ? $userData['g_user_id'] : NULL);
                $stmt->bindValue(':use_name', $userData['user_name']);
                $stmt->bindValue(':hash', $userData['hash']);
                $stmt->bindValue(':mail_address', $userData['mail_address']);
                $stmt->bindValue(':authority', $userData['authority']);
                $stmt->bindValue(':notification_id', $userData['notification_id']);
                $stmt->bindValue(':line_id', $userData['line_id']);
                $res = $stmt->execute();
		return $res;
	}

        public function getByGoogleId($g_id){
                $sql = sprintf('SELECT * FROM %s where g_user_id = :gUserId ', $this->tableName);
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':gUserId', $g_id, PDO::PARAM_INT);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                if(!isset($rows[0]))return null;
                return $rows[0];  
        }

        public function updateUserNameByGoogleUserId($g_id, $user_name){
                $sql = sprintf("UPDATE %s SET user_name = :user_name where g_user_id = :g_user_id", $this->tableName);
error_log($sql);
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':user_name', $user_name);
                $stmt->bindValue(':g_user_id', $g_id);
                $res = $stmt->execute();
                return $res;

        }

}

?>