<?php
	include("katherine_connect.php");
	/*
	Template Name: Checklist
	Copyright (c) 2013 Katherine Erickson
	*/
	get_header(); 
?>

<h1 id="species-title">Photo Checklist</h1>

<?php
	$query = "
		SELECT common_name, seen_this_year,
		is_lifer, url_common_name,
		date, state,
		flickr_code
		FROM species_list
		LEFT JOIN sightings
		ON species_list.id = sightings.species_id
		WHERE seen_this_year = '1'
		ORDER BY species_list.id	
	";

	$result = mysql_query($query) or die(mysql_error());
?>

<div class="species-section">
	<div class="photo-checklist">
		<?php
			$counter = 0;
			while ($row = mysql_fetch_assoc($result)) {
				$common_name = $row["common_name"];
				$seen_this_year = $row["seen_this_year"];
				$is_lifer = $row["is_lifer"];
				$url_common_name = $row["url_common_name"];
				$date = $row["date"];
				$state = $row["state"];
				$flickr_code = $row["flickr_code"];	

				if ($flickr_code)
					echo "$flickr_code";
				
				echo "<span>";
				
				echo "<a href='/species/?common_name=$url_common_name'>$common_name</a>";
					
				$counter += 1;
				echo "&#x2713;";

				if ($date)
					echo "$date ";
				if ($state)
					echo "$state ";
				if ($is_lifer)
					echo "<b>LIFER!</b> ";
				echo "</span>";
				
				// if logged in, show links to edit
				if ($_SESSION["logged_in"]) {
					echo "<a href = '/edit/$url_common_name'>Edit</a>";
				}
			}
			echo "<span>$counter total species seen!</span>";
		?>
	</div>
</div>
