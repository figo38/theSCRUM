<?php
	class Dbuser extends PBObject {
		
		protected $sqlTableName = 'user';
		protected $sqlIdField = 'usr_login';
	
		public function getId() { return $this->id;}
		public function isAdmin() { return $this->getField('is_admin') == 1;}
		public function getLastLoginDate() { return HelpersDate::getFormattedFullDateTime($this->getField('last_login_date'));}
		
		public static function getAllUsers($sortby = 'login') {
			global $DB;
			$sth = $DB->prepare('SELECT usr_login id, usr_is_admin is_admin, UNIX_TIMESTAMP(usr_last_login_date) last_login_date FROM user ORDER BY usr_' . $sortby);
			return Helpers::fetchAll($sth);			
		}		
	}
?>