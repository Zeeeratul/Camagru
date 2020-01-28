<?php
	require_once("templates/header.php");
	require_once("includes/function.inc.php");

	if (isset($_GET['id']))
	{
		if (isset($_GET['log_error']))
		{
			$log = htmlspecialchars($_GET['log_error']);
			echo "<p class='error'>" . $log . "</p>";
		}
		$image = getImage($_GET['id']);
		if (!$image)
		{	
			header("Location: index.php?log_error=No image found");			
		}
		else
		{
			echo "<div class='photo_section'>";
			echo "<a href='includes/add.inc.php?like=true&id=" . $_GET['id'] . "'>";
			echo "<img id='main_photo' src='ressources/img/" . $image['name'] . "'><br>";
			echo "</a>";
			if (check_like($_GET['id']))
				echo "<a href='includes/add.inc.php?like=true&id=" . $_GET['id'] . "'>Dislike</a>";
			else
				echo "<a href='includes/add.inc.php?like=true&id=" . $_GET['id'] . "'>Like</a>";
			echo "<p id='total_like'>Total Likes: " . total_like($_GET['id']) . "</p>";
			echo "<p>Uploaded by: " . htmlspecialchars($image['username']) . " at " . $image['creation_date'] . "</p>";
			if (check_connection() && $_SESSION['user'] === $image['username'])
			{
				echo "<form method='post' action='includes/delete.inc.php?id=" . $_GET['id'] . "'>";
				echo "<input type='submit' name='delete_image' value='Delete'>";
				echo "</form>";
			}
			echo "</div>";

			echo "<div class='leave_comment'>";
			echo "<h3>Leave a comment</h3>";
			?>
			<script type="text/javascript">
				function disabledbutton(id)
				{
					var button = document.getElementById(id);
					var comment = document.getElementById('comment_text');
					if (comment.value.length != 0)
					{
						button.style.display = "none";
					}
				}
			</script>
			<?php
				echo "<form method='post' action='./includes/add.inc.php?id=" . $_GET['id'] . "'>";
					echo "<input id='comment_text' type='text' name='comment_text' placeholder='Comment...' required=''>";
					echo "<input id='comment_button' type='submit' name='comment' value='Comment' onclick='disabledbutton(this.id)'>";
				echo "</form>";
			echo "</div>";

			echo "<div class='comment_section'>";
				getComment();
			echo "</div>";
		}
	}
	else
	{
		header("Location: index.php");
	}

?>

<?php
	require_once("templates/footer.php");
?>