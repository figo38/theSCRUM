<?php
	/**
	  * Some helpers to generate visual controls
	  */
	class HelpersControl {

		/**
		  * Display a warning message
		  * @param $content The message to display
		  * @param $idname If you want to add an ID attribute to the HTML element. Default: no ID
		  * @param $hidden If you want to hide the message by default, se this to "true". Default: visible ("false");
		  */
		public static function warningMsg($content, $idname = NULL, $hidden = false) {
?>
	<div class="warningMsg"<?php if ($idname != NULL) {?> id="<?php echo $idname?>"<?php }; if ($hidden) {?> style="display:none"<?php } ?>>
    	<div class="inner"><?php echo $content;?></div>
    </div>
<?php		
		}

		/**
		  * Display an info message
		  * @param $content The message to display
		  * @param $idname If you want to add an ID attribute to the HTML element. Default: no ID
		  * @param $hidden If you want to hide the message by default, se this to "true". Default: visible ("false");
		  */	
		public static function infoMsg($content, $idname = NULL, $hidden = false) {
?>
	<div class="infoMsg"<?php if ($idname != NULL) {?> id="<?php echo $idname?>"<?php } ?>>
    	<div class="inner"><?php echo $content;?></div>
    </div>
<?php		
		}
	}
?>