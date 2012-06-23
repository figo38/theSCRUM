<?php
	class ProductBacklogHelpers {
		function __construct() {
		}







                public function deleteLinkRelease($storyId) {
                        global $DB;
                        global $USERAUTH;
                        $sth = $DB->prepare('UPDATE story SET rel_id=NULL where sto_id=?');
                        $sth->bindParam(1, $storyId, PDO::PARAM_STR);

                        $sth->execute();
                        return $storyId;
                }	
	}
?>