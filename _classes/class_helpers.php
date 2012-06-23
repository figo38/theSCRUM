<?php
	/**
	  * Some wrappers for PDO in order to optimize code writing
	  */
	class Helpers {
	
		/**
		  * Log any SQL error in the webserver error log
		  * @param $sth PDO Statement
		  */
		public static function logError($sth) {
			$res = $sth->errorInfo();
			error_log('SQLSTATE ' . $res[0] . ' - ' . $res[2]);
		}

		/**
		  * Execute a SQL statement that doesn't take any parameters, and fetch all results
		  * @param $sql SQL statement
		  */
		public static function execute($sql) {
			global $DB;
			$sth = $DB->prepare($sql);
			return Helpers::fetchAll($sth);	
		}
		
		/**
		  * Execute a PDO statement, log SQL error if any
		  * @param $sth PDO Statement
		  */
		public static function executeStatement($sth) {
			if ($sth->execute()) {
				return true;
			} else {
				Helpers::logError($sth);
				return false;
			}
		}

		/**
		  * Execute a PDO statement, and fetch all results
		  * @param $sth PDO Statement
		  */
		public static function fetchAll($sth) {
			if (Helpers::executeStatement($sth)) {
				return $sth->fetchAll();
			} else {
				return NULL;
			}
		}
		
		public static function executeInsertStatement($sth) {
			// TODO: transaction management?
			global $DB;
			return Helpers::executeStatement($sth) ? $DB->lastInsertId() : 0;			
		}
		
		public static function fetchOneRow($sth) {
			if (Helpers::executeStatement($sth)) {
				return $sth->fetch(PDO::FETCH_ASSOC);
			} else {
				return NULL;
			}
		}
	}
?>