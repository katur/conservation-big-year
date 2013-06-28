<?php get_header(); 
	include("katherine_connect.php");
	/*
	Template Name: Edit Species Sighting
	Copyright (c) 2013 Katherine Erickson
	*/
	
	// get the common name from the GET variable (visible in the url). 
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
				

	echo "
		<h3>Sightings</h3>
		<ul id='edit-list'>";	
		$sightings_query = "SELECT
			id, state, date
			FROM sighting
			WHERE species_id = '$species_id'
		";

		// run query
		$sightings_result = mysql_query($sightings_query) or die(mysql_error());
		
		while ($row = mysql_fetch_assoc($sightings_result)) {
			$sighting_id = $row["id"];
			$state = $row["state"];
			$date = $row["date"];
			echo "<li>$state on $date (sighting id: $sighting_id)</li>";
		}
		
		echo "
		</ul>

		<h3>Add a new sighting</h3>
		<form id='add-to-list' method='post' action='/process-species-sighting/?id=$species_id'>
			<span class='add-to-list-row'>
				<span>State:</span><input name='state' type='text' size='20'>
			</span>
			<span class='add-to-list-row'>
				<span>Date (yyyy-mm-dd):</span><input name='date' type='text' size='10'>
			</span>
			
			<input type='submit' value='Add to Database'></input>
		</form>
		
		<h3>Remove a new sighting</h3>
		<form id='add-to-list' method='post' action='/process-species-sighting/?id=$species_id'>
			<span class='add-to-list-row'>
				<span>Id (see above):</span><input name='sighting_id' type='text' size='4'>
			</span>
			
			<input type='submit' value='Remove from Database'></input>
		</form>
	";
?>
