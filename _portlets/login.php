<?php 
	include '_include/header.php'; 
	include '_include/intropageHeader.php';
?>
		<div id="loginForm">      
			<form method="post" action="/login" id="loginForm">
			<p>
				<label for="loginFormLogin">Username:</label>
				<input type="text" name="loginFormLogin" id="loginFormLogin" value=""/>
			</p>
			<p>
				<label for="loginFormPassword">Password:</label> 
				<input type="password" name="loginFormPassword" id="loginFormPassword" value=""/>
				<button type="submit">Log in</button>
			</p>
			</form>        
		</div>
<?php
	include '_include/intropageFooter.php';
?>