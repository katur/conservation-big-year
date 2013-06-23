<?php get_header(); 
	include("katherine_connect.php");
	
	/*
	Template Name: Checklist
	Copyright (c) 2013 Katherine Erickson
	*/

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
	$total_seen = mysql_numrows($result);
?>
<div class="site-content full-width">
	<h1 id="species-title">Photo Checklist</h1>
	<h2 class="species-subtitle">
		<?php echo $total_seen; ?> species seen so far!!
	</h2>

	<div id="photo-checklist">
		<h2>Bjrds</h2>
		<?php
			while ($row = mysql_fetch_assoc($result)) {
				$common_name = $row["common_name"];
				$seen_this_year = $row["seen_this_year"];
				$is_lifer = $row["is_lifer"];
				$url_common_name = $row["url_common_name"];
				$date = $row["date"];
				$state = $row["state"];
				$flickr_code = $row["flickr_code"];	

				echo "<div class='photo-and-caption'>";
					if ($flickr_code)
						echo "$flickr_code";
					else
						echo "<img src='http://placekitten.com/500/333' width='500px' />";

					echo "<div class='photo-caption'>";
						
						echo "
							<a href='/species/?common_name=$url_common_name'>$common_name</a>
						";
							
						echo "<span>";
						echo "&#x2713;";
						if ($date)
							echo "$date ";
						if ($state)
							echo "$state ";
						if ($is_lifer)
							echo "<b>LIFER!</b> ";
						
						echo "</span>";
					
						// if logged in, show links to edit
						//if ($_SESSION["logged_in"]) {
						//	echo "<a href = '/edit/$url_common_name'>Edit</a>";
						//}
						
					echo "</div></div>";
				}
			?>
	</div>
</div>
<?php get_footer(); ?>
