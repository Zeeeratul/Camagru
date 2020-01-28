<?php
	require_once("function.inc.php");
	session_start();

	if (!check_connection())
	{
		header("Location: ../index.php");
	}
	else
	{	
		if ($_POST['delete_account'] === "Delete")
		{
			$hash_pass = hash('whirlpool', $_POST['password']);
			$DB = dbConnect();
			$stmt = $DB->prepare("SELECT password FROM users WHERE id = ?");
			$stmt->execute([$_SESSION['id']]);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($hash_pass !== $result['password'])
			{
				header("Location: ../parameters.php?log_error=Wrong password");
			}
			else
			{
				$DB = dbConnect();
				$stmt = $DB->prepare("DELETE FROM users WHERE id = ?");
				$stmt->execute([$_SESSION['id']]);
				header("Location: logout.inc.php");
			}
		}
		else if ($_POST['delete_image'] === "Delete" && isset($_GET['id']))
		{
			$DB = dbConnect();
			$stmt = $DB->prepare("SELECT name FROM images WHERE id = ?");
			$stmt->execute([$_GET['id']]);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$image_name = $result['name'];

			$DB = dbConnect();
			$stmt = $DB->prepare("DELETE FROM images WHERE id = ?");
			$stmt->execute([$_GET['id']]);
			unlink("../img/" . $image_name);
			header("Location: ../index.php?log_error=Image deleted");
		}
		else if ($_POST['delete_comment'] === "Delete" && isset($_GET['id_comment']) && isset($_GET['id']))
		{
			$DB = dbConnect();
			$stmt = $DB->prepare("DELETE FROM comments WHERE id_comment = ?");
			$stmt->execute([$_GET['id_comment']]);
			header("Location: ../image.php?id=" . $_GET['id']);
		}
		else
		{
			header("Location: ../index.php");
		}

	}

?>