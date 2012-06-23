<?php
	class UserManagement {

		private $userlogin = NULL;

		function __construct() {
			if (!isset($_SESSION['_auth_user'])) {
				if (isset($_SERVER["PHP_AUTH_USER"])) {
					$this->userlogin = $_SERVER["PHP_AUTH_USER"];
					$U = new User($this->userlogin, true);
					unset($_SESSION['_auth_user']);
					$_SESSION['_auth_user'] = $U;
				}
			}		
		}

		public function getUserLogin() {
			if (isset($_SESSION['_auth_user'])) {
				$U = $_SESSION['_auth_user'];
				return $U->getLogin();
			} else {
				return 'Unknown';
			}
		}
		
		public function isAdmin() {
			if (isset($_SESSION['_auth_user'])) {
				$U = $_SESSION['_auth_user'];
				return $U->isAdmin();
			} else {
				return false;
			}
			return true;
		}

		public function getRights() {
			if (isset($_SESSION['_auth_user'])) {
				$U = $_SESSION['_auth_user'];
				return $U->getRights();
			} else {
				return false;
			}
		}

		private function hasRight($role) {
			$rights = $this->getRights();
			if ($rights == false) {
				return false;
			} else {
				$ret = false;
				foreach ($this->getRights() as $key => $right) {
					if ($right['role'] == $role) {
						$ret = true;
					}
				}
				return $ret;
			}
		}

		public function isProductOwner() { return $this->hasRight('P'); }
		public function isScrumMaster() { return $this->hasRight('S'); }
		
		private function hasRightOf($role, $projectId) {
			$rights = $this->getRights();
			if ($rights == false) {
				return false;
			} else {
				$ret = false;
				foreach ($this->getRights() as $key => $right) {
					if ($right['role'] == $role && $right['projectid'] == $projectId) {
						$ret = true;
					}
				}
				return $ret;
			}
		}
		
		public function isProductOwnerOf($projectId) { return $this->hasRightOf('P', $projectId); }
		public function isScrumMasterOf($projectId) { return $this->hasRightOf('S', $projectId); }
		public function isTeamMemberOf($projectId) { return $this->hasRightOf('T', $projectId); }

	}
?>