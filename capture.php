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

	<h1>Take your photo</h1>

<div class="capture">
		<div class="main_capture">
			<script src="capture.js"></script>
			<script type="text/javascript">
			function myFunction(id)
			{
				var radio = document.getElementById(id);
				if (radio.checked == true)
				{
					var side  = document.getElementById("startbutton");
					side.style.display = "inline";
				}	
			}
			</script>
			<video id="video">Video stream not available.</video>
			<br>
			<button id="startbutton">Take photo</button>

			<form action="includes/upload.inc.php" method="post" enctype="multipart/form-data">
				<div id="photos"></div>			
				<input id='save_image' type="submit" value="Upload" name="Submit">
				<br>
				<label for="zelda"><img src="ressources/cadre/zelda_cartoon.png"></label>
				<input type="radio" id="zelda" name="cadre" value="zelda_cartoon.png" onclick="myFunction(this.id)">
				<label for="cadre"><img src="ressources/cadre/cadre_noel.png"></label>
				<input type="radio" id="cadre" name="cadre" value="cadre_noel.png" onclick="myFunction(this.id)">		
				<label for="troll"><img src="ressources/cadre/troll.png"></label>
				<input type="radio" id="troll" name="cadre" value="troll.png" onclick="myFunction(this.id)">
				<label for="pokeball"><img src="ressources/cadre/pokeball.png"></label>
				<input type="radio" id="pokeball" name="cadre" value="pokeball.png" onclick="myFunction(this.id)">
				<label for="endive"><img src="ressources/cadre/endive.png"></label>
				<input type="radio" id="endive" name="cadre" value="endive.png" onclick="myFunction(this.id)">		
				<label for="ksos"><img src="ressources/cadre/ksos.png"></label>
				<input type="radio" id="ksos" name="cadre" value="ksos.png" onclick="myFunction(this.id)">		
				<label for="ruquier"><img src="ressources/cadre/ruquier.png"></label>
				<input type="radio" id="ruquier" name="cadre" value="ruquier.png" onclick="myFunction(this.id)">
				<label for="zemmour"><img src="ressources/cadre/zemmour.png"></label>
				<input type="radio" id="zemmour" name="cadre" value="zemmour.png" onclick="myFunction(this.id)">		
				<label for="interville"><img src="ressources/cadre/interville.png"></label>
				<input type="radio" id="interville" name="cadre" value="interville.png" onclick="myFunction(this.id)">	
				<label for="trump"><img src="ressources/cadre/trump.png"></label>
				<input type="radio" id="trump" name="cadre" value="trump.png" onclick="myFunction(this.id)">
				<br>
				<label for="file_choose">Upload your photo</label>
				<input id="file_choose" type="file" name="File">
			</form>

		<canvas id="canvas"></canvas>

		</div>
		<div class="side_capture">
	 		<?php
	 			$i = 0;
	 			$id = getImagesIdUser($_SESSION['user'], 15);
	 			while ($id[$i])
	 			{
	 				$image = getImage($id[$i]);
	 				echo "<a href='image.php?id=" . $id[$i] . "'>";
					echo "<img src='ressources/img/" . $image['name'] . "'>";
					echo "</a>";
					$i++;
	 			}
	 		?>
	 	</div>
</div>
<?php
	
	require_once("templates/footer.php");
	}


		
?>



