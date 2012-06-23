<?php
	class DbuserDisplay extends Dbuser {

		private $displaynone = false;

		public function __construct($dbuser) {
			if (is_array($dbuser)) {
				$this->setDetails($dbuser);
			} else {
				parent::__construct($dbuser, true);
			}
		}
		
		public function setDisplayNone($flag) { $this->displaynone = $flag; }

		public function render() {
?>
<tr id="userrow-<?php echo $this->getId()?>">
	<td><span id="user-login-<?php echo $this->getId()?>"><?php echo $this->getId()?></span></td>
	<td><input type="checkbox" <?php if ($this->isAdmin()) { ?>checked="checked"<?php } ?>></td>
	<td><?php echo $this->getLastLoginDate();?></td>
    <td>
    	<?php echo img('pencil.png', 'Show user details', 'details-' . $id)?>
    	<?php echo img('delete.png', 'Delete the user', 'user-delete-' . $id)?>    
    </td>
</tr>		
<?php				
		}
	}
?>