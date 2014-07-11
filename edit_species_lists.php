<?php get_header();
	include("katherine_connect.php");
	/*
	Template Name: Edit Species Lists
	Copyright (c) 2013 Katherine Erickson
	*/

	// get the common name from the GET variable (in the url).
	$url_common_name = mysql_real_escape_string($_GET['common_name']);
	$query = "SELECT
		id, common_name
		FROM species_list
		WHERE url_common_name = '$url_common_name'
	";
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_assoc($result);
	$species_id = $row["id"];
	$common_name = $row["common_name"];

	echo "
		<div class='site-content full-width edit-species'>
			<h2 class='species-subtitle'>
				Edit list-type information for <b>$common_name</b>
			</h2>
	";


	/* Sightings */
	echo "
		<div class='edit-list-section'>
			<h3>Sightings</h3>
			<ul class='edit-list-list'>";
			$sightings_query = "SELECT
				id, state, date
				FROM sighting
				WHERE species_id = '$species_id'
			";
			$sightings_result = mysql_query($sightings_query) or die(mysql_error());

			while ($row = mysql_fetch_assoc($sightings_result)) {
				$sighting_id = $row["id"];
				$state = $row["state"];
				$date = $row["date"];
				echo "<li>On $date in $state (id: $sighting_id)</li>";
			}

			echo "
			</ul>

			<div class='modify-list-subsection'>
				<h3>Add a sighting</h3>
				<form method='post' action='/process-species-lists/?id=$species_id'>
					<span class='modify-list-row'>
						<span>State:</span><input name='state' type='text' size='20'>
					</span>
					<span class='modify-list-row'>
						<span>Date (yyyy-mm-dd):</span><input name='date' type='text' size='10'>
					</span>

					<input type='submit' value='Add to Database'></input>
				</form>
			</div>

			<div class='modify-list-subsection'>
				<h3>Remove a sighting</h3>
				<form method='post' action='/process-species-lists/?id=$species_id'>
					<span class='modify-list-row'>
						<span>Id (see above):</span><input name='sighting_id' type='text' size='4'>
					</span>

					<input type='submit' value='Remove from Database'></input>
				</form>
			</div>
		</div>
	";


	/* Links */
	echo "
		<div class='edit-list-section'>
			<h3>Links</h3>
			<ul class='edit-list-list'>";
			$links_query = "SELECT
				id, link, link_name
				FROM link
				WHERE species_id = '$species_id'
			";
			$links_result = mysql_query($links_query) or die(mysql_error());

			while ($row = mysql_fetch_assoc($links_result)) {
				$link_id = $row["id"];
				$link = $row["link"];
				$link_name = $row["link_name"];
				echo '
					<li>
						<a target="_blank" href="$link">
							$link_name
						</a> (id: $link_id)
					</li>
				';
			}

			echo "
			</ul>

			<div class='modify-list-subsection'>
				<h3>Add a link</h3>
				<form method='post' action='/process-species-lists/?id=$species_id'>
					<span class='modify-list-row'>
						<span>Link Name:</span>
						<input name='link_name' type='text' size='25'>
					</span>
					<span class='modify-list-row'>
						<span>URL (http://):</span><input name='link' type='text' size='20'>
					</span>

					<input type='submit' value='Add to Database'></input>
				</form>
			</div>

			<div class='modify-list-subsection'>
				<h3>Remove a link</h3>
				<form method='post' action='/process-species-lists/?id=$species_id'>
					<span class='modify-list-row'>
						<span>Id (see above):</span><input name='link_id' type='text' size='4'>
					</span>

					<input type='submit' value='Remove from Database'></input>
				</form>
			</div>
		</div>
	";


	/* Factoids */
	echo "
		<div class='edit-list-section'>
			<h3>Factoids</h3>
			<ul class='edit-list-list'>";
			$factoids_query = "SELECT
				id, factoid
				FROM factoid
				WHERE species_id = '$species_id'
			";
			$factoids_result = mysql_query($factoids_query) or die(mysql_error());

			while ($row = mysql_fetch_assoc($factoids_result)) {
				$factoid_id = $row["id"];
				$factoid = $row["factoid"];
				echo "
					<li>
						$factoid (id: $factoid_id)
					</li>
				";
			}

			echo "
			</ul>

			<div class='modify-list-subsection'>
				<h3>Add a factoid</h3>
				<form method='post' action='/process-species-lists/?id=$species_id'>
					<span class='modify-list-row'>
						<span>Factoid:</span>
						<input name='factoid' type='text' size='25'>
					</span>
					<input type='submit' value='Add to Database'></input>
				</form>
			</div>

			<div class='modify-list-subsection'>
				<h3>Remove a factoid</h3>
				<form method='post' action='/process-species-lists/?id=$species_id'>
					<span class='modify-list-row'>
						<span>Id (see above):</span>
						<input name='factoid_id' type='text' size='4'>
					</span>
					<input type='submit' value='Remove from Database'></input>
				</form>
			</div>
		</div>
	";
?>
