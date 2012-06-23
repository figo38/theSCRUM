<?php
	/**
	  * Helper class to read an object from the database. Should not be instantiated directly
	  */
	abstract class PBObject {
	
		// ID of the object
		protected $id = 0;
		
		// The attributes of the object ; must be accessed with the getField() method
		private $details = NULL;
		
		protected $sqlTableName = 'TABLE';
		protected $sqlIdField = 'TABLE_ID';
		
		// The SQL statement used to read the object in the database; must be overloaded by each sub-class
		protected $readSqlStatement = 'SELECT * FROM xxx WHERE id=?';

		/**
		  * Constructor
		  * @param $id ID of the object to load
		  * @param $load If FALSE, then the object is not read from the DB (lazy loading); if TRUE, all attributes of the object are read
		  */
		public function __construct($id, $load = false) {
			$this->id = $id;
			if ($load) {
				$this->details = $this->getDetails();
				if ($this->details == false) {
					throw new Exception("Object not found");
				}				
			}			
		}

		public function getId() { return $this->details['id']; }

		protected function setDetails($attrs) { 
			$this->details = $attrs;
			$this->id = $attrs['id'];
		}

		/**
		  * Method that loads the object from the database. Called by the constructor.
		  */	
		private function getDetails() {
			global $DB;
			$sth = $DB->prepare($this->readSqlStatement);
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			return Helpers::fetchOneRow($sth);
		}
		
		protected function getField($fieldname, $defaultValue = NULL) {
			if (isset($this->details[$fieldname])) {
				return $this->details[$fieldname];
			} else {
				return $defaultValue;
			}
		}

		protected function setField($fieldname, $value) {
			$this->details[$fieldname] = $value;
		}
		
		/**
		  * The field update functions should only update 1 row when called. 
		  * This function is called by each of the update functions to check that it is true.
		  * If it's not, it logs an error in the web server logs
		  * @param $sth The prepared statement to execute
		  * @return The number of rows affected by the prepared statement
		  */
		private function updateField($sth) {
			$nbRows = Helpers::executeUpdateStatement($sth);
			if ($nbRows != 1) {
				error_log('Trouble when updating field <' . $fieldname . '> for record <' . $this->sqlIdField . '=' . $this->id . '>: ' . $nbRows . ' row(s) have been updated instead of one');
			}
			return $nbRows;			
		}
		
		/**
		  * Update a field of type String in the DB
		  * @param $fieldname Field name to be updated
		  * @param $value Value to set
		  * @return The number of rows affected by the prepared statement
		  */
		protected function updateStringField($fieldname, $value) {
			global $DB;
			$sth = $DB->prepare('UPDATE ' . $this->sqlTableName . ' SET ' . $fieldname . '=? WHERE ' . $this->sqlIdField . '=?');
			$sth->bindParam(1, $value, PDO::PARAM_STR);
			$sth->bindParam(2, $this->id, PDO::PARAM_INT);
			return $this->updateField($sth);
		}		

		/**
		  * Update a field of type Int in the DB
		  * @param $fieldname Field name to be updated
		  * @param $value Value to set
		  * @return The number of rows affected by the prepared statement
		  */
		protected function updateIntField($fieldname, $value) {
			global $DB;
			$sth = $DB->prepare('UPDATE ' . $this->sqlTableName . ' SET ' . $fieldname . '=? WHERE ' . $this->sqlIdField . '=?');
			$sth->bindParam(1, $value, PDO::PARAM_INT);
			$sth->bindParam(2, $this->id, PDO::PARAM_INT);
			return $this->updateField($sth);
		}		

		/**
		  * Update a field of type Date in the DB
		  * @param $fieldname Field name to be updated
		  * @param $value Value to set
		  * @return The number of rows affected by the prepared statement
		  */
		protected function updateDateField($fieldname, $value) {
			global $DB;
			$sth = $DB->prepare('UPDATE ' . $this->sqlTableName . ' SET ' . $fieldname . "=STR_TO_DATE(?,'" . DATE_FORMAT . "') WHERE " . $this->sqlIdField . '=?');
			$sth->bindParam(1, $value, PDO::PARAM_STR);
			$sth->bindParam(2, $this->id, PDO::PARAM_INT);
			return $this->updateField($sth);
		}
		
		public function delete() {
			global $DB;
			$sth = $DB->prepare('DELETE FROM ' . $this->sqlTableName . ' WHERE ' . $this->sqlIdField . '=?');
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			return Helpers::executeStatement($sth);	
		}		
	}
?>