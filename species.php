<?php get_header();
	include("katherine_connect.php");
	/*
	Template Name: Species
	Copyright (c) 2013 Katherine Erickson
	*/

	// get the common name from the url.
	// (php-format url available via htaccess file)
	$url_common_name = mysql_real_escape_string($_GET['common_name']);

	// define MySQL query for info on current species
	$query = "SELECT id, common_name, scientific_name,
		species_list.order, family, family_common, subfamily,
		seen_this_year, seen_in_refuge, seen_only_in_refuge, is_lifer,
		is_probably_extinct, in_conservation_list,
		flickr_code,
		abc_status_id, esa_status_id,
		cornell_map, ebird_map, essay
		FROM species_list
		WHERE url_common_name = '$url_common_name'
	;";

	// run query
	$result = mysql_query($query) or die(mysql_error());
	if (mysql_num_rows($result) != 1) {
		echo "The bird in the URL is not on the list,
			is spelled differently,
			or had more than one match.";

	} else {
		// define variables
		while ($row = mysql_fetch_assoc($result)) {
			$species_id = $row["id"];
			$common_name = $row["common_name"];
			$scientific_name = $row["scientific_name"];
			$order = $row["order"];
			$family = $row["family"];
			$family_common = $row["family_common"];
			$subfamily = $row["subfamily"];
			$seen_this_year = $row["seen_this_year"];
			$seen_in_refuge = $row["seen_in_refuge"];
			$seen_only_in_refuge = $row["seen_only_in_refuge"];
			$is_lifer = $row["is_lifer"];
			$is_probably_extinct = $row["is_probably_extinct"];
			$in_conservation_list = $row["in_conservation_list"];
			$flickr_code = $row["flickr_code"];
			$abc_status_id = $row["abc_status_id"];
			$esa_status_id = $row["esa_status_id"];
			$cornell_map = $row["cornell_map"];
			$ebird_map = $row["ebird_map"];
			$essay = $row["essay"];
		}
	}

	$abc_query = "SELECT status FROM abc_status WHERE id = $abc_status_id;";
	$abc_result = mysql_query($abc_query) or die(mysql_error());
	if (mysql_num_rows($abc_result) == 0) {
		echo "This bird's abc status not found in abc_status table.";
	} else {
		while ($abc_row = mysql_fetch_assoc($abc_result)) {
			$abc_long = $abc_row["status"];
		}
	}

	$esa_query = "SELECT status FROM esa_status WHERE id = $esa_status_id;";
	$esa_result = mysql_query($esa_query) or die(mysql_error());
	if (mysql_num_rows($esa_result) == 0) {
		echo "This bird's esa status not found in esa_status table.";
	} else {
		while ($esa_row = mysql_fetch_assoc($esa_result)) {
			$esa_long = $esa_row["status"];
		}
	}

?>
<div class="site-content full-width">
	<h1 id="species-title">
		<?php echo $common_name;
			if ($seen_this_year)
				echo " &#x2713;";
		?>
	</h1>

	<!-- general -->
	<div class="species-section">
		<!-- image -->
		<div id="primary-image">
			<?php echo $flickr_code ?>
		</div>

		<!-- scientific classification -->
		<div id="scientific-classification">
			<?php
				if ($probably_extinct) echo "<span>Probably Extinct</span>";
			?>
			<span>Order: <?php echo $order; ?></span>
			<span>Family: <?php echo "$family ($family_common)"; ?> </span>
			<?php if ($subfamily) echo "<span>Subfamily: $subfamily</span>"; ?>
			<span>Scientific Name: <i><?php echo $scientific_name; ?></i></span>
			<span></span>
			<?php
				$sightings_query = "SELECT date, state
					FROM sighting
					WHERE species_id = $species_id
					ORDER BY date;
				";
				$sightings_result = mysql_query($sightings_query) or die(mysql_error());
				if (mysql_numrows($sightings_result) > 0) {
					$sightings_row = mysql_fetch_assoc($sightings_result);
					$date = date("M j, Y", strtotime($sightings_row["date"]));
					$state = $sightings_row["state"];
					echo "<span>&#x2713; First seen on $date in $state";
					if ($is_lifer) {
						echo ": LIFER!!";
					}
					echo "</span>";
					if ($sightings_row = mysql_fetch_assoc($sightings_result)) {
						echo "<span>Also seen on:<ul>";
						while ($sightings_row) {
							$date = date("M j, Y", strtotime($sightings_row["date"]));
							$state = $sightings_row["state"];
							echo "<li>$date in $state</li>";
							$sightings_row = mysql_fetch_assoc($sightings_result);
						}
						echo "</ul></span>";
					}


					if ($seen_only_in_refuge)
						echo "<span></span><span>Seen only within the <a href='http://www.fws.gov/refuges/' target='_blank'>National Wildlife Refuge System</a></span>";
					else if ($seen_in_refuge)
						echo "<span></span><span>Seen within the <a href='http://www.fws.gov/refuges/' target='_blank'>National Wildlife Refuge System</a></span>";

				} else {
					if ($is_lifer) {
						echo "<span>Would be a lifer for Laura</span>";
					}
				}
			?>
		</div>
	</div>

	<!-- conservation information -->
	<div id="conservation-classification" class="species-section">
		<h2 class="species-subtitle">Conservation Classification</h2>
		<span>
			<?php
				if ($in_conservation_list)
					echo "In Laura's Conservation List";
				else
					echo "Not in Laura's Conservation List";
			?>
		</span>

		<span>
			<a href="http://www.abcbirds.org/abcprograms/science/watchlist/index.html" target="_blank">ABC WatchList</a>:
			<?php echo $abc_long; ?>
		</span>

		<span>
			<a href="http://www.fws.gov/endangered/species/index.html" target="_blank">ESA Status</a>:
			<?php echo $esa_long; ?>
		</span>
	</div>


	<!-- maps -->
	<?php
		if ($cornell_map or $ebird_map) {
			echo "<h2 class='species-subtitle'>Maps</h2><div id='species-maps' class='species-section'>";

			if ($cornell_map)
				echo "<a href='$cornell_map' target='_blank'>Cornell's Range Map</a>";

			if ($ebird_map)
				echo "<a href='$ebird_map' target='_blank'>eBird Dynamic Map</a>";

			echo "</div>";
		}
	?>

	<!-- links -->
	<?php
		$links_query = "SELECT link, link_name
			FROM link
			WHERE species_id = $species_id;
		";
		$links_result = mysql_query($links_query) or die(mysql_error());
		if (mysql_numrows($links_result) > 0) {
			echo "<div id='species-links' class='species-section'>
				<h2 class='species-subtitle'>Links</h2>";
			while ($links_row = mysql_fetch_assoc($links_result)) {
				$link = $links_row["link"];
				$link_name = $links_row["link_name"];
				echo "<a target='_blank' href='$link'>$link_name</a>";
			}
			echo "</div>";
		}
	?>

	<!-- factoids -->
	<?php
		$factoids_query = "SELECT factoid
			FROM factoid
			WHERE species_id = $species_id;
		";
		$factoids_result = mysql_query($factoids_query) or die(mysql_error());
		if (mysql_numrows($factoids_result) > 0) {
			echo "<h2 class='species-subtitle'>Factoids</h2>
				<ul id='factoids' class='species-section'>";
			while ($factoids_row = mysql_fetch_assoc($factoids_result)) {
				$factoid = $factoids_row["factoid"];
				echo "<li>$factoid</li>";
			}
			echo "</ul>";
		}
	?>

	<!-- writing -->
	<?php
		if ($essay) {
			echo "<div class='species-section'>
					<h2 class='species-subtitle'>Conservation Concerns</h2>
					<div id='species-essay'>$essay</div>
				</div>
			";
		}

		if (current_user_can( 'edit_pages' )) {
			echo "
				<div id='edit-buttons'>
					<a href='/edit-species-info/?common_name=$url_common_name'>
						Edit Info
					</a>
					<a href='/edit-species-lists/?common_name=$url_common_name'>
						Edit List-Type Info
					</a>
				</div>
			";
		}
	?>
	</div>
</div>
