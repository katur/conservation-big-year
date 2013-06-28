<?php	
	include("katherine_connect.php");
	/*
	Template Name: Process Species Lists
	Copyright (c) 2013 Katherine Erickson
	*/
	
	// get the common name from the GET variable (visible in the url). 
	$species_id = mysql_real_escape_string($_GET['id']);
	$query = "SELECT 
		url_common_name, seen_this_year
		FROM species_list 
		WHERE id = '$species_id'
	";
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_assoc($result);
	$url_common_name = $row["url_common_name"];
	$seen_this_year = $row["seen_this_year"];


	/* Sightings */	
	if (isset($_POST['state'])) {	
		$state = $_POST['state'];
		$date = $_POST['date'];
		$query = "
			INSERT INTO sighting (species_id, state, date) 
			VALUES ('$species_id', '$state', '$date');
		";
		
		mysql_query($query) or die(mysql_error());
		
		if (!$seen_this_year) {
			$query = "
				UPDATE species_list SET seen_this_year = '1'
				WHERE id = '$species_id';
			";	
			mysql_query($query) or die(mysql_error());
		}	
	
	} else if (isset($_POST['sighting_id'])) {
		$sighting_id = $_POST['sighting_id'];
		
		$query = "
			DELETE FROM sighting 
			WHERE id = '$sighting_id';
		";	
		mysql_query($query) or die(mysql_error());
		
		// count sightings
		$query = "SELECT *
			FROM sighting
			WHERE id = '$sighting_id';
		";
		$result = mysql_query($query) or die(mysql_error());
		if (mysql_num_rows($result) == 0) {
			$query = "
				UPDATE species_list SET seen_this_year = '0'
				WHERE id = '$species_id';
			";	
			mysql_query($query) or die(mysql_error());
		}	
	}
	
	
	/* Links */	
	if (isset($_POST['link'])) {	
		$link = $_POST['link'];
		$link_name = $_POST['link_name'];
		$query = "
			INSERT INTO link (species_id, link, link_name) 
			VALUES ('$species_id', '$link', '$link_name');
		";
		
		mysql_query($query) or die(mysql_error());
	
	} else if (isset($_POST['link_id'])) {
		$link_id = $_POST['link_id'];
		
		$query = "
			DELETE FROM link
			WHERE id = '$link_id';
		";	
		mysql_query($query) or die(mysql_error());
	}
	
	
	/* Factoids */	
	if (isset($_POST['factoid'])) {	
		$factoid = $_POST['factoid'];
		$query = "
			INSERT INTO factoid (species_id, factoid) 
			VALUES ('$species_id', '$factoid');
		";
		
		mysql_query($query) or die(mysql_error());
	
	} else if (isset($_POST['factoid_id'])) {
		$factoid_id = $_POST['factoid_id'];
		
		$query = "
			DELETE FROM factoid
			WHERE id = '$factoid_id';
		";	
		mysql_query($query) or die(mysql_error());
	}


	// set the header to redirect to VIEW page
	wp_redirect("/species/?common_name=$url_common_name");
?>
