
<?php	
	require_once("templates/header.php");
	require_once("includes/function.inc.php");
	if (isset($_GET['log_error']))
	{
		$log = htmlspecialchars($_GET['log_error']);
		echo "<p class='error'>" . $log . "</p>";
	}
?>
		<h1>Galery</h1>

		<div class="galery">
		<?php
			$array_id = getImagesId();

			if (isset($_GET['page']))
			{
				$start = ($_GET['page'] - 1) * 12;
				$end = $start + 12;
				if (is_numeric($start))
				{
					while ($start < $end && $array_id[$start])
					{
						$image = getImage($array_id[$start]);
						echo "<div class='photo'>";
							echo "<a href='image.php?id=" . $array_id[$start] . "'>";
							echo "<img src='ressources/img/" . $image['name'] . "'>";
							echo "</a>";
						echo "</div>";
						$start++;
					}
				}
			}
			else
			{
				$start = 0;
				while ($start < 12 && $array_id[$start])
				{
					$image = getImage($array_id[$start]);
					echo "<div class='photo'>";
						echo "<a href='image.php?id=" . $array_id[$start] . "'>";
						echo "<img src='ressources/img/" . $image['name'] . "'>";
						echo "</a>";
					echo "</div>";
					$start++;
				}
			}

		?>

		</div>



		<div class="pagination">
		<?php

			$nb_page = ceil(count($array_id) / 12);
			for ($page = 1; $page <= $nb_page; $page++)
			{ 
				echo "<a href='index.php?page=" . $page . "'>" . $page . " </a>";
			}
		?>
		</div>
		
<?php
	require_once("templates/footer.php");
?>