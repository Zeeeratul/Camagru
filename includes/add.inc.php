<?php
	require_once("./function.inc.php");

	session_start();

	if (!check_connection())
	{
		if (isset($_GET['id']))
		{
			header("Location: ../image.php?id=" . $_GET['id'] . "&log_error=Please login to like and comment");
		}
		else
		{
			header("Location: ../index.php");
		}
	}
	else
	{
		if (!empty($_POST['comment_text']) && ($_POST['comment'] === "Comment") && isset($_GET['id']))
		{
			$comment = htmlspecialchars($_POST['comment_text']);
			if (strlen($comment) > 2000)
			{
				header("Location: ../image.php?log_error=Comment are 2000 char max&id=" . $_GET['id']);
			}
			else
			{
				$DB = dbConnect();
				$stmt = $DB->prepare("INSERT INTO comments (id_user, id_image, comment) VALUES (?, ?, ?)");
				$stmt->execute([$_SESSION['id'], $_GET['id'], $comment]);

				$DB = dbConnect();
				$stmt = $DB->prepare("SELECT username FROM images WHERE id = ?");
				$stmt->execute([$_GET['id']]);
				$username = $stmt->fetch(PDO::FETCH_ASSOC);

				$DB = dbConnect();
				$stmt = $DB->prepare("SELECT email, email_bool FROM users WHERE username = ?");
				$stmt->execute([$username['username']]);
				$result = $stmt->fetch(PDO::FETCH_ASSOC);

				header("Location: ../image.php?log_error=Success&id=" . $_GET['id']);
						if ($result['email_bool'] == 1)
				{
					send_email($result['email'], $_SESSION['user'] . " just comment your photo", $_SESSION['user'] . " just comment your photo check it out: https://celestindelahaye.ddns.net/camagru/image.php?id=" . $_GET['id']);
				}	
			 }

		}
		else if (isset($_GET['like']) && isset($_GET['id']))
		{
			if ($_GET['like'] == "true")
			{
				if (check_like($_GET['id']))
				{
					$DB = dbConnect();
					$stmt = $DB->prepare("DELETE FROM likes WHERE id_user = ? AND id_image = ?");
					$stmt->execute([$_SESSION['id'], $_GET['id']]);
					header("Location: ../image.php?id=" . $_GET['id']);
				}
				else
				{
					$DB = dbConnect();
					$stmt = $DB->prepare("INSERT INTO likes (id_user, id_image) VALUES (?, ?)");
					$stmt->execute([$_SESSION['id'], $_GET['id']]);
					header("Location: ../image.php?id=" . $_GET['id']);
				}
			}
			else
			{
				header("Location: ../index.php");
			}
		}
		else
		{
			header("Location: ../index.php");
		}
	}


?>