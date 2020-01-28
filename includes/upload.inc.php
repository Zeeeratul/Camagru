<?php
	session_start();
	require_once("./function.inc.php");

	if (!check_connection())
	{
		header("Location: ../index.php");
	}
	else
	{
		if (isset($_POST["Submit"]) && isset($_POST['cadre']))
		{
			if (!file_exists("../ressources/img"))
			{
				var_dump(mkdir("../ressources/img", 0777));
			}
			if (isset($_POST['userUpload']))
			{
				postImgPhoto($_POST['userUpload'], $_POST['cadre']);
				header("Location: ../capture.php?log_error=Photo uploaded");
			}
			else if (isset($_FILES['File']) && !empty($_FILES['File']['name']))
			{
				$flag = check_img($_FILES);
				if ($flag == "png")
					$name = ".png";
				else if ($flag == "jpg")
					$name = ".jpg";
				else if ($flag == "gif")
					$name = ".gif";
				else
				{
					header("Location: ../capture.php?log_error=" . $flag);
					return ;
				}
				$name = uniqid() . $name;
				move_uploaded_file($_FILES["File"]["tmp_name"], "../ressources/img/" . $name);
				if ($flag == "png")
					$base = imagecreatefrompng("../ressources/img/" . $name);	
				else if ($flag == "jpg")
					$base = imagecreatefromjpeg("../ressources/img/" . $name);	
				else if ($flag == "gif")
					$base = imagecreatefromgif("../img/" . $name);	
				$cadre = imagecreatefrompng("../ressources/cadre/" . $_POST['cadre']);
				unlink("../img/" . $name);
				imagecopymerge_alpha($base, $cadre, 0, 0, 0, 0, imagesx($cadre), imagesy($cadre), 100);
				imagejpeg($base, "../ressources/img/" . $name);
				put_image_database($name, $_SESSION['user']);
				header("Location: ../capture.php?log_error=Photo uploaded");
			}
			else
			{
				header("Location: ../capture.php?log_error=Take a photo or upload one");
			}
		}
		else
		{
			header("Location: ../capture.php?log_error=Take a photo or upload one");
		}
	}

?>