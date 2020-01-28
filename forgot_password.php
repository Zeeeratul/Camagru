<?php
	require_once("./templates/header.php");
	if (isset($_GET['log_error']))
	{
		$log = htmlspecialchars($_GET['log_error']);
		echo "<p class='error'>" . $log . "</p>";
	}
?>
	<h1>Forgot password</h1>
	
	<div class="forgot_password">
		<form method="post" action="./includes/forgot_password.inc.php">
			<label for="foremail">Email</label>
			<input id="foremail" type="email" name="email" required="" placeholder="Your email">
			<input type="submit" name="forgot" value="Send email">
		</form>
	</div>
<?php
	include_once("./templates/footer.php");
