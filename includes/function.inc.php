<?php
	session_start();
	require_once("/var/www/html/camagru/config/database.php");

    function send_email($to, $subject, $message)
    {
        $from = "celestdelahaye@gmail.com";
        $headers = "From:" . $from;
        mail($to,$subject,$message,$headers);
    }

    function check_connection()
    {
    	if (isset($_SESSION['id']) && $_SESSION['user'] !== "" && $_SESSION['id'] !== "" && $_SESSION['email'] !== "")
    	{
    		$DB = dbConnect();
    		$stmt = $DB->prepare("SELECT id, username, email FROM users WHERE id = ?");
    		$stmt->execute([$_SESSION['id']]);
    		$result = $stmt->fetch(PDO::FETCH_ASSOC);
    		if ($result['id'] !== $_SESSION['id'] || $result['email'] !== $_SESSION['email'] || $result['username'] !== $_SESSION['user'])
    			return false;
    		else
    			return true;
    	}
    	else
    		return false;
    }

    function valid_format_create($passwd, $passwd2, $enail, $username)
	{
		if ($passwd !== $passwd2)
		{
			$error = "Passwords are different";
		}
		else if (filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$error = "Not good email format";
		}
		else if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{7,49}$/', $passwd))
		{
			$error = "Password need at least 1 number, 1 uppercase, 1 lowercase and at least 8 char";
		}
		else if (strlen($username) < 6)
		{
			$error = "Username need to be at least 6 char";
		}
		else
		{
			$error = TRUE;
		}
		return $error;
	}

	function already_use_email($email)
	{
		$DB = dbConnect();	
		$stmt = $DB->prepare("SELECT email FROM users WHERE email = ?");
		$stmt->execute([$email]);
		$row = $stmt->fetch();
		if ($row) 
			$bool = TRUE;
		else
			$bool = FALSE;
		return $bool;
	}	

	function already_use_username($username)
	{
		$DB = dbConnect();	
		$stmt = $DB->prepare("SELECT username FROM users WHERE username = ?");
		$stmt->execute([$username]);
		$row = $stmt->fetch();
		if ($row) 
			$bool = TRUE;
		else
			$bool = FALSE;
		return $bool;
	}

	function create_account($password, $email, $username)
	{
		$DB = dbConnect();
		$req = $DB->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");
		$req->bindParam(':email', $email);
		$req->bindParam(':username', $username);
		$req->bindParam(':password', $password);
		$req->execute();
	}

	function checking_login($login, $password)
	{
		$DB = dbConnect();
		$stmt = $DB->prepare("SELECT id, password, email, username, validation FROM users WHERE email = ? OR username = ?");
		$stmt->execute(array($login, $login));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result)
		{
			if ($result['password'] !== $password)
				$error = "Wrong password";
			else
			{
				if ($result['validation'])
					$error = "Log success";
				else
					$error = "Please check your email for validate your account";
			}
		}
		else
			$error = "Unknown login";
		if ($error === "Log success")
		{
			$return = array('validation' => $error, 'email' => $result['email'], 'username' => $result['username'], 'id' => $result['id']);
		}
		else
		{
			$return = array('validation' => $error);
		}
		return $return;
	}

	function generate_unique_url($username)
	{
		$DB = dbConnect();
		$token = sha1(uniqid($username, true));
		$stmt = $DB->prepare("INSERT INTO pending_users (username, token, tstamp) VALUES (?, ?, ?)");
		$stmt->execute(array($username, $token, $_SERVER["REQUEST_TIME"]));
		return $token;
	}

 	function validate_email($token)
 	{
		$DB = dbConnect();
		$stmt = $DB->prepare("SELECT username FROM pending_users WHERE token = ?");
		$stmt->execute([$token]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result)
		{
			$DB = dbConnect();
			$stmt = $DB->prepare("UPDATE users SET validation = 1 WHERE username = ?");
			$stmt->execute([$result['username']]);
			$DB = dbConnect();
			$stmt = $DB->prepare("DELETE FROM pending_users WHERE username = ?");
			$stmt->execute([$result['username']]);
			$DB = dbConnect(); 
			$stmt = $DB->prepare("SELECT email, password FROM users WHERE username = ?");
			$stmt->execute([$result['username']]);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$password = $result['password'];
			$email = $result['email'];
			checking_login($email, $password);
			$error = "email_validate";
		}
		else
			$error = "expire_token";
		return $error;
 	}

 	function check_token($token)
 	{
		$DB = dbConnect();
		$stmt = $DB->prepare("SELECT username FROM pending_users WHERE token = ?");
		$stmt->execute([$token]);
		$array = $stmt->fetch(PDO::FETCH_ASSOC);
		return $array['username'];
 	}

	function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
	{
		$cut = imagecreatetruecolor($src_w, $src_h); 
		imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h); 
		imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h); 
		imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct); 
	}

 	function put_image_database($name, $user)
 	{
 		$DB = dbConnect();
 		$stmt = $DB->prepare("INSERT INTO images (name, username) VALUES (?, ?)");
 		$stmt->execute(array($name, $user));
 	}

	function postImgPhoto($img_str, $cadre)
	{
		$base = imagecreatefrompng($img_str);
		$cadre = imagecreatefrompng("../ressources/cadre/" . $cadre);
		imagecopymerge_alpha($base, $cadre, 0, 0, 0, 0, imagesx($cadre), imagesy($cadre), 100);
		$name = uniqid() . '.png';
		imagepng($base, '../ressources/img/' . $name);
		put_image_database($name, $_SESSION['user']);
	}

	function check_img($file)
	{
		$imageFileType = strtolower(pathinfo($file['File']['name'], PATHINFO_EXTENSION));
		if ($file["File"]["size"] > 2000000) 
		{
			$error = "Image size is bigger than 2mo";
		}
		else if ($imageFileType === "png") 
		{
			if (!$img = @imagecreatefrompng($file["File"]["tmp_name"])) 
				$error = "Bad png";
			else
				$error = "png";
		} 
		else if ($imageFileType === "jpg" || $imageFileType === "jpeg") 
		{
			if (!$img = @imagecreatefromjpeg($file["File"]["tmp_name"])) 
				$error = "Bad pjg";
			else
				$error = "jpg";
		}
		else if ($imageFileType === "gif") 
		{
			if (!$img = @imagecreatefromgif($file["File"]["tmp_name"])) 
				$error = "Bad gif";
			else
				$error = "gif";
		} 
		else 
			$error = "Format supported jpg, jpeg, png, gif";
		return $error;
	}

	function getImagesId()
	{
		$DB = dbConnect();
		$stmt = $DB->prepare("SELECT MAX(ID) FROM images");
		$stmt->execute();
		$i = $stmt->fetch();
		$i = $i[0];
		$array_id = array();
		while ($i > 0)
		{		
			$DB = dbConnect();
			$stmt = $DB->prepare("SELECT id FROM images WHERE id = ?");
			$stmt->execute([$i]);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($result)
				array_push($array_id, $result['id']);
			$i--;
		}
		return $array_id;
	}

	function getImagesIdUser($username, $limit)
	{
		$DB = dbConnect();
		$stmt = $DB->prepare("SELECT MAX(ID) FROM images");
		$stmt->execute();
		$i = $stmt->fetch();
		$i = $i[0];
		$array_id = array();
		while ($i > 0 && $limit > 0)
		{		
			$DB = dbConnect();
			$stmt = $DB->prepare("SELECT id FROM images WHERE id = ? AND username = ?");
			$stmt->execute([$i, $username]);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($result)
			{	
				$limit--;
				array_push($array_id, $result['id']);
			}
			$i--;
			if ($i == 0)
				$limit = 0;
		}
		return $array_id;
	}

	function getImage($id)
	{
		$DB = dbConnect();
		$stmt = $DB->prepare("SELECT name, username, creation_date FROM images WHERE id = ?");
		$stmt->execute([$id]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	function getComment()
	{
		$DB = dbConnect();
		$stmt = $DB->prepare("SELECT id_comment, id_user, comment, creation_date FROM comments WHERE id_image = ?");
		$stmt->execute([$_GET['id']]);
		$comment = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$i = 0;
		while ($comment[$i])
		{
			$DB = dbConnect();
			$stmt = $DB->prepare("SELECT username FROM users WHERE id = ?");
			$stmt->execute([$comment[$i]['id_user']]);
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			echo "<div class='comment'>";
			echo "<p class='info'>" . htmlspecialchars($user['username']) . " at: " . $comment[$i]['creation_date'] . "</p>";
			echo "<p class='content_comment'>" . $comment[$i]['comment'] . "</p>";			
			if (check_connection() && $_SESSION['user'] == $user['username'])
			{
				echo "<form method='post' action='includes/delete.inc.php?id_comment=" . $comment[$i]['id_comment'] . "&id=" . $_GET['id'] . "'>";
				echo "<input type='submit' name='delete_comment' value='Delete'>";
				echo "</form>";
			}
			echo "</div>";
			$i++;
		}
	}

	function check_like($id_image)
	{
		$DB = dbConnect();
		$stmt = $DB->prepare("SELECT count(id) FROM likes WHERE id_user = ? AND id_image = ?");
		$stmt->execute([$_SESSION['id'], $id_image]);
		$count = $stmt->fetch(PDO::FETCH_ASSOC);
		$count_like = (int)$count['count(id)'];
		if ($count_like === 0)
			return false;
		else
			return true;
	}

	function total_like($id_image)
	{
		$DB = dbConnect();
		$stmt = $DB->prepare("SELECT count(id) FROM likes WHERE id_image = ?");
		$stmt->execute([$id_image]);
		$count = $stmt->fetch(PDO::FETCH_ASSOC);
		$count_like = (int)$count['count(id)'];
		return $count_like;
	}

?>