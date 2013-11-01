<?php get_header();
	include("katherine_connect.php");

	/*
	Template Name: Photo Checklist
	Copyright (c) 2013 Katherine Erickson
	*/

	$family_query = "
		SELECT family_common, COUNT(*) as family_count
		FROM species_list
		WHERE seen_this_year='1'
		GROUP BY family_common
		ORDER BY species_list.id
	";
	$family_result = mysql_query($family_query) or die(mysql_error());

	$species_query = "
		SELECT common_name, date, state,
		is_lifer, url_common_name,
		flickr_src, flickr_width, flickr_height
		FROM species_list
		LEFT JOIN sighting
		ON species_list.id = sighting.species_id
		WHERE seen_this_year = '1'
		ORDER BY species_list.id
	";

	$species_result = mysql_query($species_query) or die(mysql_error());
	$total_seen = mysql_numrows($species_result);
?>
<div class="site-content full-width">
	<h1 id="species-title">Photo Checklist</h1>
	<?php echo $total_seen; ?> species seen so far!!

	<div id="photo-checklist">
	<?php
		while ($family_row = mysql_fetch_assoc($family_result)) {
			$family_common = $family_row["family_common"];
			$family_count = $family_row["family_count"];
			echo "
				<h2>$family_common</h2>
				<div class='photo-checklist-family'>
			";
			for ($i=0; $i<$family_count; $i++) {
				$row = mysql_fetch_assoc($species_result);
				$common_name = $row["common_name"];
				$is_lifer = $row["is_lifer"];
				$url_common_name = $row["url_common_name"];
				$date = $row["date"];
				$state = $row["state"];
				$flickr_src = $row["flickr_src"];
				$flickr_width = $row["flickr_width"];
				if ($flickr_width == 500)
					$flickr_src = preg_replace("/\.jpg/", "_n.jpg", $flickr_src);
				if ($flickr_src)
					$flickr_height = $row["flickr_height"] * (320 / $flickr_width);
				$flickr_width = 320;
				echo "<a href='/species/?common_name=$url_common_name'>
					<div class='photo-and-caption'>";
					if ($flickr_src)
						echo "<img src='$flickr_src' width='$flickr_width' height='$flickr_height' />";
					else
						echo "<img src='" . get_template_directory_uri() . "/camera-icon.png' height='220' width='320px' />";

					echo "
						<div class='photo-caption'>
							<span class='left'>$common_name</span>
							<span class='right'>&#x2713
					";
						if ($date) {
							$date = date("M j", strtotime($row["date"]));
							echo "$date ";
						}
						if ($state)
							echo "$state ";
						if ($is_lifer)
							echo "<b>LIFER!</b> ";

						echo "</span>";

						// if logged in, show links to edit
						//if ($_SESSION["logged_in"]) {
						//	echo "<a href = '/edit/$url_common_name'>Edit</a>";
						//}

					echo "</div></a>"; // photo-caption
				echo "</div>"; // photo-and-caption
			} // species for loop
			echo "</div>"; // photo-checklist-family
		} // family while loop
	?>
	</div>
</div>
<?php get_footer(); ?>
