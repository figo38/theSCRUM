<?php
	class User {

		private $login = NULL;
		private $bIsAdmin = false;
		private $rights = NULL;

		function __construct($login, $loading = false) {
			$this->login = $login;
			if ($loading == true) {
				$this->details = $this->getDetails();

				if ($this->details == false) {
					$this->register();
				} else {	
					$this->updateLoginDate();	
					$this->bIsAdmin = ($this->details['usr_is_admin'] == 1);
				}
			}
		}

		public function getLogin() { return $this->login; }

		public function isAdmin() {			
			$this->details = $this->getDetails();
			$this->bIsAdmin = ($this->details['usr_is_admin'] == 1);	
			return $this->bIsAdmin; 
		}

		private function updateLoginDate() {
			global $DB;
			$sth = $DB->prepare('UPDATE user SET usr_last_login_date = NOW() WHERE usr_login=?');
			$sth->bindParam(1, $this->login, PDO::PARAM_STR);
			return $sth->execute();
		}

		private function getDetails() {
			global $DB;
			$sth = $DB->prepare('SELECT usr_is_admin FROM user WHERE usr_login=?');
			$sth->bindParam(1, $this->login, PDO::PARAM_STR);
			return Helpers::fetchOneRow($sth);
		}

		private function register() {
			global $DB;
			$sth = $DB->prepare('INSERT INTO user (usr_login, usr_is_admin, usr_last_login_date) VALUES (?, 0, NOW())');
			$sth->bindParam(1, $this->login, PDO::PARAM_STR);
			return $sth->execute();
		}
		
		public function getRights() {
			global $DB;
			/*
			if ($this->rights != NULL) {
				return $this->rights;
			} else {*/
				$sth = $DB->prepare('SELECT u.pro_id projectid, usr_role role, p.pro_name projectname FROM project_user u, project p WHERE u.pro_id = p.pro_id AND u.usr_login=? ORDER BY usr_role');
				$sth->bindParam(1, $this->login, PDO::PARAM_STR);
				$sth->execute();
				$this->rights = $sth->fetchAll();
				return $this->rights;
			/*}*/
		}



	}
?>
