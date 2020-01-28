<?php
	require_once("templates/header.php");
	if (isset($_GET['log_error']))
	{
		$log = htmlspecialchars($_GET['log_error']);
		echo "<p class='error'>" . $log . "</p>";
	}
?>
	<h1>Create Account</h1>

	<div class="create_account">	
		<form method="post" action="./includes/create.inc.php">
			<label for="foremail">Email</label>
			<input id="foremail" type="email" name="email" placeholder="Your email" required="" autocomplete="email">

			<label for="forusername">Username</label>
			<input id="forusername" type="text" name="username" placeholder="Your username" required="" autocomplete="username">

			<label for="passwd1">Password</label>
			<input id="passwd1" type="password" name="passwd" placeholder="Your password" required="" autocomplete="password">

			<label for="passwd2">Confirm your password</label>
			<input id="passwd2" type="password" name="passwd2" placeholder="Confirm password" required="" autocomplete="password">

			<input type="submit" name="create" value="Create">	
		</form>
	</div>
<?php
	require_once("templates/footer.php");
?>