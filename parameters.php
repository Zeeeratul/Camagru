<?php
	require_once("templates/header.php");
	require_once("includes/function.inc.php");
	session_start();
	if (!check_connection())
	{
		header("Location: index.php");
	}
	else
	{
		if (isset($_GET['log_error']))
		{
			$log = htmlspecialchars($_GET['log_error']);
			echo "<p class='error'>" . $log . "</p>";
		}
	?>
		<div class="param">

			<div class="password">
				<h3>Change Password</h3>
				<form method="post" action="./includes/parameters.inc.php">
					<label for="foroldpass">Actual Password</label>
					<input id="foroldpass" type="password" name="old_passwd" required="" placeholder="Actual password" autocomplete="old_passwd">
					<label for="fornewpass1">New Password</label>
					<input id="fornewpass1" type="password" name="new_passwd1" required="" placeholder="New password" autocomplete="new_passwd1">
					<label for="fornewpass2">Confirm New Password</label>
					<input id="fornewpass2" type="password" name="new_passwd2" required="" placeholder="Confirm new password" autocomplete="new_passwd2">
					<input type="submit" name="changepasswd" value="Change Password">
				</form>
			</div>
			<div class="email">
				<h3>Change Email</h3>
				<form method="post" action="./includes/parameters.inc.php">
					<label for="foroldemail">Actual Email</label>
					<input id="foroldemail" type="email" name="old_email" required="" placeholder="Actual email">
					<label for="fornewemail1">New Email</label>
					<input id="fornewemail1" type="email" name="new_email1" required="" placeholder="New email">
					<label for="fornewemail2">Confirm New Email</label>
					<input id="fornewemail2" type="email" name="new_email2" required="" placeholder="Confirm new email">
					<input  type="submit" name="changeemail" value="Change Email">
				</form>		
			</div>
			<div class="username">
				<h3>Change Username</h3>
				<form method="post" action="./includes/parameters.inc.php">
					<label for="forolduser">Actual Username</label>
					<input id="forolduser" type="text" name="old_username" required="" placeholder="Actual username">
					<label for="fornewuser1">New Username</label>
					<input id="fornewuser1" type="text" name="new_username1" required="" placeholder="New username">
					<label for="fornewuser2">Confirm New Username</label>
					<input id="fornewuser2" type="text" name="new_username2" required="" placeholder="Confirm new username">
					<input type="submit" name="changeusername" value="Change Username">
				</form>
			</div>
			<div class="delete">
				<h3>Delete Account</h3>
				<form method="post" action="./includes/delete.inc.php">
					<label for="forconfirmpass">Password</label>
					<input id="forconfirmpass" type="password" name="password" required="" placeholder="Please Confirm Password" autocomplete="password">
					<input type="submit" name="delete_account" value="Delete">
				</form>	
			</div>
			<div class="toggle_email">
				<h3>Receive Email when comment</h3>
				<script src="email_ajax.js"></script>
				
				<label class="switch">
					<?php
						$DB = dbConnect();
						$stmt = $DB->prepare("SELECT email_bool FROM users WHERE username = ?");
						$stmt->execute([$_SESSION['user']]);
						$bool = $stmt->fetch(PDO::FETCH_ASSOC);
						if ($bool['email_bool'] == true)
							echo "<input id='myCheck' type='checkbox' onclick='myFunction()' checked=''>";
						else
							echo "<input id='myCheck' type='checkbox' onclick='myFunction()'>";
					?>
					<span class="slider round"></span>
				</label>
			</div>
		</div>

		<?php 

		require_once("templates/footer.php");

		}
